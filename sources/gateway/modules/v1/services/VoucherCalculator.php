<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/26/2015
 * Time: 7:43 PM
 */

namespace gateway\modules\v1\services;

use common\components\language\T;
use common\enums\VoucherCategory;
use common\enums\VoucherDiscountType;
use common\enums\VoucherValueType;
use common\models\OrderItem;
use Exception;
use gateway\models\SessionUser;
use Yii;
use yii\helpers\ArrayHelper;

class VoucherCalculator {

    /**
     * @var OrderItem[] order items
     */
    private $order_items;

    /**
     * @var
     */
    private $voucher;

    /**
     * @var
     */
    private $restaurant_id;

    /**
     * @var float
     */
    private $delivery_charge;

    /**
     * @var
     */
    public $discount_items;

    /**
     * @var string error message
     */
    public $error;

    /**
     * @var
     */
    public $discount_delivery_charge;

    function __construct(&$order_items, $voucher, $delivery_charge, $restaurant_id)
    {
        $this->order_items = &$order_items;
        $this->voucher = $voucher;
        $this->delivery_charge = $delivery_charge;
        $this->restaurant_id = $restaurant_id;
    }

    /**
     * @param SessionUser $session_user
     * @param null $error
     * @return bool
     */
    public static function calculateDiscountBySessionUser(&$session_user, &$error = null) {
        $calc = new VoucherCalculator($session_user->order_items, $session_user->voucher, $session_user->delivery_charge, $session_user->restaurant_id);

        if ($calc->calculateDiscount()) {
            $session_user->discount_items = $calc->discount_items;
            $session_user->discount_delivery_charge = $calc->discount_delivery_charge;
            return true;
        }
        else {
            static::clearVoucher($session_user);
            $error = $calc->error;
            return false;
        }
    }

    /**
     * clear voucher data with discounts
     * @param SessionUser $session_user
     */
    public static function clearVoucher(&$session_user) {
        $session_user->voucher = null;
        $session_user->voucher_code = null;
        $session_user->discount_items = 0;
        $session_user->discount_delivery_charge = 0;

        foreach ($session_user->order_items as &$orderItem) {

            $orderItem->discount = 0;

            foreach ($orderItem->options as &$option) {
                $option->discount = 0;
            }
        }
    }

    /**
     * @param null $order_items
     * @return int
     */
    private function getAllQuantities($order_items = null) {

        if ($order_items === null) {
            $order_items = $this->order_items;
        }

        $result = 0;
        /** @var OrderItem $orderItem */
        foreach ($order_items as $orderItem) {
            $result += $orderItem->quantity;
        }

        return $result;
    }

    /**
     * @param $menu_item_id
     * @return OrderItem|null
     */
    private function getOrderItemByMenuItemId($menu_item_id) {
        /** @var OrderItem $orderItem */
        foreach ($this->order_items as &$orderItem) {
            if ($orderItem->menu_item_id == $menu_item_id) {
                return $orderItem;
            }
        }

        return null;
    }

    /**
     * @param OrderItem[]|null $order_items
     * @param bool $include_options_total
     * @param bool $include_quantity
     * @return float
     */
    private function getItemsTotal($order_items = null, $include_options_total = true, $include_quantity = true) {
        if ($order_items === null) {
            $order_items = $this->order_items;
        }

        $result = 0.0;

        foreach ($order_items as $orderItem) {
            // if or something
            $result += $orderItem->getWebTotal($include_options_total, $include_quantity);
        }

        return $result;
    }

    /**
     * @param OrderItem[]|null $order_items
     * @param bool $include_options_total
     * @param bool $include_quantity
     */
    private function distributeDiscount($order_items = null, $include_options_total = true, $include_quantity = true) {
        if ($order_items === null) {
            $order_items = &$this->order_items;
        }
        $items_total = $this->getItemsTotal($order_items, $include_options_total, $include_quantity);

        foreach ($order_items as &$orderItem) {
            $orderItem->discount = $orderItem->web_price * ($include_quantity ? $orderItem->quantity : 1) * $this->discount_items / $items_total;

            if ($include_options_total) {
                foreach ($orderItem->options as &$option) {
                    $option->discount = $option->web_price * ($include_quantity ? $option->quantity : 1) * $this->discount_items / $items_total;
                }
            }
        }
    }

