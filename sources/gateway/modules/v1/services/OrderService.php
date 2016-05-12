<?php

namespace gateway\modules\v1\services;

use common\components\cache\GlobalCache;
use common\components\voucherServices\EagleEyeValidationService;
use common\enums\DeliveryProvider;
use common\enums\DeliveryType;
use common\enums\OrderStatus;
use common\enums\RecordType;
use common\enums\UserType;
use common\models\Address;
use common\models\MenuItem;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderOption;
use common\models\ReportOrder;
use common\models\User;
use common\models\Vat;
use common\models\VoucherUseHistory;
use ErrorException;
use Exception;
use common\components\DispatchService;
use gateway\models\SessionUser;
use gateway\modules\v1\components\OrderHelper;
use gateway\modules\v1\forms\common\CheckoutForm;
use Yii;
use yii\db\ActiveQuery;

class OrderService {

    /**
     * get statuses
     * @return array
     */
    private static function getStatuses() {
        $statuses = [
            OrderStatus::ProcessingPayment => [
                UserType::Member => [
                    OrderStatus::PaymentReceived,
                ],
                UserType::RestaurantApp => [
                    OrderStatus::PaymentReceived,
                    OrderStatus::ReadyBy
                ],
                UserType::CorporateMember => [
                    OrderStatus::PaymentReceived,
                ],
                UserType::UNAUTHORIZED => [
                    OrderStatus::PaymentReceived,
                ],
            ],

//            OrderStatus::ProcessingPayment => [
//                UserType::Member => [
//                    OrderStatus::PaymentReceived,
//                ],
//                UserType::CorporateMember => [
//                    OrderStatus::PaymentReceived,
//                ],
//                UserType::UNAUTHORIZED => [
//                    OrderStatus::PaymentReceived,
//                ],
//            ], //todo list all
        ];

        return $statuses;
    }

    /**
     * get available change statuses
     * @param $status
     * @param $role
     * @return null
     */
    private static function getAvailableStatuses($status, $role)
    {
        if ($role == UserType::Admin || $role == UserType::ClientAdmin) {
            return array_flip(OrderStatus::getStatuses());
        }

        $statuses = static::getStatuses();

        $roles = $statuses[$status];

        if (!array_key_exists($role, $roles)) {
            return null;
        }

        return $roles[$role];
    }

    /**
     * get allowed statuses for role
     * @param $role
     * @return array
     */
    public static function getAllowedStatuses($role) {
        //if ($role == UserType::Admin || $role == UserType::ClientAdmin) {
            return array_flip(OrderStatus::getStatuses());
        //}

//        $statuses = static::getStatuses();
//
//        $result = null;
//
//        foreach ($statuses as $allowedRoles) {
//            if (array_key_exists($role, $allowedRoles)) {
//                $result = $allowedRoles[$role];
//            }
//        }
//
//        return $result;
    }

    /**
     * @param User $user
     * @return array|null
     */
    public static function getAllowedRestaurants($user) {
        return [
            'client_id'           => $user->client_id,
            'restaurant_chain_id' => $user->restaurant_chain_id,
            'restaurant_group_id' => $user->restaurant_group_id,
            'restaurant_id'       => $user->restaurant_id,
        ];
    }

    /**
     * Is status change allowed
     * @param $currentStatus
     * @param $newStatus
     * @param $role
     * @return bool
     */
    private function isStatusChangeAllowed($currentStatus, $newStatus, $role) {
//        if ($role == UserType::Admin) {
//            return true;
//        }
//
//        $availableStatuses = static::getAvailableStatuses($currentStatus, $role);
//
//        if (empty($availableStatuses)) {
//            return false;
//        }
//
//        return array_key_exists($newStatus, $availableStatuses);

        return true;
    }

    /**
     * Create order
     * @param SessionUser $session_user
     * @param CheckoutForm $requestForm
     * @return Order|null
     * @throws \yii\db\Exception
     */
    public static function createOrder($session_user, $requestForm) {

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $restaurant = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $session_user->restaurant_id);

