<?php

namespace admin\controllers;

use admin\common\ArrayHelper;
use common\models\Voucher;
use common\models\VoucherSchedule;
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
class VoucherScheduleController extends BaseController
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

    public function actionGetSchedule($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $schedules = VoucherSchedule::getVoucherSchedulesById($id);
        $this->renderJson(ArrayHelper::convertArToArray($schedules));
    }

    public function actionSaveSchedule()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        if (VoucherSchedule::saveSchedules($data['schedules'], $data['id'])) {
            // update cache
            $client_key = Voucher::findOne($data['id'])->client->key;

            $this->reloadCache($client_key);

            return ['result' => 'Success', 'schedules' => ArrayHelper::convertArToArray(VoucherSchedule::getVoucherSchedulesById($data['id']))];
        }
        return ['result' => 'Error'];
    }

    private function reloadCache($client_key) {
        Yii::$app->globalCache->addUpdateCacheAction("loadVouchersByClient('".$client_key."')");
    }

    public function actionForm($id)
    {
        return $this->renderPartial('_form', [
            'id' => $id
        ]);
    }

}

