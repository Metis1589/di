<?php

namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\components\PostApiAction;
use gateway\modules\v1\forms\common\UpdateOrderRefundForm;
use Exception;
use gateway\modules\v1\services\OrderService;
use Yii;
use yii\base\ErrorException;
use \gateway\components\PaymentHelper;

class UpdateOrderRefundAction extends PostApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return UpdateOrderRefundForm
     */
    protected function createRequestForm() {
        return new UpdateOrderRefundForm();
    }

    /**
     * Returns candidate's gender.
     *
     * @param UpdateOrderRefundForm $requestForm Request form class instance.
     * @throws
     * @return mixed
     */
    protected function getResponseData($requestForm) {
        try {

            $order = \common\models\Order::find()->where(['id' => $requestForm->order_id])->with('restaurant')->one();

            $user = Yii::$app->user->identity;
            
            $client = Yii::$app->globalCache->getClientById($order->restaurant->client_id);
            
            if (!empty($requestForm->client_refund_diff) ){
                $order->client_refund = (float)$order->client_refund + (float)$requestForm->client_refund_diff;
            }
            
            if (!empty($requestForm->restaurant_refund_diff)){
                $order->restaurant_refund = (float)$order->restaurant_refund + (float)$requestForm->restaurant_refund_diff;
            }
            
            if (!OrderService::processRefund((float)$requestForm->client_refund_diff + (float)$requestForm->restaurant_refund_diff, $order, $client)){
                throw new ErrorException('ERR_API_UPDATE_ORDER_REFUND__UNABLE_TO_REFUND_VALUE');
            }
            
            if (!empty($requestForm->corporate_client_refund)){
                $order->corporate_client_refund = $requestForm->corporate_client_refund;
            }
            if (!empty($requestForm->corporate_restaurant_refund)) {
                $order->corporate_restaurant_refund = $requestForm->corporate_restaurant_refund;
            }

            if (!empty($requestForm->internal_comment)) {
                $order->internal_comment = $requestForm->internal_comment;
            }

            if (!$order->save()) {
                throw new ErrorException('ERR_API_UPDATE_ORDER_REFUND__UNABLE_TO_SAVE_ORDER');
            }
            
            $order = OrderService::getOrders(null, $requestForm->order_id);
            
            if (count($order) > 0){
                $order = $order[0];
            } else {
                throw new ErrorException('ERR_API_UPDATE_ORDER_REFUND__ORDER_NOT_FOUND');
            }

            if (isset($order['delivery_address_data']) && !empty($order['delivery_address_data'])) {
                $order['delivery_address_data'] = unserialize($order['delivery_address_data']);
            }

            if (isset($order['billing_address_data']) && !empty($order['billing_address_data'])) {
                $order['billing_address_data'] = unserialize($order['billing_address_data']);
            }

            if (isset($order['voucher_data']) && !empty($order['voucher_data'])) {
                $order['voucher_data'] = unserialize($order['voucher_data']);
            }
            
            if (isset($order['orderHistories']) && !empty($order['orderHistories'])){
                foreach ($order['orderHistories'] as &$history) {
                    $history['status'] = \common\enums\OrderStatus::getAbbr($history['status']);
                    $history['create_on'] = date("H:i",strtotime($history['create_on']));
                }
            }
            $order['restaurant_refund_diff'] = 0;
            $order['client_refund_diff'] = 0;
            
            $order['current_status'] = $order['status'];

            return $order;
        } catch (Exception $ex) {
//            $transaction->rollBack();
            return $ex;
        }
    }
}
