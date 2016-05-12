<?php

namespace frontend\controllers;

use common\components\DeliveryHelper;
use Yii;
use common\enums\DeliveryType;

class OrderController extends \yii\web\Controller
{
    public function actionCheckout()
    {
//        $session_user = Yii::$app->userCache->getUser();
//
//        $restaurant = Yii::$app->globalCache->getRestaurant($session_user->restaurant_id);

        return $this->render('checkout',
            [
//                'delivery_charge' => DeliveryHelper::getDeliveryCharge($restaurant, $session_user->latitude, $session_user->longitude)
                'deliveryTypeFiler' => $this->getDeliveryTypeData(),
            ]
        );
    }

    /**
     * @return array
     */
    protected function getDeliveryTypeData()
    {
        $deliveryTypeFilter = [
            'types' => [
                DeliveryType::DeliveryAsap    => 'Delivery Asap',
                DeliveryType::DeliveryLater   => 'Delivery Later',
                DeliveryType::CollectionAsap  => 'Collect Asap',
                DeliveryType::CollectionLater => 'Collect Later',
            ],
            'dates' => Yii::$app->deliveryDatesService->generateDeliveryDates(),
        ];
        return $deliveryTypeFilter;
    }
    
    public function actionPaymentCompleted($merchantReference, $skinCode, $shopperLocale, $paymentMethod = null, $authResult, $pspReference = null, $merchantSig)
    {
        return $this->render('payment-completed', [
            'params' => [
                'merchant_reference' => $merchantReference,
                'skin_code' => $skinCode,
                'payment_method' => $paymentMethod,
                'auth_result' => $authResult,
                'psp_reference' => $pspReference,
                'merchant_sig' => $merchantSig
            ]]);
    }

    public function actionTracker($order_number = null, $clearOrder = false) {
        return $this->render('tracker', [
            'order_number' => $order_number,
            'clearOrder' => $clearOrder
        ]);
    }

}
