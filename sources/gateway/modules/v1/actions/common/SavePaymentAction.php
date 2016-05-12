<?php

namespace gateway\modules\v1\actions\common;

use common\enums\UserType;
use common\models\Order;
use gateway\modules\v1\forms\common\SavePaymentForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class SavePaymentAction extends PostApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return SavePaymentForm
     */
    protected function createRequestForm() {
        return new SavePaymentForm();
    }

    /**
     * Save payment.
     *
     * @param SavePaymentForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm) {
        try {
            $session_user = Yii::$app->userCache->getUser();
            
            $payment = \common\models\PaymentNotificationHistory::find()->where('psp_reference = :psp_reference and merchant_reference = :merchant_reference and reason != :reason',['psp_reference' => $requestForm->psp_reference, 'merchant_reference' => $requestForm->merchant_reference, 'reason' => 'Refused'])->one();
            Yii::warning($payment, 'save-payment-object');

            /** @var Order $order */
            $order = &$session_user->order;
            
            if (empty($requestForm->payment_method) && empty($requestForm->psp_reference) && $requestForm->auth_result == 'CANCELLED'){
                return [
                    'result' => $requestForm->auth_result
                ]; 
            }
            
            $order->auth_result = $requestForm->auth_result;
            $order->psp_reference = $requestForm->psp_reference;
            $order->merchant_reference = $requestForm->merchant_reference;
            $order->skin_code = $requestForm->skin_code;
            $order->payment_method = $requestForm->payment_method;
            $order->merchant_sig = $requestForm->merchant_sig;
            
            if (!empty($payment)){
                
                    switch ($payment->event_code) {

                    case 'AUTHORISATION':
                        // Handle AUTHORISATION notification.
                        // Confirms whether the payment was authorised successfully.
                        // The authorisation is successful if the "success" field has the value true.
                        // In case of an error or a refusal, it will be false and the "reason" field
                        // should be consulted for the cause of the authorisation failure.
                        $user_type = Yii::$app->user->isGuest ? UserType::UNAUTHORIZED : Yii::$app->user->identity->user_type;
                        \gateway\modules\v1\services\OrderService::changeOrderStatus($order, \common\enums\OrderStatus::PaymentReceived, $user_type);
                        break;

                    case 'CANCELLATION':
                        // Handle CANCELLATION notification.
                        // Confirms that the payment was cancelled successfully. 
                        break;

                    case 'REFUND':
                        // Handle REFUND notification.
                        // Confirms that the payment was refunded successfully. 
                        break;

                    case 'CANCEL_OR_REFUND':
                        // Handle CANCEL_OR_REFUND notification.
                        // Confirms that the payment was refunded or cancelled successfully. 
                        break;

                    case 'CAPTURE':
                        // Handle CAPTURE notification.
                        // Confirms that the payment was successfully captured. 
                        break;

                    case 'REFUNDED_REVERSED':
                        // Handle REFUNDED_REVERSED notification.
                        // Tells you that the refund for this payment was successfully reversed. 
                        break;

                    case 'CAPTURE_FAILED':
                        // Handle AUTHORISATION notification.
                        // Tells you that the capture on the authorised payment failed. 
                        break;

                    case 'REQUEST_FOR_INFORMATION':
                        // Handle REQUEST_FOR_INFORMATION notification.
                        // Information requested for this payment .
                        break;

                    case 'NOTIFICATION_OF_CHARGEBACK':
                        // Handle NOTIFICATION_OF_CHARGEBACK notification.
                        // Chargeback is pending, but can still be defended 
                        break;

                    case 'CHARGEBACK':
                        // Handle CHARGEBACK notification.
                        // Payment was charged back. This is not sent if a REQUEST_FOR_INFORMATION or
                        // NOTIFICATION_OF_CHARGEBACK notification has already been sent.
                        break;

                    case 'CHARGEBACK_REVERSED':
                        // Handle CHARGEBACK_REVERSED notification.
                        // Chargeback has been reversed (cancelled).
                        break;

                    case 'REPORT_AVAILABLE':
                        // Handle REPORT_AVAILABLE notification.
                        // There is a new report available, the URL of the report is in the "reason" field.
                        break;
                }
            }
            
            if (!$order->save()){
               throw new \yii\base\ErrorException("ERR_API_SAVE_PAYMENT__ORDER_SAVE");
            }
            
            $order_number = $order->order_number;
            
            if ($order->auth_result == 'REFUSED'){
                return [
                    'order_number' => $order_number,
                    'result' => $order->auth_result
                ]; 
            }
            
            if (!empty($payment) && $payment->event_code == 'AUTHORISATION'){
                $session_user->clearOrder();
            }

            return [
                'order_number' => $order_number,
                'result' => $order->auth_result
            ];
            
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
