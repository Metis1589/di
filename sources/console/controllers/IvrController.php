<?php

namespace console\controllers;

use common\models\OrderContactHistory;
use Yii;
use common\components\language\T;
use \common\enums\DeliveryProvider;
use \common\enums\DeliveryType;
use \common\enums\OrderStatus;
use \common\enums\RecordType;
use \common\enums\RestaurantContactOrderType;
use \gateway\modules\v1\services\EmailService;
use \yii\db\ActiveQuery;

class IvrController extends \yii\console\Controller
{
    /**
     * IVR notification
     *
     * @return int
     */
    public function actionCall()
    {
        $twilio          = new \console\models\TwilioService;
        $orders          = new \common\models\Order;
        $orders          = $orders->getUnstagedOrders();
        $restPhone       = '';
        $succeededCallId = 0;
        $unitPrice       = 0;
        $url             = Yii::$app->params['ivrUrl'] . '?action=menu&press_button=default&';

        foreach ($orders as $order) {
            $skip_status = [
                OrderStatus::OrderCancelled, 'in-progress',
                OrderStatus::EstimatedDeliveryTime, OrderStatus::OrderConfirmed,
                OrderStatus::Delivered
            ];

            if (isset($order->lastOrderContactHistory) && in_array($order->lastOrderContactHistory->status, $skip_status)) {
                continue;
            }

            $serviceType = $order->restaurantDelivery->has_dinein ? DeliveryProvider::Client : DeliveryProvider::Restaurant;
            $tsTime      = strtotime($order->orderHistories[0]->create_on);

            if ($serviceType == DeliveryProvider::Client && $order->delivery_type == DeliveryType::DeliveryAsap && !sizeof($order->orderContactHistories)) {
            } else {
                // Get all IVR restaurant contacts for current order
                foreach ($order->restaurantContacts as $contact) {
                    // Time order changed to readyBy status + order delay
                    $delay = $tsTime + $contact->delay_in_min * 60;

                    // Add record to order contact log (history)
                    if ($delay < time()) {
                        $succeededCallId++;
                        $restPhone = $contact->number;
                        if (isset(Yii::$app->params['redirect_to_phone_number']) && !empty(Yii::$app->params['redirect_to_phone_number'])) {
                            $restPhone =  Yii::$app->params['redirect_to_phone_number'];
                        }
                        $unitPrice = ($contact->charge != '') ? $contact->charge : false;

                        \common\models\OrderContactHistory::addRecord(
                            $order->id,
                            \common\enums\RestaurantContactOrderType::Ivr,
                            'dialing-twilio',
                            $contact->name,
                            $restPhone,
                            null
                        );
                    }
                }
            }

            $internalOrder = [];
            $address       = isset($order->delivery_address_data) ? $order->delivery_address_data : (isset($order->billing_address_data) ? $order->billing_address_data : false);
            if ($succeededCallId && $address) {
                // Get order price / items data
                $internalOrder       = $this->_getIvrOrderData($order->id);
                $resttotal           = number_format($internalOrder['total'], 2);
                list($pound, $pence) = explode('.', $resttotal);

                if (!$order->ready_by && $serviceType == DeliveryProvider::Restaurant && $order->delivery_type == DeliveryType::DeliveryAsap) {
                    $readyBytime = $order->create_on;
                } elseif (!$order->ready_by && $order->delivery_type == DeliveryType::DeliveryLater) {
                    $readyBytime = $order->later_date_from;
                } else {
                    $readyBytime = $order->ready_by;
                }

                $itemsList = [];
                if (isset($internalOrder['orderItems'])) {
                    $subitem_node = '';
                    foreach ($internalOrder['orderItems'] as $subItem) {
                        $subitem_node .= T::l($subItem['menuItem']['name_key']) . ' . , ';
                    }
                    $itemsList[] = $subitem_node;
                }
                $itemsList = join(',', $itemsList);

                // Generate data for Twilio request
                $addressData = unserialize($address);
                $orderData   = [
                    'StatusCallback' => Yii::$app->params['ivrUrl'] . '?action=menu&press_button=default&',
                    'Timeout'        => Yii::$app->params['timeout'],
                    'callId'         => $succeededCallId,
                    'orderId'        => $order->id,
                    'serviceType'    => $serviceType,
                    'delivery_type'  => $order->delivery_type,
                    'firstName'      => $addressData['first_name'],
                    'lastName'       => $addressData['last_name'],
                    'address'        => "{$addressData['address1']}, {$addressData['building_number']}",
                    'postcode'       => $addressData['postcode'],
                    'city'           => $addressData['city'],
                    'email'          => $addressData['email'],
                    'phone'          => $addressData['phone'],
                    'laterDate'      => $order->later_date_from,
                    'readyByTime'    => $order->ready_by,
                    'user_id'        => $order->user_id,
                    'orderItems'     => $itemsList,
                    'itemCount'      => sizeof($internalOrder['orderItems']),
                    'addrequest'     => trim($order->member_comment),
                    'unit_price'     => $order->currency_code,
                    'pound'          => $pound,
                    'pence'          => $pence
                ];

                // Generate callback url
                foreach ($orderData as $key => $value) {
                    if (!is_array($value)) {
                        $url .= "$key=" . urlencode($value) . '&';
                    }
                }

                $twilio->makeIvrCall($restPhone, $orderData, $url);
            }
        }

        return 1;
    }

