<?php

namespace frontend\controllers;

use common\components\DeliveryHelper;
use Yii;
use common\enums\DeliveryType;

class UserController extends \yii\web\Controller
{
    public function actionUser()
    {
//        $session_user = Yii::$app->userCache->getUser();
//
//        $restaurant = Yii::$app->globalCache->getRestaurant($session_user->restaurant_id);

        return $this->render('user',
            [
                'deliveryTypeFiler'=>$this->getDeliveryTypeData()
//                'delivery_charge' => DeliveryHelper::getDeliveryCharge($restaurant, $session_user->latitude, $session_user->longitude)
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
}
