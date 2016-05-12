<?php

namespace admin\controllers;

use admin\common\ArrayHelper;
use Yii;
use common\models\RestaurantContactOrder;
use admin\controllers\search\RestaurantContactOrderSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\forms\RestaurantContactOrderForm;
use common\models\Restaurant;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;

/**
 * RestaurantContactOrderController implements the CRUD actions for RestaurantContactOrder model.
 */
class RestaurantContactOrderController extends BaseController
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

    public function actionGetContacts($restaurant_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $contacts = RestaurantContactOrder::findAll(['restaurant_id' => $restaurant_id]);
        $this->renderJson(ArrayHelper::convertArToArray($contacts));
    }


    public function actionList()
    {
        return $this->renderPartial('_list');
    }

    public function actionForm()
    {
        return $this->renderPartial('_form');
    }

    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $saveResult = RestaurantContactOrder::saveByPost($data['contact'], $data['restaurant_id']);
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
