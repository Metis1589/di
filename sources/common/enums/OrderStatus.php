<?php
namespace common\enums;

use common\components\language\T;
use gateway\modules\v1\services\OrderService;
use Yii;

class OrderStatus extends BaseEnum {
    const ProcessingPayment        = 'ProcessingPayment';
    const PaymentReceived          = 'PaymentReceived';
    const TransferringToRestaurant = 'TransferringToRestaurant';
    const ReadyBy                  = 'ReadyBy';
    const OrderConfirmed           = 'OrderConfirmed';
    const FoodPreparing            = 'FoodPreparing';
    const FoodIsReady              = 'FoodIsReady';
    const AssignedToDriver         = 'AssignedToDriver';
    const RequestDriver            = 'RequestDriver';
    const AcceptByDriver           = 'AcceptByDriver';
    const WayToPickUp              = 'WayToPickUp';
    const EstimatedDeliveryTime    = 'EstimatedDeliveryTime';
    const DriverAtRestaurant       = 'DriverAtRestaurant';
    const DriverWaiting            = 'DriverWaiting';
    const DriverPickedUp           = 'DriverPickedUp';
    const FoodEnRoute              = 'FoodEnRoute';
    const ArrivedAtCustomer        = 'ArrivedAtCustomer';
    const Delivered                = 'Delivered';
    const OrderCancelled           = 'OrderCancelled';
    const Collected                = 'Collected';

    private static $abbr = [
        self::ProcessingPayment        => 'PAYP',
        self::PaymentReceived          => 'PAYR',
        self::TransferringToRestaurant => 'TRANS',
        self::ReadyBy                  => 'RDYBY',
        self::OrderConfirmed           => 'CONF',
        self::FoodPreparing            => 'PREP',
        self::FoodIsReady              => 'RDY',
        self::AssignedToDriver         => 'ASGN',
        self::RequestDriver            => 'REQ',
        self::AcceptByDriver           => 'ACPT',
        self::EstimatedDeliveryTime    => 'EDT',
        self::WayToPickUp              => 'W2R',
        self::DriverAtRestaurant       => 'ATREST',
        self::DriverWaiting            => 'WAIT',
        self::DriverPickedUp           => 'PICK',
        self::FoodEnRoute              => 'WAY',
        self::ArrivedAtCustomer        => 'ARRIV',
        self::Delivered                => 'DLVD',
        self::OrderCancelled           => 'CNCL', // CANC
        self::Collected                => 'CLTD',
    ];

    public static function getLabels() {
        return [
            self::ProcessingPayment        => T::l('Processing Payment'),
            self::PaymentReceived          => T::l('Payment Received'),
            self::TransferringToRestaurant => T::l('Transferring To Restaurant'),
            self::ReadyBy                  => T::l('Ready By'),
            self::OrderConfirmed           => T::l('Order Confirmed'),
            self::FoodPreparing            => T::l('Food Preparing'),
            self::FoodIsReady              => T::l('Food Is Ready'),
            self::AssignedToDriver         => T::l('Assigned To Driver'),
            self::RequestDriver            => T::l('Request Driver'),
            self::AcceptByDriver           => T::l('Accept By Driver'),
            self::EstimatedDeliveryTime    => T::l('Estimated Delivery Time'),
            self::WayToPickUp              => T::l('Way To Pick Up'),
            self::DriverAtRestaurant       => T::l('Driver At Restaurant'),
            self::DriverWaiting            => T::l('Driver Waiting'),
            self::DriverPickedUp           => T::l('Driver Picked Up'),
            self::FoodEnRoute              => T::l('Food En Route'),
            self::ArrivedAtCustomer        => T::l('Arrived At Customer'),
            self::Delivered                => T::l('Delivered'),
            self::OrderCancelled           => T::l('Cancelled'),
            self::Collected                => T::l('Collected'),
        ];
    }

    /**
     * get allowed change statuses for Delivery ASAP/Later by Client (Dinein)
     * @return array
     */
    public static function getAllowedStatusesLabelsCDAL() {
        $statuses = OrderService::getAllowedStatuses(Yii::$app->user->isGuest ? UserType::UNAUTHORIZED : Yii::$app->user->identity->user_type);

        $statuses = array_intersect_key(static::getLabels(), $statuses);

        unset($statuses[OrderStatus::Collected]);

        return $statuses;
    }

    /**
     * get allowed change statuses for Delivery ASAP/Later by Restaurant (Dinein)
     * @return array
     */
    public static function getAllowedStatusesLabelsRDAL() {
        $statuses = OrderService::getAllowedStatuses(Yii::$app->user->isGuest ? UserType::UNAUTHORIZED : Yii::$app->user->identity->user_type);

        unset($statuses[OrderStatus::ReadyBy]);
        unset($statuses[OrderStatus::Collected]);

        $statuses = array_intersect_key(static::getLabels(), $statuses);

        return $statuses;
    }

    /**
     * get allowed change statuses for Collection ASAP/Later by Client/Restaurant
     * @return array
     */
    public static function getAllowedStatusesLabelsCAL() {
        $statuses = OrderService::getAllowedStatuses(Yii::$app->user->isGuest ? UserType::UNAUTHORIZED : Yii::$app->user->identity->user_type);

        unset($statuses[OrderStatus::ReadyBy]);
        unset($statuses[OrderStatus::AssignedToDriver]);
        unset($statuses[OrderStatus::WayToPickUp]);
        unset($statuses[OrderStatus::DriverAtRestaurant]);
        unset($statuses[OrderStatus::DriverWaiting]);
        unset($statuses[OrderStatus::DriverPickedUp]);
        unset($statuses[OrderStatus::FoodEnRoute]);
        unset($statuses[OrderStatus::ArrivedAtCustomer]);
        unset($statuses[OrderStatus::Delivered]);

        $labels = static::getLabels();

        $labels[OrderStatus::FoodIsReady] = T::l('Ready for collection');
        $labels[OrderStatus::EstimatedDeliveryTime] = T::l('Estimated collection time');

        $statuses = array_intersect_key($labels, $statuses);

        return $statuses;
    }


    public static function getStatuses() {
        return [
            self::ProcessingPayment,
            self::PaymentReceived,
            self::TransferringToRestaurant,
            self::ReadyBy,
            self::OrderConfirmed,
            self::FoodPreparing,
            self::FoodIsReady,
            self::AssignedToDriver,
            self::RequestDriver,
            self::AcceptByDriver,
            self::EstimatedDeliveryTime,
            self::WayToPickUp,
            self::DriverAtRestaurant,
            self::DriverWaiting,
            self::DriverPickedUp,
            self::FoodEnRoute,
            self::ArrivedAtCustomer,
            self::Delivered ,
            self::OrderCancelled,
            self::Collected
        ];
    }

    public static function getAbbr($status){
        return self::$abbr[$status];
    }
}