    /**
     * SMS notification
     *
     * @return int
     */
    public function actionSms()
    {
        $twilio = new \console\models\TwilioService;
        $orders = new \common\models\Order;
        $orders = $orders->getUnsentSms();

        foreach ($orders as $order) {
            // SMS contents
            $smsBody     = T::l('Your order #') . $order->id . T::l(' is cancelled');
            $address     = isset($order->delivery_address_data) ? $order->delivery_address_data : (isset($order->billing_address_data) ? $order->billing_address_data : '');
            $addressData = unserialize($address);
            $twilio->sendTextMessage($addressData, $smsBody, $order);
        }

        return 1;
    }

    /**
     * EMAIL notification
     *
     * @return int
     */
    public function actionEmail()
    {
        $customerGroups = join(',', [
            //'"' . OrderStatus::PaymentReceived . '"',
            '"' . OrderStatus::OrderConfirmed . '"',
            '"' . OrderStatus::TransferringToRestaurant . '"',
            '"' . OrderStatus::FoodEnRoute . '"',
            '"' . OrderStatus::ReadyBy . '"',
            '"' . OrderStatus::Delivered . '"',
            '"' . OrderStatus::OrderCancelled . '"'
        ]);

        $orderContacts = OrderContactHistory::find()
            ->where('is_succeeded=0')
            ->andWhere('order_contact_history.order_status IN (' . $customerGroups . ')')
            ->andWhere('order_contact_history.record_type <> "' . RecordType::Deleted . '"')
            ->andWhere('order_contact_history.type = "' . RestaurantContactOrderType::Email .'"')
            ->with(['order' => function(ActiveQuery $q) {
                $q->where('order.record_type <> "' . RecordType::Deleted . '"');
                $q->with(['user']);
            }])->all();


        // Customers
        foreach ($orderContacts as $orderContact) {

            $order = $orderContact->order;

            if($order){

                $order->status = $orderContact->order_status;

                $deliveryData = unserialize($order->billing_address_data);

                $client = isset($order->user) && isset($order->user->client) ? $order->user->client : \common\models\Client::findOne($order->restaurant->client_id);
                $user   = isset($order->user) ? $order->user : new \common\models\User;
                if (!isset($order->user)) {
                    $user->username = $deliveryData['email'];
                }

                switch ($order->status) {
                    case OrderStatus::PaymentReceived:
                        //EmailService::sendPaymentReceived($client, $user, $order, $client->url.'/order/tracker?order_number='.$order->order_number);
                        break;
                    case OrderStatus::OrderConfirmed:
                        EmailService::sendOrderConfirmed($client, $user, $order, $client->url.'/order/tracker?order_number='.$order->order_number);
                        break;
                    case OrderStatus::TransferringToRestaurant:
                        $orderData = $this->_getOrderData($order->id);
                        EmailService::sendTransferringToRestaurantNotification($client, $user, $order, $orderData);
                        break;
                    case OrderStatus::FoodEnRoute:
                        EmailService::sendFoodEnRouteNotification($client, $user, $order, $client->url.'/order/tracker?order_number='.$order->order_number);
                        break;
                    case OrderStatus::Delivered:
                        EmailService::sendDeliveredOrder($client, $user, $order, $client->url.'/order/tracker?order_number='.$order->order_number);
                        break;
                    case OrderStatus::OrderCancelled:
                        EmailService::sendCancellOrder($client, $user, $order);
                        EmailService::sendCancellOrderToRestaraunt($client, $order->restaurant, $order);
                        break;
                    case OrderStatus::ReadyBy:
                        $orderData = $this->_getReadyByOrderData($order->id);
                        EmailService::sendReadyBy($client, $order->restaurant, $order, $orderData);
                        break;
                }

                $history = \common\models\OrderContactHistory::find()->where('order_id=:order_id AND order_status=:status AND is_succeeded=0',[
                    ':order_id'  => $order['id'],
                    ':status'    => $order['status']
                ])->one();
                if (!$history) {
                    $history = new \common\models\OrderContactHistory;
                }
                $history->type         = \common\enums\RestaurantContactOrderType::Email;
                $history->number       = $deliveryData['phone'] ?: '-';
                $history->email        = $deliveryData['email'];
                $history->status       = 'email_sent';
                $history->order_status = $order->status;
                $history->name         = $deliveryData['name'] ?: $deliveryData['first_name'];
                $history->role         = isset($order->delivery_address_data) || isset($order->billing_address_data) ? \common\enums\UserType::Member : \common\enums\UserType::UNAUTHORIZED;
                $history->order_id     = $order->id;
                $history->is_succeeded = 1;
                $history->save();
            }

        }

        return 1;
    }