    /**
     * @param $menu_item_ids
     * @return \common\models\OrderItem[]
     * @internal param $category_ids
     */
    private function getOrderItemsByMenuIDs($menu_item_ids) {

        $result = [];

        foreach ($this->order_items as &$orderItem) {
            if (in_array($orderItem->menu_item_id, $menu_item_ids)) {
                $result[] = &$orderItem;
            }
        }

        return $result;
    }

    /**
     * @param $category_ids
     * @param $limit
     * @return \common\models\OrderItem[]
     */
    private function getOrderItemsByCategoryIDs($category_ids, $limit = null) {

        $result = [];

        foreach ($this->order_items as &$order_item) {
            $menu_item = Yii::$app->globalCache->getMenuItem($this->restaurant_id, $order_item->menu_item_id);

            if (in_array($menu_item['menu_category_id'], $category_ids)) {
                $result[] = &$order_item;

                if ($limit !== null && count($result) == $limit) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @param $field_key
     * @param $field_value
     * @return \common\models\OrderItem[]
     */
    private function getOrderItemsByCustomField($field_key, $field_value) {
        $result = [];

        foreach ($this->order_items as &$order_item) {
            $menu_item = Yii::$app->globalCache->getMenuItem($this->restaurant_id, $order_item->menu_item_id);
            if (array_key_exists($field_key, $menu_item['customFields']) && $menu_item['customFields'][$field_key] === $field_value) {
                $result[] = &$order_item;
            }
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function calculateDiscount() {

        try {

            $this->clearDiscounts();

            if (empty($this->voucher)) {
                return true;
            }

            switch ($this->voucher['category']) {
                case VoucherCategory::Free:
                    $this->discount_delivery_charge = $this->delivery_charge;

                    break;
                case VoucherCategory::Delivery:
                    if ($this->getAllQuantities($this->order_items) >= $this->voucher['item_quantity']) {
                        if ($this->voucher['value_type'] == VoucherValueType::Percent) {
                            $this->discount_delivery_charge = $this->delivery_charge * $this->voucher['discount_value'] / 100;
                        } else {
                            if ($this->delivery_charge >= $this->voucher['discount_value']) {
                                $this->discount_delivery_charge = $this->voucher['discount_value'];
                            }
                            else {
                                $this->error = T::l('Min delivery charge should be ') . $this->voucher['discount_value'];
                                return false;
                            }
                        }
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }
                    break;
                case VoucherCategory::Wine:
                    $order_items = $this->getOrderItemsByCustomField('Is Alcohol', '1'); // todo hardcode

                    if ($this->getAllQuantities($order_items) < $this->voucher['item_quantity']) {
                        $this->error = T::l('Min alcohol items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }

                    if ($this->voucher['value_type'] == VoucherValueType::Percent) {
                        $this->discount_items = $this->getItemsTotal($order_items) * $this->voucher['discount_value'] / 100;
                    } else {
                        if ($this->getItemsTotal($order_items) >= $this->voucher['discount_value']) {
                            $this->discount_items = $this->voucher['discount_value'];
                        }
                        else {
                            $this->error = T::l('Min items total should be ') . $this->voucher['discount_value'];
                            return false;
                        }
                    }

                    $this->distributeDiscount($order_items);
                    break;
                case VoucherCategory::Food:
                    if ($this->getAllQuantities() >= $this->voucher['item_quantity']) {
                        if ($this->voucher['value_type'] == VoucherValueType::Percent) {
                            $this->discount_items = $this->getItemsTotal() * $this->voucher['discount_value'] / 100;
                        } else {
                            if ($this->getItemsTotal() >= $this->voucher['discount_value']) {
                                $this->discount_items = $this->voucher['discount_value'];
                            }
                            else {
                                $this->error = T::l('Min total should be ') . $this->voucher['discount_value'];
                                return false;
                            }
                        }

                        $this->distributeDiscount();
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }

                    break;
                case VoucherCategory::All:
                    if ($this->getAllQuantities() >= $this->voucher['item_quantity']) {
                        if ($this->voucher['value_type'] == VoucherValueType::Percent) {
                            $this->discount_items = $this->getItemsTotal() * $this->voucher['discount_value'] / 100;
                            $this->discount_delivery_charge = $this->delivery_charge * $this->voucher['discount_value'] / 100;
                        } else {
                            if ($this->getItemsTotal() + $this->delivery_charge >= $this->voucher['discount_value']) {
                                $this->discount_items = $this->voucher['discount_value'] / ($this->getItemsTotal() + $this->delivery_charge) * $this->getItemsTotal();
                                $this->discount_delivery_charge = $this->voucher['discount_value'] / ($this->getItemsTotal() + $this->delivery_charge) * $this->delivery_charge;
                            }
                            else {
                                $this->error = T::l('Min total + delivery charge should be ') . $this->voucher['discount_value'];
                                return false;
                            }
                        }

                        $this->distributeDiscount();
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }
                    break;
                case VoucherCategory::MenuItems:
                    /** @var OrderItem $order_item */
                    $order_item = &$this->getOrderItemByMenuItemId($this->voucher['menu_item_id']);

                    if ($order_item == null) {
                        $this->error = 'Item not selected';
                        return false;
                    }

                    if ($order_item->quantity >= $this->voucher['item_quantity']) {
                        if ($this->voucher['discount_type'] == VoucherDiscountType::Price) {
                            if ($order_item->web_price * $order_item->quantity >= $this->voucher['price_value']) {
                                $this->discount_items = $order_item->web_price * $order_item->quantity - $this->voucher['price_value'];
                            }
                            else {
                                $this->error = T::l('Voucher price value greater than item price');
                                return false;
                            }
                        }
                        else {
                            if ($this->voucher['value_type'] == VoucherValueType::Percent) {
                                $this->discount_items = $order_item->web_price * $this->voucher['item_quantity'] * $this->voucher['discount_value'] / 100;
                            } else {
                                if ($order_item->web_price * $order_item->quantity >= $this->voucher['discount_value']) {
                                    $this->discount_items = $this->voucher['discount_value'];
                                }
                                else {
                                    $this->error = T::l('Min item price should be ') . $this->voucher['discount_value'];
                                    return false;
                                }
                            }
                        }

                        $this->distributeDiscount([$order_item]);
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }
                    break;
                case VoucherCategory::FoodPrice:
                    if ($this->getItemsTotal() >= $this->voucher['price_value']) {
                        if ($this->voucher['value_type'] == VoucherValueType::Percent) {
                            $this->discount_items = $this->getItemsTotal() * $this->voucher['discount_value'] / 100;
                        } else {
                            $this->discount_items = $this->voucher['discount_value'];
                        }
                        $this->distributeDiscount();
                    }
                    else {
                        $this->error = T::l('Total should be >= ' . $this->voucher['price_value']);
                        return false;
                    }

                    break;
                case VoucherCategory::FreeWithinCategory:

                    if ($this->getAllQuantities() >= $this->voucher['item_quantity']) {

                        // find items in source category
                        $source_menu_category_id = $this->getMenuCategoryId('Source');

                        $source_menu_items = null;
                        if ($source_menu_category_id != null) {
                            $source_menu_items = &$this->getOrderItemsByCategoryIDs([$source_menu_category_id]);
                        }

                        // find items in target category
                        $target_menu_category_id = $this->getMenuCategoryId('Target');

                        if ($target_menu_category_id == null) {
                            $this->error = T::l('No items in target category');
                            return false;
                        }

                        $target_menu_items = &$this->getOrderItemsByCategoryIDs([$target_menu_category_id]);

                        // 1. check if source item exists

                        if ($source_menu_items !== null && count($source_menu_items) == 0) {
                            // source item is missing
                            $this->error = T::l('No items in source category');
                            return false;
                        }

                        // 2. check if target items exists
                        if (count($target_menu_items) == 0) {
                            // source item is missing
                            $this->error = T::l('No items in target category');
                            return false;
                        }

                        // 3. find least expensive order item
                        $order_item = &$this->getCheapestItem($target_menu_items);

                        $this->discount_items = $order_item->web_price;

                        $this->distributeDiscount([$order_item], false);
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }
                    break;
                case VoucherCategory::FreeItem:

                    if ($this->getAllQuantities() >= $this->voucher['item_quantity']) {
                        // 1. find target order item
                        $order_item = &$this->getOrderItemByMenuItemId($this->voucher['voucherMenuItems'][0]['menu_item_id']);

                        if ($order_item == null) {
                            $this->error = T::l('Order item not selected');
                            return false;
                        }

                        $this->discount_items = $order_item->web_price;

                        $this->distributeDiscount([$order_item], false, false);
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }

                    break;
                case VoucherCategory::OffByCategory:

                    if ($this->getAllQuantities() >= $this->voucher['item_quantity']) {
                        $source_menu_items = &$this->getOrderItemsByCategoryIDs(ArrayHelper::getColumn($this->voucher['voucherMenuCategories'], 'menu_category_id'));

                        if (count($source_menu_items) == 0) {
                            $this->error = T::l('No source items selected');
                            return false;
                        }

                        if ($this->voucher['value_type'] == VoucherValueType::Percent) {
                            $this->discount_items = $this->getItemsTotal($source_menu_items) * $this->voucher['discount_value'] / 100;
                        } else {
                            if ($this->getItemsTotal($source_menu_items) >= $this->voucher['discount_value']) {
                                $this->discount_items = $this->voucher['discount_value'];
                            }
                            else {
                                $this->error = T::l('Min items total should be ') . $this->voucher['discount_value'];
                                return false;
                            }
                        }

                        $this->distributeDiscount($source_menu_items, true, true);
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }

                    break;
                case VoucherCategory::MultipleItemsSinglePrice:
                case VoucherCategory::MultipleCategoriesSinglePrice:
                    if ($this->getAllQuantities() >= $this->voucher['item_quantity']) {
                        if ($this->voucher['category'] == VoucherCategory::MultipleItemsSinglePrice) {
                            $order_items = &$this->getOrderItemsByMenuIDs(ArrayHelper::getColumn($this->voucher['voucherMenuItems'], 'menu_item_id'));

                            if (count($order_items) != count($this->voucher['voucherMenuItems'])) {
                                $this->error = T::l('Discount items not selected');
                                return false;
                            }
                        }
                        else {
                            $order_items = &$this->getOrderItemsByCategoryIDs(ArrayHelper::getColumn($this->voucher['voucherMenuCategories'], 'menu_category_id'), $this->voucher['item_quantity']);

                            if (count($order_items) != $this->voucher['item_quantity']) {
                                $this->error = T::l('Discount items not selected');
                                return false;
                            }
                        }

                        if ($this->getItemsTotal($order_items, true, true) < $this->voucher['discount_value']) {
                            $this->error = T::l('Negative discount');
                            return false;
                        }

                        $this->discount_items = $this->getItemsTotal($order_items, true, true) - $this->voucher['discount_value'];

                        $this->distributeDiscount($order_items, true, true);
                    }
                    else {
                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
                        return false;
                    }

                    break;
//                case VoucherCategory::MultipleCategoriesSinglePrice:
//
//                    if ($this->getAllQuantities() >= $this->voucher['item_quantity']) {
//                        $order_items = &$this->getOrderItemsByCategoryIDs(ArrayHelper::getColumn($this->voucher['voucherMenuCategories'], 'menu_category_id'));
//
//                        if (count($order_items) == 0) {
//                            $this->error = T::l('Discount items not selected');
//                            return false;
//                        }
//
//                        $this->discount_items = $this->getItemsTotal($order_items) - $this->voucher['discount_value'];
//
//                        $this->distributeDiscount($order_items, false, false);
//                    }
//                    else {
//                        $this->error = T::l('Min items quantity should be >= ' . $this->voucher['item_quantity']);
//                        return false;
//                    }
//
//                    break;
            }

            return true;
        }
        catch (Exception $ex) {
            $this->error = T::l('Error applying voucher: ' . $ex->getMessage());
            return false;
        }
    }

    private function getMenuCategoryId($menu_category_type) {
        foreach ($this->voucher['voucherMenuCategories'] as $category) {
            if ($category['menu_category_type'] == $menu_category_type) {
                return $category['menu_category_id'];
            }
        }

        return null;
    }

    /**
     * @param OrderItem[] $order_items
     * @return OrderItem
     */
    private function getCheapestItem(&$order_items) {
        /** @var OrderItem $result */
        $result = null;

        foreach ($order_items as &$order_item) {
            if ($result == null || $result->web_price > $order_item->web_price) {
                $result = &$order_item;
            }
        }

        return $result;
    }

    /**
     * clear discount values for each item with options
     */
    private function clearDiscounts() {
        foreach ($this->order_items as &$orderItem) {

            $orderItem->discount = 0;

            foreach ($orderItem->options as &$options) {
                $options->discount = 0;
            }
        }
    }
}
