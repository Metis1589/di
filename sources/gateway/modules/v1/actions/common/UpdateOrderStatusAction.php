<?php

namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\components\PostApiAction;
use gateway\modules\v1\forms\common\UpdateOrderStatusForm;
use Exception;
use gateway\modules\v1\services\OrderService;
use Yii;
use yii\base\ErrorException;
use \gateway\components\PaymentHelper;
use \DateTime;
use \DateInterval;

class UpdateOrderStatusAction extends PostApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return UpdateOrderStatusForm
     */
    protected function createRequestForm() {
        return new UpdateOrderStatusForm();
    }

    /**
     * Returns candidate's gender.
     *
     * @param UpdateOrderStatusForm $requestForm Request form class instance.
     * @throws
     * @return mixed
     */
    protected function getResponseData($requestForm) {
        try {
            $order = \common\models\Order::find()->where(['id' => $requestForm->order_id])->with('restaurant')->one();
            $user  = Yii::$app->user->identity;

            OrderService::changeOrderStatus($order, $requestForm->order_status, $user->user_type, $user->id);

            $order->status = $requestForm->order_status;

            if (!empty($requestForm->internal_comment)) {
                $order->internal_comment = $requestForm->internal_comment;
            }

            if (!empty($requestForm->restaurant_comment)) {
                $order->restaurant_comment = $requestForm->restaurant_comment;
            }
            if (!empty($requestForm->restaurant_charge)) {
                $order->restaurant_charge = $requestForm->restaurant_charge;
            }
            if (!empty($requestForm->client_cost)) {
                $order->client_cost = $requestForm->client_cost;
            }
            if (!empty($requestForm->client_received)) {
                $order->client_received = $requestForm->client_received;
            }
            if (!empty($requestForm->restaurant_credit)) {
                $order->restaurant_credit = $requestForm->restaurant_credit;
            }
            if (!empty($requestForm->ready_by)) {
                $order->ready_by = $requestForm->ready_by;
            }
            if (!empty($requestForm->ready_by_time)) {
                if ($order->ready_by){
                    $ready_by = new DateTime($order->ready_by);
                    $ready_by->add(new DateInterval('PT' . $requestForm->ready_by_time . 'M'));
                    $order->ready_by = $ready_by->format('Y-m-d H:i:s');
                } else {
                    $ready_by = new DateTime();
                    $ready_by->add(new DateInterval('PT' . $requestForm->ready_by_time . 'M'));
                    $order->ready_by = $ready_by->format('Y-m-d H:i:s');
                }
            }
            if (!empty($requestForm->cancellation_reason)) {
                $order->cancellation_reason = $requestForm->cancellation_reason;
            }

            if (!$order->save()) {
                throw new ErrorException('ERR_API_UPDATE_ORDER_STATUS__UNABLE_TO_SAVE_ORDER');
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

            $name  = '';
            if (!empty($order['delivery_address_data'])) {
                $name = $order['delivery_address_data']['first_name'];
            }

            if (!empty($order['billing_address_data'])) {
                $name = $order['billing_address_data']['first_name'];
            }

            $phone = '';
            if (!empty($order['delivery_address_data'])) {
                $name = $order['delivery_address_data']['phone'];
            }

            if (!empty($order['billing_address_data'])) {
                $name = $order['billing_address_data']['phone'];
            }

            $order['current_status'] = $order['status'];

            if (isset($order['orderHistories']) && !empty($order['orderHistories'])){
                foreach ($order['orderHistories'] as &$history) {
                    $history['status'] = \common\enums\OrderStatus::getAbbr($history['status']);
                    $history['create_on'] = date("H:i",strtotime($history['create_on']));
                }
            }
            return $order;
        } catch (Exception $ex) {
//            $transaction->rollBack();
            throw $ex;
        }
    }

    private function processRefund($requestForm, $order, $client){
        if ($order->psp_reference != null){
            if ($this->isRefundAllowed($requestForm, $order)){
               $result = false;
               if (!empty($requestForm->client_refund_diff)){
                   $result = PaymentHelper::Refund($order->psp_reference, $order->currency_code, $requestForm->client_refund_diff, $client);
               }
               if (!empty($requestForm->restaurant_refund_diff)){
                   $result = PaymentHelper::Refund($order->psp_reference, $order->currency_code, $requestForm->restaurant_refund_diff, $client);
               }

               return $result;
            }
        }

        return false;
    }

    private function isRefundAllowed($requestForm, $order){
        if ($order->client_refund + $order->restaurant_refund + $requestForm->client_refund + $requestForm->restaurant_refund >= $order->total){
            return false;
        }
        return true;
    }

}
