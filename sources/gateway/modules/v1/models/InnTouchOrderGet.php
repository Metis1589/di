<?php

namespace gateway\modules\v1\models;


use common\enums\DeliveryType;
use common\enums\OrderStatus;
use common\models\Order;
use common\models\RestaurantDeliveryCharge;
use Yii;

class InnTouchOrderGet {
    public $Order;

    public static function create(Order $order) {
        $intouchOrder = new InnTouchOrderGet();
        $deliveryAddress = isset($order->delivery_address_data) ? unserialize($order->delivery_address_data) : null;
        $expenseTypeName = '';
        if ($order->is_corporate) {
            $expenseType = unserialize($order->corp_expense_type_data);
            $expenseTypeName = $expenseType['name'];
        }
        $intouchOrder->Order = [
            'Header' => [
                'OrderId' => $order->id,
                'Status' => $order->status == OrderStatus::PaymentReceived ? 'Confirmed' : ($order->status == OrderStatus::OrderCancelled ? 'Cancelled' : 'Unconfirmed'),
                'IsASAP' => ($order->delivery_type == DeliveryType::DeliveryAsap || $order->delivery_type == DeliveryType::CollectionAsap) ? 'True' : 'False',
                'Currency' => $order->restaurant->currency->code,
                'Culture' => 'en-GB',
                'LeadTime' => 30, //TODO
                'Instructions' => $order->member_comment,
                'Revision' => 1,
                'TaxRate' => $order->vat_value,
                'Method' => ($order->delivery_type == DeliveryType::DeliveryAsap || $order->delivery_type == DeliveryType::DeliveryLater) ? 'Delivery' : 'Collection',
            ],
            'Location' => [
                'LocationId' => $order->restaurant->getRestaurantCustomFieldValue('LocationId'),
                'Name' => $order->restaurant->name,
                'Phone' => $order->restaurant->pickupAddress->phone,
                'Email' => $order->restaurant->pickupAddress->email,
                'Abbr' => '#'.$order->restaurant->id,
                'Chain' => isset($order->restaurant->restaurantChain) ? $order->restaurant->restaurantChain->name_key : '',
            ],
            'Customer' => [
                'CustomerId' => $order->user->id,
                'FirstName' => $order->user->first_name,
                'LastName' => $order->user->last_name,
                'Email' => $order->user->username,
            ],
            'Contact' => [
                'Name' => isset($deliveryAddress) ? $deliveryAddress['first_name'] . ' ' . $deliveryAddress['last_name'] : $order->user->first_name  . ' ' . $order->user->last_name,
                'Email' => isset($deliveryAddress) ? $deliveryAddress['email'] : $order->user->username,
                'Phone' => isset($deliveryAddress) ? $deliveryAddress['phone'] : '',
            ],
            'Times' => [
                'Ordered' => $order->create_on,
                'Scheduled' => $order->ready_by,
                'TimeZone' => '0'
            ],
            'Totals' => [
                'SubTotal' => $order->subtotal,
                'Delivery' => $order->delivery_charge,
                'Tax' => $order->total / ((1 + $order->vat_value/100) / ($order->vat_value/100)),
                'Adjustments' => '0.00', //TODO
                'Tip' => $order->driver_charge,
                'Discount' => '0.00',
                'Total' => $order->total,
                'Balance' => '0.00',
            ],
            'Payments' => [
                'Payment' => [
                    'Index' => 1,
                    'Amount' => $order->paid,
                    'PaymentCode' => $expenseTypeName,
                    'PayBalance' => $order->total == $order->paid ? 'True' : 'False',
                    'VoucherCode' => $order->voucher_code
                ]
            ],
            'attributes' => [
                'Format' => 'XML',
                'Version' => '1.1',
                'TimeStamp' => $order->create_on
            ]
        ];
        $intouchOrder->Order['Items'] = [];
        $index = 1;
        foreach($order->orderItems as $orderItem) {
            $menuItem = $orderItem->menuItem;
            $intouchOrder->Order['Items'][$index-1] = [
                'Index' => $index,
                'Item' => $menuItem->name_key,
                'Size' => '', //TODO
                'MenuItemId' => $menuItem->id,
                'Qty' => $orderItem->quantity,
                'Price' => $orderItem->getWebTotal(true, true),
                'BasePrice' => $orderItem->getWebTotal(false, true),
                'OptionsPrice' => $orderItem->getWebTotal(true, true) - $orderItem->getWebTotal(false, true),
                'PrepCode' => 0,
                'IsDefaultSize' => 'True', //TODO
                'PosCode' => $orderItem->getOrderItemCustomFieldValue('PosCode')
            ];
            $index++;
        }

        $deliveryChargePosCode = '';
        if ($order->delivery_type == DeliveryType::DeliveryAsap || $order->delivery_type == DeliveryType::DeliveryLater) {
            $restaurant = Yii::$app->globalCache->getRestaurant(Yii::$app->user->identity->client->key, $order->restaurant_id);
            $deliveryCharge = Yii::$app->restaurantService->getDeliveryChargeModel($restaurant, $deliveryAddress['postcode']);
            if (is_array($deliveryCharge)) {
                $deliveryChargeModel = RestaurantDeliveryCharge::findOne($deliveryCharge['id']);
                $deliveryChargePosCode = $deliveryChargeModel->getRestaurantDeliveryChargeCustomFieldValue('PosCode');
            }
        }


        $intouchOrder->Order['Items'][$index-1] = [
            'Index' => $index,
            'Item' => 'Delivery Charge',
            'Size' => '',
            'MenuItemId' => '',
            'Qty' => 1,
            'Price' => $order->delivery_charge,
            'BasePrice' => $order->delivery_charge,
            'OptionsPrice' => 0,
            'PrepCode' => 0,
            'IsDefaultSize' => '',
            'PosCode' => $deliveryChargePosCode
        ];
        return $intouchOrder;
    }
} 