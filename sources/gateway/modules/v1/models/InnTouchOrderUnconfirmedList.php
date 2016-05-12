<?php

namespace gateway\modules\v1\models;


use common\enums\DeliveryType;
use common\enums\OrderStatus;

class InnTouchOrderUnconfirmedList {
    public $List;

    public static function create(Array $orders) {
        $intouchOrders = new InnTouchOrderUnconfirmedList();
        $intouchOrders->List = [];
        $intouchOrders->List['Orders'] = [];
        foreach($orders as $order) {
            $deliveryAddress = unserialize($order->delivery_address_data);
            $intouchOrders->List['Orders'][] = [
                'OrderId' => $order->id,
                'Status' => $order->status == OrderStatus::OrderConfirmed ? 'Confirmed' : ($order->status == OrderStatus::OrderCancelled ? 'Cancelled' : 'Unconfirmed'),
                'TimeScheduled' => $order->ready_by,
                'CustomerName' => $deliveryAddress['first_name'] . ' ' . $deliveryAddress['last_name'],
                'CustomerId' => $order->user_id,
                'LocationId' => $order->restaurant->getRestaurantCustomFieldValue('LocationId'),
                'Loc' => '#'.$order->restaurant->id,
                'Total' => $order->total,
                'IsLocked' => 'True', //TODO
                'EmailAddress' => $order->user->username,
                'TimeZone' => 0,
                'IsASAP' => ($order->delivery_type == DeliveryType::DeliveryAsap || $order->delivery_type == DeliveryType::CollectionAsap) ? 'True' : 'False',
                'XmlWrapperName' => 'Order'
            ];
        }
        return $intouchOrders;
    }
} 