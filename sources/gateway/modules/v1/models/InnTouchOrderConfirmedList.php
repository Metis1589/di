<?php

namespace gateway\modules\v1\models;


use common\enums\DeliveryType;
use common\enums\OrderStatus;
use common\models\Order;

class InnTouchOrderConfirmedList {
    public $Items;

    public static function create(Array $orders) {
        $intouchOrders = new InnTouchOrderConfirmedList();
        $intouchOrders->Items = [];
        foreach($orders as $order) {
            $deliveryAddress = unserialize($order->delivery_address_data);
            $intouchOrders->Items[] = [
                'OrderId' => $order->id,
                'LocationId' => $order->restaurant->getRestaurantCustomFieldValue('LocationId'),
                'Loc' => '#'.$order->restaurant->id,
                'CustomerId' => $order->user_id,
                'TimeScheduled' => $order->ready_by,
                'TimeZone' => '0',
                'Total' => $order->total,
                'CustomerName' => $deliveryAddress['first_name'] . ' ' . $deliveryAddress['last_name'],
                'DeliveryZone' => '',
                'OrderStatusId' => '', //TODO
                'Status' => $order->status == OrderStatus::OrderConfirmed ? 'Confirmed' : ($order->status == OrderStatus::OrderCancelled ? 'Cancelled' : 'Unconfirmed'),
                'IsASAP' => ($order->delivery_type == DeliveryType::DeliveryAsap || $order->delivery_type == DeliveryType::CollectionAsap) ? 'True' : 'False',
            ];
        }
        return $intouchOrders;
    }
} 