<?php

namespace admin\controllers;

use admin\common\ArrayHelper;
use common\models\Client;
use common\models\RestaurantChain;
use common\models\RestaurantGroup;
use Yii;
use common\models\RestaurantSchedule;
use admin\controllers\search\RestaurantScheduleSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Restaurant;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;

/**
 * RestaurantScheduleController implements the CRUD actions for RestaurantSchedule model.
 */
class RestaurantScheduleController extends BaseController
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionGetSchedule($id, $model)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $schedules = RestaurantSchedule::getRestaurantSchedulesById($id, $model);
        $this->renderJson(ArrayHelper::convertArToArray($schedules));
    }

    public function actionSaveSchedule()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        if (RestaurantSchedule::saveSchedules($data['schedules'], $data['id'], $data['model'])) {
            // update cache
            if($data['model'] == 'restaurant')
                $client_key = Restaurant::getById($data['id'])->client->key;
            elseif ($data['model'] == 'restaurant_chain')
                $client_key = RestaurantChain::findOne(['id'=>$data['id']])->client->key;
            elseif ($data['model'] == 'restaurant_group')
                $client_key = RestaurantGroup::findOne(['id'=>$data['id']])->restaurantChain->client->key;
            elseif ($data['model'] == 'client')
                $client_key = Client::findOne(['id'=>$data['id']])->key;

            $this->reloadCache($client_key);
            return ['result' => 'Success', 'schedules' => ArrayHelper::convertArToArray(RestaurantSchedule::getRestaurantSchedulesById($data['id'], $data['model']))];
        }
        return ['result' => 'Error'];

        //$this->renderJson($data['schedules']);
    }

    private function reloadCache($client_key) {
        Yii::$app->globalCache->addUpdateCacheAction("loadRestaurantsByClientKey('".$client_key."')");
    }

    public function actionForm($model)
    {
        return $this->renderPartial('_form', [
            'model' => $model
        ]);
    }

}

