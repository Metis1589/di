<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 11/17/2014
 * Time: 3:20 PM
 */

namespace gateway\models;

use common\components\voucherServices\EagleEyeValidationService;
use common\enums\DeliveryType;
use common\enums\RecordType;
use common\enums\VoucherCategory;
use common\enums\VoucherValueType;
use common\models\Address;
use common\models\MenuItem;
use common\models\Order;
use common\models\OrderItem;
use common\models\Voucher;
use common\models\VoucherUseHistory;
use DateTime;
use gateway\modules\v1\services\VoucherCalculator;
use Yii;
use yii\base\ErrorException;

/**
 * Class SessionUser
 * @package api\models
 */
class SessionUser
{
    /**
     *
     * @var string Delivery Type
     */
    public $delivery_type = DeliveryType::DeliveryAsap;

    /**
     * @var DateTime later date
     */
    public $later_date_from;

    /**
     * @var DateTime later date to
     */
    public $later_date_to;

    /**
     * @var string postcode
     */
    public $postcode;

    /**
     * @var float Delivery charge
     */
    public $delivery_charge;

    /**
     * @var float drive charge
     */
    public $driver_charge = 1;

    /**
     * @var string latitude
     */
    public $latitude;

    /**
     * @var string longitude
     */
    public $longitude;

    /**
     * @var int current restaurant id
     */
    public $restaurant_id;

    /**
     * @var array order items
     */
    public $order_items = [];

    /**
     * @var Voucher
     */
    public $voucher;

    /**
     * Third party voucher code
     * @var string
     */
    public $voucher_code;

    /**
     * @var float discount value
     */
    public $discount_items = 0;

    /**
     * @var float discount to delivery charge value
     */
    public $discount_delivery_charge = 0;

    /**
     * @var int client id
     */
    public $client_id;

    /**
     * @var array addresses
     */
    public $addresses = [];

    /**
     * @var array corp order users
     */
    public $corp_users = [];

    /**
     * @var array expense type
     */
    public $expense_type;
    
    /**
     * @var order object
     */
    public $order;

    /**
     * Load addresses
     * @param $user_id
     */
    public function loadAddresses($user_id) {
        $this->addresses = Address::find()
            ->joinWith('userAddresses', false, 'INNER JOIN')
            ->where(['user_address.user_id' => $user_id, 'address.record_type' => RecordType::Active])
            ->select(
                [
                    'address.id',
                    'address.name',
                    'address.title',
                    'address.first_name',
                    'address.last_name',
                    'address.address1',
                    'address.address2',
                    'address.address3',
                    'address.city',
                    'address.postcode',
                    'address.phone',
                    'address.email',
                    'address.instructions'
                ])
            ->asArray()->all();
    }

    /**
     * @return float order sub total
     */
    public function getSubtotal() {
        $result = 0.0;
        /** @var OrderItem $orderItem */
        foreach ($this->order_items as $orderItem) {
            $result += $orderItem->getWebTotal(true, true);
        }

        return $result;
    }

    /**
     * get order total
     * @return float order total
     */
    public function getTotal() {
        return $this->getSubtotal() + $this->delivery_charge + $this->driver_charge - $this->discount_items - $this->discount_delivery_charge;
    }

    public function getTotalAllocated() {
        if (!isset($this->corp_users)) {
            return null;
        }

        $result = 0;

        foreach($this->corp_users as $corpUser) {
            $result += $corpUser->allocation;
        }
        return $result;
    }

    /**
     * @return float restaurant subtotal
     */
    public function getRestaurantSubtotal() {
        $result = 0.0;
        /** @var OrderItem $orderItem */
        foreach ($this->order_items as $orderItem) {
            $result += $orderItem->getRestaurantTotal(true);
        }

        return $result;
    }

    /**
     * @return float restaurant discount value
     */
    public function getRestaurantDiscountValue() {
        if ($this->voucher && $this->voucher['promotion_type'] == 'Restaurant') {
            return $this->discount_items + $this->discount_delivery_charge;
        }
        return 0.0;
    }

    /**
     * @return float restaurant total
     */
    public function getRestaurantTotal() {
        return $this->getRestaurantSubtotal() - $this->getRestaurantDiscountValue();
    }

    /**
     * @return int
     */
    public function getAllQuantities() {
        $result = 0.0;
        /** @var OrderItem $orderItem */
        foreach ($this->order_items as $orderItem) {
            $result += $orderItem->quantity;
        }

        return $result;
    }

    public function clearOrder() {
        $this->order = null;
        $this->order_items = [];
        $this->corp_users = [];
        $this->expense_type = null;
        VoucherCalculator::clearVoucher($this);
        Yii::$app->userCache->setUser($this);
    }
}