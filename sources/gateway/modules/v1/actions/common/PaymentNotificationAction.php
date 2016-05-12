<?php

namespace gateway\modules\v1\actions\common;

use common\models\Order;
use gateway\modules\v1\forms\common\PaymentNotificationForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;
use common\enums\OrderStatus;
use common\enums\UserType;

class PaymentNotificationAction extends PostApiAction {


    /**
     * Creates request form used to validate request parameters.
     *
     * @return PasswordResetForm
     */
    protected function createRequestForm() {
        return new PaymentNotificationForm();
    }

    /**
     * Add review.
     *
     * @param AddReviewForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm) {
        try {

            Yii::warning($requestForm, 'payment-notification-request');
            $pspReference = $requestForm->pspReference;
            
            $payment_notification_history = new \common\models\PaymentNotificationHistory();
            $payment_notification_history->psp_reference = $requestForm->pspReference;
            $payment_notification_history->event_code = $requestForm->eventCode;
            $payment_notification_history->event_date = Yii::$app->formatter->asDatetime($requestForm->eventDate, 'php:Y-m-d H:i:s');
            $payment_notification_history->success = $requestForm->success?1:0;
            $payment_notification_history->live = $requestForm->live?1:0;
            $payment_notification_history->amount = $requestForm->amount;
            $payment_notification_history->value = $requestForm->value;
            $payment_notification_history->merchant_account_code = $requestForm->merchantAccountCode;
            $payment_notification_history->merchant_reference = $requestForm->merchantReference;
            $payment_notification_history->original_reference = $requestForm->originalReference;
            $payment_notification_history->operations = $requestForm->operations;
            $payment_notification_history->reason = $requestForm->reason;
            if (!$payment_notification_history->save()){
                throw new \yii\base\ErrorException("ERR_API_PAYMENT_NOTIFICATION__ORDER_HISTORY_SAVE");
            }
            
            Yii::error($payment_notification_history->getErrors(), 'payment-notification-history');
            
            Yii::error($requestForm->pspReference, 'payment-notification-pspReference');
            
            $order = Order::find()->where(['psp_reference' => $pspReference, 'status' => OrderStatus::ProcessingPayment])->one();
            
            if ($order == null){
               print "[accepted]";die();
            }
             
            switch ($requestForm->eventCode) {

                case 'AUTHORISATION':
                    // Handle AUTHORISATION notification.
                    // Confirms whether the payment was authorised successfully.
                    // The authorisation is successful if the "success" field has the value true.
                    // In case of an error or a refusal, it will be false and the "reason" field
                    // should be consulted for the cause of the authorisation failure.
                    \gateway\modules\v1\services\OrderService::changeOrderStatus($order, \common\enums\OrderStatus::PaymentReceived, UserType::Admin);
                    
                    break;

                case 'CANCELLATION':
                    // Handle CANCELLATION notification.
                    // Confirms that the payment was cancelled successfully. 
                    break;

                case 'REFUND':
                     Yii::warning($requestForm->value, 'payment-notification-refund');
                    // TODO Send email to customer
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
            
            //TODO check currency
            //TODO check value

//            [originalReference] => 
//            [reason] => 
//            [merchantAccountCode] => DineInLimitedUK
//            [eventCode] => NOTIFICATIONTEST
//            [operations] => 
//            [success] => true
//            [paymentMethod] => visa
//            [currency] => EUR
//            [pspReference] => test_NOTIFICATIONTEST_1
//            [merchantReference] => testMerchantRef1
//            [value] => 11099
//            [live] => false
//            [eventDate] => 2015-05-06T20:47:45.95Z
                        
            print "[accepted]";die();
        } catch (Exception $ex) {
            Yii::error($ex->getMessage(), 'payment-notification-error');
            return $ex;
        }
    }

}
