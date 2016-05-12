<?php

namespace gateway\modules\v1\actions\common;

use common\enums\OrderStatus;
use common\enums\UserType;
use common\models\Address;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderOption;
use gateway\models\SessionUser;
use gateway\modules\v1\services\OrderService;
use gateway\modules\v1\forms\common\CheckoutForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class CheckoutAction extends PostApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return CheckoutForm
     */
    protected function createRequestForm() {
        return new CheckoutForm();
    }

    /**
     * Checkout
     *
     * @param CheckoutForm $requestForm Request form class instance.
     *
     * @return boolean
     */
    protected function getResponseData($requestForm) {
        try {

            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            OrderService::validateOrder($requestForm->client_key);
            
            $order = OrderService::createOrder($session_user, $requestForm);

            if (is_null($order)) {
                throw new \yii\base\ErrorException("Error creating order");
            }

            $session_user->order = $order;

            Yii::$app->userCache->setUser($session_user);

            $has_inntouch = isset(Yii::$app->user->identity->client->has_inntouch) && Yii::$app->user->identity->client->has_inntouch;

            if (empty($order->paid) && (Yii::$app->user->isGuest || Yii::$app->user->identity->user_type != UserType::CorporateMember)) {
                throw new Exception('Paid could be less zero only for Corporate Members');
            }

            if (Yii::$app->user->isGuest || Yii::$app->user->identity->user_type != UserType::CorporateMember) {
                return $this->getOrderValues($order, $requestForm->client_key);
            }

            if (Yii::$app->user->identity->user_type == UserType::CorporateMember) {
                if ($has_inntouch || empty($order->paid)) {
                    OrderService::changeOrderStatus($order, OrderStatus::PaymentReceived, Yii::$app->user->identity->user_type, Yii::$app->user->identity->id);
                    $session_user->clearOrder();
                    return [
                        'url' => '',
                        'order_number' => $order->order_number
                    ];
                }
                return $this->getOrderValues($order, $requestForm->client_key);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    private function getOrderValues($order, $client_key) {
        $client = Yii::$app->globalCache->getClient($client_key);
        
        $request = [
            "merchantReference" => "PAYMENT-FOR-ORDER-". $order->id . date("Y-m-d-H:i:s"),
            "paymentAmount" => $order->paid*100,
            "currencyCode" => strtoupper($order->currency_code),
            "shipBeforeDate" => date("Y-m-d", strtotime("+3 days")),
            "skinCode" => $client["payment_skin_code"],
            "merchantAccount" => $client["payment_merchant_account"],
            "sessionValidity" => date("c", strtotime("+1 days")),
            "shopperLocale" => "en_GB",
            "orderData" => base64_encode(gzencode("Dinein payment")),
            "shopperEmail" => "",
            "countryCode" => "GB",
            "shopperReference" => "",
            "allowedMethods" => "",
            "blockedMethods" => "",
            "offset" => "",
            "merchantSig" => ""
        ];


        $hmacKey = $client["payment_hmac_key"];

        $request["merchantSig"] = base64_encode(pack("H*",hash_hmac(
  	'sha1',
	$request["paymentAmount"] . $request["currencyCode"] . $request["shipBeforeDate"] . $request["merchantReference"] . 
	$request["skinCode"] . $request["merchantAccount"] . $request["sessionValidity"] . $request["shopperEmail"] . 
	$request["shopperReference"] . $request["allowedMethods"] . $request["blockedMethods"] . $request["offset"],
	$hmacKey
  )));


        $url = "https://test.adyen.com/hpp/pay.shtml?";
        foreach ($request as $field => $value)
            $url .= "&" . $field . "=" . urlencode($value);

        return [
            'url' => $url
        ];
    }

}
