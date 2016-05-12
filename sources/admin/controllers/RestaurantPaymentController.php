<?php

namespace admin\controllers;

use admin\common\ArrayHelper;
use common\components\identity\RbacHelper;
use Yii;
use common\models\RestaurantPayment;
use admin\controllers\search\RestaurantPaymentSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use admin\forms\RestaurantPaymentForm;
use yii\web\Response;

/**
 * RestaurantPaymentController implements the CRUD actions for RestaurantPayment model.
 */
class RestaurantPaymentController extends BaseController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowAllActionsForRestaurantUser(Yii::$app->request->getFirstParamValue(['restaurant_id'])),
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'save' => ['post'],
                ],
            ],
        ];
    }

    public function actionGetPayment($restaurant_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $contacts = RestaurantPayment::findOne(['restaurant_id' => $restaurant_id]);
        $this->renderJson(ArrayHelper::convertArToArray($contacts));
    }

    public function actionForm()
    {
        return $this->renderPartial('_form');
    }

    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $saveResult = RestaurantPayment::saveByPost($data['payment'], $data['restaurant_id']);
        if ($saveResult === false) {
            return 'Error';
        }
        $this->reloadCache($saveResult);
        $saveResult->refresh();
        $this->renderJson(ArrayHelper::convertArToArray($saveResult));
    }

    private function reloadCache($model) {
        Yii::$app->globalCache->addUpdateCacheAction("loadRestaurantsByClientKey('".$model->restaurant->client->key."')");
    }
}
