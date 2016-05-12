<?php

namespace console\controllers;

use common\enums\RecordType;
use common\models\CacheQueue;
use Yii;
use yii\base\Exception;
use yii\console\Controller;

class CacheUpdateController extends Controller
{
    public function actionIndex() {
        try{
            if (Yii::$app->globalCache->cacheIsUpdating()) {
                return;
            }
            Yii::$app->globalCache->cacheUpdatingStarted();

            while($cacheQueues = CacheQueue::find()->where(['record_type' => RecordType::Active])->all()) {
                foreach($cacheQueues as $cacheQueue) {
                    $action = $cacheQueue->action;
                    $cacheQueue->record_type = RecordType::InActive;
                    $cacheQueue->save();
                    $updateAction = 'Yii::$app->globalCache->'. $action .';';
                    $isUpdated = eval($updateAction);
                    $cacheQueue->delete();
                }
            }

            Yii::$app->globalCache->cacheUpdatingFinished();
        } catch (Exception $ex) {
            echo 'cron updating exception '. $ex->getMessage();
            Yii::$app->globalCache->cacheUpdatingFinished();
        }

        echo 'cron is updated';
    }
}