            // create order
            $order = isset($session_user->order) ? $session_user->order : new Order();
            $order->restaurant_id          = $session_user->restaurant_id;
            $order->restaurant_name        = $restaurant['name'];
            $order->delivery_provider      = $restaurant['restaurantDelivery']['has_own'] ? DeliveryProvider::Restaurant : DeliveryProvider::Client;
            $order->sales_fee_value        = $restaurant['restaurantPayments'][0]['sales_fee_value'];
            $order->sales_fee_type         = $restaurant['restaurantPayments'][0]['sales_fee_type'];
            $order->sales_charge_type      = $restaurant['restaurantPayments'][0]['sales_charge_type'];
            $order->collection_fee_value   = $restaurant['restaurantPayments'][0]['collection_fee_value'];
            $order->collection_fee_type    = $restaurant['restaurantPayments'][0]['collection_fee_type'];
            $order->collection_charge_type = $restaurant['restaurantPayments'][0]['collection_charge_type'];

            /** @var Vat $vat */
            $vat = Vat::find()->where(['is_default' => true])->one();

            $order->vat_value = $vat->value;

            if (!Yii::$app->user->isGuest) {
                $order->user_id = Yii::$app->user->identity->id;
            }

            $order->postcode        = $session_user->postcode;
            $order->delivery_type   = $session_user->delivery_type;
            $order->later_date_from = $session_user->later_date_from;
            $order->later_date_to   = $session_user->later_date_to;
            $order->estimated_time  = $session_user->later_date_to;
            $order->member_comment  = $requestForm->additional_requirements;

            if (in_array($order->delivery_type, [DeliveryType::DeliveryAsap, DeliveryType::DeliveryLater])) {
                $deliveryAddress = new Address();

                $deliveryAddress->title         = $requestForm->delivery_address['title'];
                $deliveryAddress->first_name    = $requestForm->delivery_address['first_name'];
                $deliveryAddress->last_name     = $requestForm->delivery_address['last_name'];
                $deliveryAddress->address1      = $requestForm->delivery_address['address1'];
                $deliveryAddress->address2      = array_key_exists('address2', $requestForm->delivery_address) ? $requestForm->delivery_address['address2'] : null;
                $deliveryAddress->city          = $requestForm->delivery_address['city'];
                $deliveryAddress->postcode      = $requestForm->delivery_address['postcode'];
                $deliveryAddress->phone         = $requestForm->delivery_address['phone'];
                $deliveryAddress->email         = $requestForm->delivery_address['email'];
                $deliveryAddress->instructions  = !empty($requestForm->delivery_address['instructions']) ? $requestForm->delivery_address['instructions'] : null;
                

                $postcode = Yii::$app->locationService->getPostcode($deliveryAddress->postcode);

                $deliveryAddress->latitude  = $postcode['latitude'];
                $deliveryAddress->longitude = $postcode['longitude'];

                $order->delivery_address_data = serialize($deliveryAddress->attributes);
            }

            if ($requestForm->billing_address) {
                $billingAddress = new Address();

                $billingAddress->title      = $requestForm->billing_address['title'];
                $billingAddress->first_name = $requestForm->billing_address['first_name'];
                $billingAddress->last_name  = $requestForm->billing_address['last_name'];
                $billingAddress->address1   = $requestForm->billing_address['address1'];
                $billingAddress->address2   = array_key_exists('address2', $requestForm->billing_address) ? $requestForm->billing_address['address2'] : null;
                $billingAddress->city       = $requestForm->billing_address['city'];
                $billingAddress->postcode   = $requestForm->billing_address['postcode'];
                $billingAddress->phone      = $requestForm->billing_address['phone'];
                $billingAddress->email      = $requestForm->billing_address['email'];

                $order->billing_address_data = serialize($billingAddress->attributes);
            }
            else {
                $order->billing_address_data = $order->delivery_address_data;
            }

            $order->is_utensils          = $requestForm->include_utensils === 'true' ? 1 : 0;
            $order->status               = OrderStatus::ProcessingPayment;
            $order->is_amend             = true;
            $order->is_term_cond         = true;
            $order->is_term_cond_acc_pol = true;
            $order->is_subscribe_own     = true;
            $order->is_subscribe_other   = true;

