<?php

namespace gateway\modules\v1\actions\common;

use common\enums\UserType;
use common\models\OrderItem;
use common\models\OrderOption;
use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\GetInternalOrderForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use gateway\modules\v1\services\OrderService;
use Yii;

class GetInternalOrderAction extends GetApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return GetInternalOrderForm
     */
    protected function createRequestForm() {
        return new GetInternalOrderForm();
    }

    /**
     * Returns order information.
     *
     * @param GetInternalOrderForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm) {
        $order = false;
        try {
            $order = OrderService::getOrders(null, $requestForm->order_id);

            if (count($order) > 0){
                $order = $order[0];
            } else {
                throw new ErrorException('ERR_API_GET_INTERNAL_ORDER__ORDER_NOT_FOUND');
            }

            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->user_type == UserType::RestaurantApp){
                return OrderService::getRestaurantAppResponse([$order],Yii::$app->user->identity);
            }

            if (isset($order['delivery_address_data']) && !empty($order['delivery_address_data'])) {
                $order['delivery_address_data'] = unserialize(html_entity_decode($order['delivery_address_data']));
            }

            if (isset($order['billing_address_data']) && !empty($order['billing_address_data'])) {
                $order['billing_address_data'] = unserialize(html_entity_decode($order['billing_address_data']));
            }

            if (isset($order['voucher_data']) && !empty($order['voucher_data'])) {
                $order['voucher_data'] = unserialize(html_entity_decode($order['voucher_data']));
            }
            
            $total_quantity = 0;
            $max_cook_time = 0;
            foreach ($order['orderItems'] as &$orderItem) {
                $orderItem['menuItem']['name_key'] = Yii::$app->globalCache->getLabel($orderItem['menuItem']['name_key']);
                $orderItem['menuItem']['menuCategory']['name_key'] = Yii::$app->globalCache->getLabel($orderItem['menuItem']['menuCategory']['name_key']);
                $total_quantity += $orderItem['quantity'];
                $max_cook_time = $orderItem['menuItem']['cook_time'] > $max_cook_time ? $orderItem['menuItem']['cook_time'] : $max_cook_time;
            }

            return [
                'order' => $order,
                'total_quantity' => $total_quantity,
                'max_cook_time' => $max_cook_time,
            ];
        } catch (Exception $ex) {
            return $ex;
        }
    }

}
