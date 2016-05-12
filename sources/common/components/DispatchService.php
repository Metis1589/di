<?php

/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 5/15/2015
 * Time: 11:30 AM
 */

namespace common\components;

use common\enums\DeliveryType;
use common\models\Address;
use common\models\Order;
use common\models\OrderItem;
use common\models\Restaurant;
use Exception;
use Yii;

class DispatchService {

    /**
     * Submit order
     *
     * @param Order $order
     * @param Array $orderItems
     * @param Restaurant $restaurant
     * @return bool
     */
    public static function orderSubmit($order, $orderItems, $restaurant) {

        try {
            $delivery_address = new Address();
            $pickup_address = new Address();

            $delivery_address->setAttributes(unserialize($order->delivery_address_data));
            $pickup_address = Address::findOne(['id'=>$restaurant['pickupAddress']['id']]);

            $pickupPostcode = Yii::$app->locationService->getPostcode($pickup_address->postcode);

            $data = [
                'prepTime' => $order->food_preparation_time,
                'foreignDepotId' => $order->restaurant_id,
                'isLater' => $order->delivery_type == DeliveryType::CollectionLater || $order->delivery_type == DeliveryType::DeliveryLater,
                'orderId' => (int) $order->order_number,
                'totalPrice' => (float) $order->total,
                'items' => [],
                'itemCount' => count($orderItems),
//            'timeLower' => '2015-01-01 01:01:01',
//            'timeUpper' => '2015-01-01 01:15:01',
                'pickup' => [
                    'email' => $pickup_address->email,
                    'address' => $pickup_address->address1 . ' ' . $pickup_address->address2 . ' ' . $pickup_address->address3 . ' ' . $pickup_address->city . ' ' . $pickup_address->postcode . ' ' . $pickup_address->country->native_name,
                    'name' => $pickup_address->name,
                    'latlng' => [
                        'lng' => $pickupPostcode['longitude'],
                        'lat' => $pickupPostcode['latitude']
                    ],
                    'phone1' => $pickup_address->phone,
                ],
                'dropoff' => [
                    'instructions' => $delivery_address->instructions,
                    'email' => $delivery_address->email,
                    'address' => trim($delivery_address->address1 . ' ' . $delivery_address->address2 . ' ' . $delivery_address->address3 . ' ' . $delivery_address->city . ' ' . $delivery_address->postcode . ' ' . ($delivery_address->country_id ? $delivery_address->country->native_name : '')),
                    'name' => $delivery_address->first_name . $delivery_address->last_name,
                    'latlng' => [
                        'lng' => $delivery_address->longitude,
                        'lat' => $delivery_address->latitude
                    ],
                    'phone1' => $delivery_address->phone,
                ],
            ];

            /** @var OrderItem $item */
            foreach ($orderItems as $item) {
                $data['items'][] = [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                ];
            }

            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => json_encode($data),
                    'header' =>
                    "Content-Type: application/json\r\n" .
                    "Authorization: Bearer " . Yii::$app->params["dispatch_key"] . "\r\n"
                )
            );

            $context = stream_context_create($options);
            $result = file_get_contents(Yii::$app->params['dispatch_url'] . '/order/submit/', false, $context);
            $response = json_decode($result, true);

            return $response['success'];
        } catch (Exception $ex) {
            Yii::error($ex->__toString(),'application.dispatchService');
            return false;
        }
    }

    /**
     * update order
     *
     * @param Order $order
     * @param       $status
     * @return bool
     */
    public static function orderUpdate($order, $status = 'readyBy') {
        try {
            $data = [
                'updates' => [
                    [
                        'orderNumber' => (int)$order->order_number,
                        'status'      => $status,
                        'datetime'    => date('Y-m-d\TH:i:s'),
                    ]
                ]
            ];

            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => json_encode($data),
                    'header' =>
                    "Content-Type: application/json\r\n" .
                    "Authorization: Bearer " . Yii::$app->params['dispatch_key'] . "\r\n"
                )
            );

            $context = stream_context_create($options);
            $result = file_get_contents(Yii::$app->params['dispatch_url'] . '/order/update/', false, $context);
            $response = json_decode($result, true);
            return $response['status'];
        } catch (Exception $ex) {
            Yii::error($ex->__toString(),'application.dispatchService');
            return false;
        }
    }

    /**
     * Get delivery time for depot(restaurant)
     *
     * @param array $depotIds
     * @return bool|mixed
     */
    public static function depotsDeliveryTime(array $depotIds)
    {
        $queryPart = join( '&', array_map(
            function ($el) {
                    return 'ids=' . $el;
                },
                $depotIds
            )
        );
        try {
            $options = array(
                'http' => array(
                    'method' => 'GET',
                    'header' =>
                        "Authorization: Bearer " . Yii::$app->params['dispatch_key'] . "\r\n"
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents(
                Yii::$app->params['dispatch_url'] . '/depots/deliverytime?' . $queryPart,
                false,
                $context
            );
            $response = json_decode($result, true);
            return $response;
        } catch (Exception $ex) {
            Yii::error($ex->__toString(),'application.dispatchService');
            return false;
        }
    }

    /**
     * @param $restaurant
     * @return bool|array
     */
    public static function createDepot($restaurant)
    {
        try {
            $pickupAddress = $restaurant['pickupAddress'];

            $data = [
                'foreignDepotId' => $restaurant['id'],
                'name'           => $restaurant['name'],
                'address'        => $pickupAddress->address1 . ' ' . $pickupAddress->address2 . ' ' . $pickupAddress->address3 . ' ' . $pickupAddress->city . ' ' . $pickupAddress->postcode . ' ' . $pickupAddress->country->native_name,
                "postCode"       => $pickupAddress->postcode,
            ];
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => json_encode($data),
                    'header' =>
                        "Content-Type: application/json\r\n" .
                        "Authorization: Bearer " . Yii::$app->params['dispatch_key'] . "\r\n"
                )
            );

            $context = stream_context_create($options);
            $result = file_get_contents(Yii::$app->params['dispatch_url'] . '/depots', false, $context);
            $response = json_decode($result, true);
            return $response;
        } catch (Exception $ex) {
            Yii::error($ex->__toString(),'application.dispatchService');
            return false;
        }
    }

    public static function deleteDepot($id)
    {
        try {
            $options = array(
                'http' => array(
                    'method' => 'DELETE',
                    'header' =>
                        "Authorization: Bearer " . Yii::$app->params['dispatch_key'] . "\r\n"
                )
            );

            $context = stream_context_create($options);
            $result = file_get_contents(Yii::$app->params['dispatch_url'] . '/depots/'.(int)$id.'?foreignDepotId='.(int)$id, false, $context);
            $response = json_decode($result, true);
            return $response;
        } catch (Exception $ex) {
            Yii::error($ex->__toString(),'application.dispatchService');
            return false;
        }
    }
}