    /**
     * Get order summary (items, toppings)
     *
     * @param integer $order_id
     *
     * @return mixed | \console\controllers\Exception
     */
    private function _getIvrOrderData($order_id)
    {
        $order = false;
        try {
            $order = \common\models\Order::find()
                ->where(['id' => $order_id])
                ->with(
                    'user', 'restaurant.pickupAddress',
                    'restaurant.physicalAddress', 'restaurant.currency',
                    'orderHistories', 'orderItems.orderOptions.menuOption',
                    'orderItems.menuItem.menuCategory'
                )
                ->asArray()
                ->one();

            if (isset($order['delivery_address_data']) && !empty($order['delivery_address_data'])) {
                $order['delivery_address_data'] = unserialize(html_entity_decode($order['delivery_address_data']));
            }

            if (isset($order['billing_address_data']) && !empty($order['billing_address_data'])) {
                $order['billing_address_data'] = unserialize(html_entity_decode($order['billing_address_data']));
            }

            if (isset($order['voucher_data']) && !empty($order['voucher_data'])) {
                $order['voucher_data'] = unserialize(html_entity_decode($order['voucher_data']));
            }

            return $order;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /**
     * Get order summary (items, toppings)
     *
     * @param integer $order_id
     *
     * @return mixed | \console\controllers\Exception
     */
    private function _getOrderData($order_id)
    {
        $order = false;
        $return = '';
        try {
            $order = \common\models\Order::findOne($order_id);

            $return = '<table width="100%" border="0" cellpadding="2" cellspacing="2">
                <tbody><tr>
                    <td valign="top" align="left" width="5%">Quantity</td>
                    <td valign="top" align="left" width="10%">Name</td>
                    <td valign="top" align="left" width="5%">Size</td>
                    <td valign="top" align="left" width="5%">Item No.</td>
                    <td valign="top" align="left" width="25%">Category</td>
                    <td valign="top" align="left" width="15%">Description</td>
                    <td valign="top" align="right" width="15%">Unit Price</td>
                    <td valign="top" align="right" width="15%">Total</td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>';

            $cnt = 0;

            foreach($order->orderItems as $order_item){
                $cnt += $order_item->quantity;
                $return .= '<tr>
                        <td valign="top" align="left" style="color:#aca095;font:14px arial;padding:0 2px 0 5px">'.$order_item->quantity.'</td>
                        <td valign="top" align="left" style="color:#aca095;font:14px arial;padding:0 2px 0 5px">'.($order_item->menuItem ? $order_item->menuItem->name_key: '').'</td>
                        <td valign="top" align="left" style="color:#aca095;font:14px arial;padding:0 2px 0 5px"></td>
                        <td valign="top" align="left" style="color:#aca095;font:14px arial;padding:0 2px 0 5px">'.($order_item->menuItem ? $order_item->menuItem->id : '').'</td>
                        <td valign="top" align="left" style="color:#aca095;font:bold 14px arial;padding:5px 2px 0 0">'.($order_item->menuItem && $order_item->menuItem->menuCategory ? $order_item->menuItem->menuCategory->name_key : '').'</td>
                        <td valign="top" align="left" width="150" style="color:#aca095;font:bold 14px arial;padding:5px 2px 0 0">'.($order_item->menuItem ? $order_item->menuItem->description_key : '').'</td>
                        <td valign="top" align="right" style="color:#aca095;font:14px arial;padding:0 2px 0 5px">'.$order['currency_symbol'].$order_item->web_price.'</td>
                        <td valign="top" align="right" style="color:#aca095;font:14px arial;padding:0 2px 0 5px">'.$order['currency_symbol'].($order_item->quantity * $order_item->web_price).'</td>
                    </tr>';
            }

            $return .= '
                <tr>
                    <td valign="top" align="right" colspan="8"><hr></td>
                </tr>
                <tr>
                    <td valign="top" align="center">'.$cnt.'</td>
                    <td valign="top" align="right" colspan="7">'.$order['currency_symbol'].$order['subtotal'].'</td></tr>
                <tr>

                </tr><tr>
                    <td valign="top" align="right" colspan="8">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top" align="right" colspan="7">Delivery charge: </td>
                    <td valign="top" align="right">'.$order['currency_symbol'].($order['delivery_charge']).'</td>
                </tr>
                    <tr>
                    <td valign="top" align="right" colspan="7">Payment charge: </td>
                    <td valign="top" align="right">'.$order['currency_symbol'].round($order['payment_charge'],2).'</td>
                </tr>
                    <tr>
                    <td valign="top" align="right" colspan="7">
                        <strong>Grand total:</strong>
                    </td>
                    <td valign="top" align="right" colspan="8">
                        <strong>'.$order['currency_symbol'].$order['paid'].'</strong>
                    </td>
                </tr>
                    <tr>
                    <td valign="top" align="right" colspan="7">
                        VAT (Restaurant):
                    </td>
                    <td valign="top" align="right" colspan="8">
                        '.$order['currency_symbol'].round($order['subtotal']*$order['vat_value']/100,2).'
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" colspan="7">
                    VAT (<a href="http://dinein.co.uk" target="_blank">dinein.co.uk</a>):
                    </td><td valign="top" align="right" colspan="8">
                        '.$order['currency_symbol'].round($order['subtotal']*$order['vat_value']/100,2).'
                    </td>
                </tr>
            </tbody>
            </table>';

        } catch (Exception $ex) {
            return $ex;
        }

        return $return;
    }

    /**
     * Get order summary (items, toppings)
     *
     * @param integer $order_id
     *
     * @return mixed | \console\controllers\Exception
     */
    private function _getReadyByOrderData($order_id)
    {
        $order = false;
        $return = '';
        try {
            $order = \common\models\Order::findOne($order_id);

            $return = '<table width="100%" border="0" cellpadding="2" cellspacing="2">
                <tbody><tr>
                    <td valign="top" align="left" width="5%">Quantity</td>
                    <td valign="top" align="left" width="10%">Name</td>
                    <td valign="top" align="left" width="5%">Size</td>
                    <td valign="top" align="left" width="5%">Item No.</td>
                    <td valign="top" align="left" width="25%">Category</td>
                    <td valign="top" align="left" width="15%">Description</td>
                    <td valign="top" align="right" width="15%">Restaurant Price</td>
                    <td valign="top" align="right" width="15%">Restaurant Total</td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>';

            $cnt = 0;
            $total = 0;

            foreach($order->orderItems as $order_item){
                $cnt += $order_item->quantity;
                $total += ($order_item->quantity * $order_item->restaurant_price);
                $return .= '<tr>
                        <td valign="top" align="left">'.$order_item->quantity.'</td>
                        <td valign="top" align="left">'.($order_item->menuItem ? $order_item->menuItem->name_key: '').'</td>
                        <td valign="top" align="left"></td>
                        <td valign="top" align="left">'.($order_item->menuItem ? $order_item->menuItem->id : '').'</td>
                        <td valign="top" align="left">'.($order_item->menuItem && $order_item->menuItem->menuCategory ? $order_item->menuItem->menuCategory->name_key : '').'</td>
                        <td valign="top" align="left" width="150">'.($order_item->menuItem ? $order_item->menuItem->description_key : '').'</td>
                        <td valign="top" align="right">'.$order['currency_symbol'].$order_item->restaurant_price.'</td>
                        <td valign="top" align="right">'.$order['currency_symbol'].($order_item->quantity * $order_item->restaurant_price).'</td>
                    </tr>';
            }

            $return .= '
                <tr>
                    <td valign="top" align="right" colspan="8"><hr></td>
                </tr>
                <tr>
                    <td valign="top" align="center">'.$cnt.'</td>
                    <td valign="top" align="right" colspan="7">'.$order['currency_symbol'].$total.'</td>
                </tr>
                <tr>
                    <td valign="top" align="right" colspan="8">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top" align="right" colspan="7">
                        <strong>Grand total:</strong>
                    </td>
                    <td valign="top" align="right" colspan="8">
                        <strong>'.$order['currency_symbol'].$total.'</strong>
                    </td>
                </tr>
            </tbody>
            </table>';

        } catch (Exception $ex) {
            return $ex;
        }

        return $return;
    }

}