<?php
namespace gateway\modules\v1\actions\common;

use common\models\MenuItem;
use common\models\MenuOption;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderOption;
use gateway\models\SessionUser;
use gateway\modules\v1\components\OrderHelper;
use gateway\modules\v1\forms\common\SetOrderItemForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use gateway\modules\v1\services\VoucherCalculator;
use Yii;

class SetOrderItemAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return SetOrderItemForm
	 */
	protected function createRequestForm()
	{
		return new SetOrderItemForm();
	}

	/**
	 * Set order item.
	 *
	 * @param SetOrderItemForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {

            $menuItem = MenuItem::findOne(['id' => $requestForm->menu_item_id]);

            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            $orderItems = &$session_user->order_items;

            if (array_key_exists($requestForm->order_item_id, $orderItems)) {
                $orderItem = $orderItems[$requestForm->order_item_id];

                if (empty($requestForm->selected_options)) {
                    $orderItem->options = [];
                }

                //update existed options quantity/delete options
                foreach($orderItem->options as $key => &$existedOption) {
                    $menuOptionId = $existedOption->menu_option_id;
                    $inRequestOption = array_filter($requestForm->selected_options, function($o) use (&$menuOptionId) {
                        return $o['option']['id'] == $menuOptionId;
                    });
                    $inRequestOption = array_values($inRequestOption);
                    if (count($inRequestOption) > 0) {
                        $existedOption->quantity = $inRequestOption[0]['quantity'];
                    } else {
                        unset($orderItem->options[$key]);
                    }
                }

                //add new options
                if (!empty($requestForm->selected_options)) {
                    foreach ($requestForm->selected_options as $passedOption) {
                        $menuOptionId = $passedOption['option']['id'];
                        $isExist = false;
                        foreach ($orderItem->options as $o) {
                            if ($o->menu_option_id == $menuOptionId) {
                                $isExist = true;
                                break;
                            }
                        }

                        if (!$isExist) {
                            $orderItem->options[] = $this->createOptionByRequestOption($orderItem, $passedOption);
                        }
                    }
                }

            } else {
                $orderItem = new OrderItem();
                $orderItem->id = (int)$requestForm->order_item_id;
                $orderItem->menu_item_id = (int)$requestForm->menu_item_id;
                $orderItem->name = Yii::$app->globalCache->getLabel($menuItem->name_key);
                $orderItem->web_price = (float)$menuItem->web_price;
                $orderItem->restaurant_price = (float)$menuItem->restaurant_price;
                $orderItem->is_alcohol = $menuItem->is_alcohol;
                $orderItem->web_total = $orderItem->web_price;
                $orderItem->restaurant_total = $orderItem->restaurant_price;
                $orderItem->cook_time = $menuItem->cook_time;
                if ($requestForm->selected_options) {

                    foreach ($requestForm->selected_options as $selected_option) {
                        $orderItem->options[] = $this->createOptionByRequestOption($orderItem, $selected_option);
                    }
                }
            }

            $orderItem->quantity = (int)$requestForm->quantity;
            $orderItem->special_instructions = $requestForm->special_instructions;

            if ($orderItem->quantity <= 0) {
                unset($orderItems[$requestForm->order_item_id]);
            }
            else {
                $orderItems[$requestForm->order_item_id] = $orderItem;
            }

            VoucherCalculator::calculateDiscountBySessionUser($session_user);

            Yii::$app->userCache->setUser($session_user);

            return OrderHelper::getOrderResponse($requestForm->client_key);
		}
		catch (Exception $ex) {
			return $ex;
		}
	}

    private function createOptionByRequestOption($orderItem, $requestOption) {
        $menuOption = MenuOption::findOne(['id' => $requestOption['option']['id']]);

        $option = new OrderOption();

        $option->menu_option_id = $requestOption['option']['id'];
        $option->quantity = $requestOption['quantity'];

        $option->web_price = $menuOption->getWebPriceRecursive();
        $option->restaurant_price = $menuOption->getRestaurantPriceRecursive();

        $option->name = $menuOption->name_key;

        $orderItem->web_total += $option->web_price * $option->quantity;
        $orderItem->restaurant_total += $option->restaurant_price * $option->quantity;

        return $option;
    }
}