            if ($session_user->voucher) {
                $order->voucher_data             = serialize($session_user->voucher);
                $voucher_use_history             = new VoucherUseHistory();
                $voucher_use_history->user_id    = $order->user_id;
                $voucher_use_history->voucher_id = $session_user->voucher['id'];
                if (!$voucher_use_history->save()) {
                    throw new Exception('Error saving voucher history');
                }
                // @todo add check for service name
                if($session_user->voucher['validation_service']){
                    $order->voucher_code = $session_user->voucher_code;
                }
            }

            $order->currency_code   = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $session_user->restaurant_id)['currency']['code'];
            $order->currency_symbol = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $session_user->restaurant_id)['currency']['symbol'];

            $restaurant             = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $session_user->restaurant_id);
            $order->delivery_charge = Yii::$app->restaurantService->getDeliveryCharge($restaurant, $session_user->postcode);
            $order->driver_charge   = $session_user->driver_charge;

            $order->subtotal             = $session_user->getSubtotal();
            $order->discount_items       = $session_user->discount_items;
            $order->discount_delivery_charge       = $session_user->discount_delivery_charge;
            $order->total                = $session_user->getTotal();
            $order->corp_total_allocated = $session_user->getTotalAllocated();
            $order->paid                 = $order->total - (isset($order->corp_total_allocated) ? $order->corp_total_allocated : 0);
            if ($order->paid < 0) {
                throw new Exception('The paid amount can\'t be negative');
            }

            $order->restaurant_subtotal       = $session_user->getRestaurantSubtotal();
            $order->restaurant_discount_value = $session_user->getRestaurantDiscountValue();
            $order->restaurant_total          = $session_user->getRestaurantTotal();
            $deliveryTime = DispatchService::depotsDeliveryTime([$order->restaurant_id]);

            //company info
            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->user_type == UserType::CorporateMember) {
                $company                  = Yii::$app->globalCache->getCompany(Yii::$app->user->identity->companyUserGroup->company_id);
                $order->corp_company_data = serialize($company);

                if (isset($session_user->expense_type)) {
                    $order->is_corporate = true;
                    $order->corp_expense_type_data = serialize($session_user->expense_type);
                    $order->corp_total_allocated = $session_user->getTotalAllocated();
                } else if (Yii::$app->user->identity->client->has_inntouch) {
                    $order->is_corporate = true;
                }
            }

            $order->estimated_time = $deliveryTime
            && !empty($deliveryTime[$order->restaurant_id])
            && !empty($deliveryTime[$order->restaurant_id]['dt'])
                ? $deliveryTime[$order->restaurant_id]['dt']
                : null;
            if (empty($order->later_date_from) || $order->later_date_from == ' ') {
                $order->later_date_from = null;
            }
            if (empty($order->later_date_to) || $order->later_date_to == ' ') {
                $order->later_date_to = null;
            }
            if (!$order->save()) {
                throw new Exception('Error saving order');
            }

            $order = Order::findOne(['id' => $order->id]);

            self::createContactHistoryRecord($order,$order->status);

            //save corp users
            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->user_type == UserType::CorporateMember) {
                foreach($session_user->corp_users as $corpUser) {
                    $corpUser->order_id = $order->id;
                    if (Yii::$app->user->identity->client->has_inntouch) {
                        $corpUser->allocation = $order->total;
                    }
                    if (!$corpUser->save()) {
                        throw new Exception('Error saving corp order user');
                    }
                }
            }

            //remove all previous order items from the order
            $orderItems = OrderItem::find()->where(['order_id' => $order->id, 'record_type' => RecordType::Active])->all();
            foreach ($orderItems as $orderItem) {
                $orderItem->record_type = RecordType::Deleted;
                if (!$orderItem->save()) {
                    throw new Exception('Error removing order item');
                }
            }

            // create new order items
            $savedOrderItems = [];
            /** @var OrderItem $order_item */
            foreach ($session_user->order_items as $order_item) {

                $order_item->isNewRecord = true;
                $order_item->id          = null;
                $order_item->order_id    = $order->id;

                if (!$order_item->save()) {
                    throw new Exception('Error saving order item');
                }

                /** @var OrderOption $order_option */
                foreach ($order_item->options as $order_option) {
                    $order_option->order_item_id = $order_item->id;
                    if (!$order_option->save()) {
                        throw new Exception('Error saving order option');
                    }
                }
                $savedOrderItems[$order_item->id ] = $order_item;
            }

            $transaction->commit();

            $session_user->order_items = $savedOrderItems;


        } catch (Exception $e) {
            $transaction->rollBack();
            return null;
        }

        // send order to DISPATCH

        $restaurant = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $session_user->restaurant_id);

        DispatchService::orderSubmit($order, $session_user->order_items, $restaurant);

        return $order;
    }

    /**
     * change order status
     * @param Order $order
     * @param $status
     * @param $user_type
     * @param null $user_id
     * @param null $ready_by_time
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public static function changeOrderStatus($order, $status, $user_type, $user_id = null, $ready_by_time = null) {

        if ($order->status == $status) {
            return;
        }

        if (!static::isStatusChangeAllowed($order->status, $status, $user_type));

        $order->status = $status;
        if (isset($ready_by_time)) {
            $order->estimated_time = date('H:i:s', strtotime($ready_by_time));
            Yii::info('$order->estimated_time: ' . $order->estimated_time);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            switch ($status) {
    //            case OrderStatus::ProcessingPayment:
    //
    //                break;
                case OrderStatus::PaymentReceived:

                    break;
                case OrderStatus::TransferringToRestaurant:
                    break;
                case OrderStatus::ReadyBy:
                    break;
                case OrderStatus::OrderConfirmed:
                    break;
                case OrderStatus::FoodPreparing:
                    break;
                case OrderStatus::FoodIsReady:
                    break;
                case OrderStatus::OrderCancelled:
                    self::unlockThirdPartyVoucher($order);
                    $refund_value = (float)$order->total - (float)$order->client_refund - (float)$order->restaurant_refund;
                    $client = Yii::$app->globalCache->getClientById($order->restaurant->client_id);
                    if (!OrderService::processRefund($refund_value, $order, $client)){
                        throw new ErrorException('Error processing refund');
                    }
                    $order->client_refund = $refund_value - (float)$order->client_refund;
                    break;
                case OrderStatus::Collected:
                case OrderStatus::Delivered:

                    if (!$order->loyalty_points && $order->user_id) {
                        $order->loyalty_points = LoyaltyService::calculateOrderLoyaltyPoints($order);

                        /** @var User $user */
                        $user = &$order->user;
                        $user->loyalty_points = ($user->loyalty_points ? $user->loyalty_points : 0) + $order->loyalty_points;

                        if (!$user->save()) {
                            $transaction->rollBack();
                            throw new Exception('Error saving user');
                        }
                    }

                    self::redeemThirdPartyVoucher($order);
                    ReportOrder::copyFromOrder($order);
                    break;
            }


            if (!$order->save()) {
                $transaction->rollBack();
                throw new Exception('Error saving order');
            }

            $orderHistory = new \common\models\OrderHistory();
            $orderHistory->order_id = $order->id;
            $orderHistory->status = $status;
            if (!empty($user_id)){
                $orderHistory->user_id = $user_id;
            }

            if (!$orderHistory->save()) {
                $transaction->rollBack();
                throw new Exception('Error saving order history');
            }

            if (!self::createContactHistoryRecord($order,$status)) {
                $transaction->rollBack();
                throw new Exception('Error saving order contact history');
            }

            $transaction->commit();
        }
        catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        DispatchService::orderUpdate($order, $status);
    }

    public static function processRefund($refund_value, $order, $client) {
        if (OrderService::isRefundAllowed($refund_value, $order)) {
            $result = \gateway\components\PaymentHelper::Refund($order->psp_reference, $order->currency_code, $refund_value, $client);

            return $result;
        }

        return false;
    }

    public static function isRefundAllowed($refund_value, $order){
        if ($refund_value > (float)$order->total){
            return false;
        }
        return true;
    }

    public static function getRestaurantAppResponse($orders, $user){


        $restaurant = Yii::$app->globalCache->getRestaurant($user->client_id, $user->restaurant_id);
        $filtered_restaurant = [];

        $filtered_restaurant['id'] =  $restaurant['id'];
        $filtered_restaurant['name'] =  $restaurant['name'];
        $filtered_restaurant['logo'] =  $restaurant['logo_file_name'];
        $filtered_restaurant['description'] =  $restaurant['description'];
        $filtered_restaurant['slug'] =  $restaurant['slug'];
        $filtered_restaurant['default_preparation_time'] =  $restaurant['default_cook_time'];
        $filtered_restaurant['create_on'] =  $restaurant['create_on'];

        $filtered_orders = [];

        foreach ($orders as $order) {
            if (\common\components\identity\RbacHelper::isRestaurantAllowed($user, $order['restaurant_id'])) {
                $filtered_order = [];

                $filtered_order['id'] = $order['id'];
                $filtered_order['order_number'] = $order['order_number'];
                $filtered_order['delivery_type'] = $order['delivery_type'];
                $filtered_order['is_corporate'] = $order['is_corporate'];
                $filtered_order['currency_code'] = $order['currency_code'];
                $filtered_order['subtotal'] = $order['subtotal'];
                $filtered_order['total'] = $order['total'];
                $filtered_order['create_on'] = $order['create_on'];
                $filtered_order['last_update'] = $order['last_update'];
                $filtered_order['current_status'] = $order['status'];
                $filtered_order['order_items'] = [];
                $filtered_order['order_history'] = [];

                if (isset($order['orderItems']) && !empty($order['orderItems'])) {
                    foreach ($order['orderItems'] as $order_item) {
                        $filtered_order_item = [];
                        $filtered_order_item['id'] = $order_item['id'];
                        $filtered_order_item['price'] = $order_item['web_price'];
                        $filtered_order_item['quantity'] = $order_item['quantity'];
                        $filtered_order_item['discount'] = $order_item['discount'];
                        $filtered_order_item['creation_time'] = $order_item['create_on'];
                        $filtered_order_item['special_instructions'] = $order_item['special_instructions'];
                        $filtered_order_item['display_index'] = $order_item['display_index'];
                        $filtered_order_item['order_options'] = $order_item['orderOptions'];
                        array_push($filtered_order['order_items'], $filtered_order_item);
                    }
                }

                if (isset($order['orderHistories']) && !empty($order['orderHistories'])) {
                    foreach ($order['orderHistories'] as $history) {
                        $filtered_history = [];
                        $filtered_history['id'] = $history['id'];
                        $filtered_history['status'] = $history['status'];
                        $filtered_history['last_update'] = $history['last_update'];
                        array_push($filtered_order['order_history'], $filtered_history);
                    }
                }

                array_push($filtered_orders, $filtered_order);
            }
        }

        return [
            'restaurant' => $filtered_restaurant,
            'orders' => $filtered_orders
        ];
    }

    /**
     * get allowed orders
     * @param null $client_id
     * @param null $order_id
     * @param null $custom_fields
     * @return array
     */
    public static function getOrders($client_id = null, $order_id = null, $custom_fields = null, $filter_statuses = null) {

        $allowed_statuses = static::getAllowedStatuses(Yii::$app->user->isGuest ? UserType::UNAUTHORIZED : Yii::$app->user->identity->user_type);

        $allowed_restaurants = static::getAllowedRestaurants(Yii::$app->user->identity);

        if ($allowed_restaurants == null) {
            return [];
        }

        if ($allowed_restaurants['client_id'] == null) {
            $allowed_restaurants['client_id'] = $client_id; // override client_id for admin impersonated a client
        }

        /** @var ActiveQuery $query */
        $query = Order::find()
            ->joinWith(
                [
                 'restaurant.pickupAddress',  
                 'restaurant.restaurantGroup.restaurantChain', 
                 'orderHistories.user' => function (ActiveQuery $q) {
                    $q->select(['user.id', 'user.username']);
                  }, 
                 'orderItems.orderOptions', 'orderItems.menuItem.menuCategory'
                ]);
            
        if ($allowed_restaurants['client_id']) {
            $query->andWhere(['restaurant.client_id' => $allowed_restaurants['client_id']]);
        }
        else if ($allowed_restaurants['restaurant_id']) {
            $query->andWhere(['restaurant.restaurant_id' => $allowed_restaurants['restaurant_id']]);
        }
        else if ($allowed_restaurants['restaurant_group_id']) {
            $query->andWhere(['restaurant.restaurant_group_id' => $allowed_restaurants['restaurant_group_id']]);
        }
        else if ($allowed_restaurants['restaurant_chain_id']) {
            $query->andWhere(['restaurant_group.restaurant_chain_id' => $allowed_restaurants['restaurant_chain_id']]);
        }

        $orders = [];
        if (!empty($order_id)) {
            $query->andWhere(['order.id' => (int)$order_id]);
            $orders = $query->orderBy('order.id DESC')->asArray()->all();
        } else {
            $query->andWhere(['!=', 'order.status', OrderStatus::ProcessingPayment]);
            $orders = $query->orderBy('order.id DESC')->asArray()->all();
        }

        $result = [];

        $filter_statuses_array = [];
        //filter orders by status
        if ($filter_statuses != null) {
            $filter_statuses_array = explode(',', $filter_statuses);
        }

        foreach ($orders as &$order) {
            if (array_search($order['status'], $allowed_statuses) != null) {

                if (count($filter_statuses_array) > 0 && !in_array($order['status'], $filter_statuses_array)){
                    continue;
                }

                // filter order items by custom fields
                if ($custom_fields != null) {
                    $custom_fields_array = explode(',', $custom_fields);

                    $filtered_order_items = [];

                    foreach ($order['orderItems'] as $order_item) {
                        $menu_item = Yii::$app->globalCache->getMenuItem($order['restaurant']['id'], $order_item['menu_item_id']);

                        foreach ($custom_fields_array as $field_key) {
                            if ($order_item['display_index'] !== null && strpos($field_key, ':' . $order_item['display_index'] . ':') !== false) {
                                $filtered_order_items[] = $order_item;
                            }
                            else if (!empty($field_key) && array_key_exists($field_key, $menu_item['customFields']) && $menu_item['customFields'][$field_key] == '1') {
                                $filtered_order_items[] = $order_item;
                            }
                        }
                    }

                    $order['orderItems'] = $filtered_order_items;
                }

                $order['discount_total'] = (float)$order['discount_items'] + (float)$order['discount_delivery_charge'];
                
                $result[] = $order;
            }
        }

        return $result;
    }

    public static function validateOrder($client_key) {

        try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            $restaurant = Yii::$app->globalCache->getRestaurant($client_key, $session_user->restaurant_id);

            // 1. is restaurant in range
            if (!Yii::$app->restaurantService->isRestaurantAvailableForPostcode($restaurant, $session_user->postcode)) {
                throw new ErrorException('Restaurant not available for postcode ' . $session_user->postcode);
            }

            // 1.1 is not in black list
            $postcode     = Yii::$app->locationService->getPostcode($session_user->postcode);

            if (!$postcode) {
                throw new Exception('Postcode not found');
            }

            if (Yii::$app->locationService->isInBlacklist($client_key, $session_user->postcode)) {
                throw new ErrorException('We are sorry, we do not deliver to your location');
            }

            // 2. is restaurant is opened
            if (!Yii::$app->restaurantService->isRestaurantAvailableForTime($restaurant, $session_user->delivery_type, date('Y-m-d', strtotime($session_user->later_date_from)), date('H:i', strtotime($session_user->later_date_from)) . '-' . date('H:i', strtotime($session_user->later_date_to)))) {
                throw new ErrorException('Restaurant not available for specified time');
            }

            // 3. order has items, which are active
            if (count($session_user->order_items) == 0) {
                throw new ErrorException('No items in order');
            }

            // 4. min/max quantities/amounts

            $order = OrderHelper::getOrderResponse($client_key, false);

            if ($order['max_order_value'] && $order['max_order_value'] < $session_user->getTotal()) {
                throw new ErrorException('Max order value is ' . $order['currency_symbol'] . $order['max_order_value']);
            }
            if ($order['min_order_value'] && $order['min_order_value'] > $session_user->getTotal()) {
                throw new ErrorException('Min order value is ' . $order['currency_symbol'] . $order['min_order_value']);
            }

            if ($order['max_order_amount'] && $order['max_order_amount'] < $session_user->getAllQuantities()) {
                throw new ErrorException('Max items quantity is ' . $order['max_order_amount']);
            }
            if ($order['min_order_amount'] && $order['min_order_amount'] > $session_user->getAllQuantities()) {
                throw new ErrorException('Min items quantity is ' . $order['min_order_amount']);
            }

            // 5. validate vouchers
            if ($order['voucher_code']) {
                $voucher_error = null;
                if (!VoucherCalculator::calculateDiscountBySessionUser($session_user, $voucher_error)) {
                    throw new ErrorException($voucher_error);
                }
            }

            // 7. validate order rules
            $rules = Yii::$app->globalCache->getOrderRules($client_key);

            foreach ($rules as $rule) {
                if (($rule['delivery_type'] == 'Collection' && ($session_user->delivery_type == DeliveryType::CollectionAsap || $session_user->delivery_type == DeliveryType::CollectionLater)) ||
                    ($rule['delivery_type'] == 'Delivery' && ($session_user->delivery_type == DeliveryType::DeliveryAsap || $session_user->delivery_type == DeliveryType::DeliveryLater)))
                {
                    static::validateOrderRule($rule, $order);
                }
            }

            return true;
        }
        catch (ErrorException $ex) {
            return $ex;
        }
    }

    /**
     *
     * Creates contact history record for the particular order with the particular status.
     * @param \common\models\Order $order order model
     * @param string $orderStatus status for order for which notification record will be created
     * @return boolean flag indicates whether record was created or not
     */
    private static function createContactHistoryRecord($order,$orderStatus){
        $history = \common\models\OrderContactHistory::find()->where('order_id=:order_id',[':order_id'=>$order['id']])->one();
        if(!$history){
            $history = new \common\models\OrderContactHistory;
            $billing_data = $order->billing_address_data ? unserialize($order->billing_address_data) : null;
            if($billing_data){
                $history->name = $billing_data['first_name'].' '.$billing_data['last_name'];
                $history->role = 'is not set';
                $history->type = \common\enums\RestaurantContactOrderType::Email;
            }
            else{
                $history->name = 'is not set';
                $history->role = 'is not set';
                $history->type = \common\enums\RestaurantContactOrderType::Email;
            }
            $history->status = 'is not sent';
        }
        $history->setIsNewRecord(true);
        $history->id = null;
        $history->order_id     = $order['id'];
        $history->order_status = $orderStatus;
        $history->is_succeeded = 0;
        return $history->save();
    }


    public function validateOrderRule($rule, $order) {
        /** @var SessionUser $session_user */
        $session_user = Yii::$app->userCache->getUser();

        $result = 0;

        foreach ($order['items'] as $item) {
            $menuItem = Yii::$app->globalCache->getMenuItem($session_user->restaurant_id, $item['menu_item_id']);

            $field_key = $rule['customField']['key'];

            if (array_key_exists($field_key, $menuItem['customFields'])) {
                if ($menuItem['customFields'][$field_key] == $rule['value']) {
                    $result++;
                }
            }
        }

        if ($result == count($order['items'])) {
            throw new \yii\base\ErrorException($rule['message_key']); // todo translate
        }
    }

    protected static function redeemThirdPartyVoucher($order)
    {
        if($order['voucher_code'] && !is_null($order['voucher_data'])){
            if(is_string($order['voucher_data'])){
                $order['voucher_data'] = unserialize(html_entity_decode($order['voucher_data']));
            }
            $voucher = $order['voucher_data'];
            // @todo change to compare
            if($voucher['validation_service']){
                $client = $order['restaurant']['client'];
                EagleEyeValidationService::redeem($client,$order['voucher_code']);
            }
        }
    }

    protected static function unlockThirdPartyVoucher($order)
    {
        if($order['voucher_code'] && !is_null($order['voucher_data'])){
            if(is_string($order['voucher_data'])){
                $order['voucher_data'] = unserialize(html_entity_decode($order['voucher_data']));
            }
            $voucher = $order['voucher_data'];
            // @todo change to compare
            if($voucher['validation_service']){
                $client = $order['restaurant']['client'];
                EagleEyeValidationService::unlock($client,$order['voucher_code']);
            }
        }
    }
}