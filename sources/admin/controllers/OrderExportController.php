<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   7/16/15
 * @time   11:51 AM
 */

namespace admin\controllers;

use admin\common\ArrayHelper;
use common\models\OrderExport;
use yii\caching\ArrayCache;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use yii\filters\VerbFilter;
use Yii;
use yii\web\HttpException;
use yii\web\Response;

class OrderExportController extends BaseController
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

    public function actionGetOrderExport($id = null,$model)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $exports = false;
        if(!is_null($id)){
            $exports = OrderExport::find()->where([$model.'_id'=>$id])->asArray(true)->all();
        }
        if(!$exports){
            $exports = [ArrayHelper::convertArToArray(new OrderExport())];
        }
        $this->renderJson($exports);
    }

    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        if($this->saveConfigs($data['export'],$data['id'],$data['model'])){
            return [
                'result'=>'Success',
                'export'=>OrderExport::find()->where([$data['model'].'_id'=>$data['id']])->asArray(true)->all()
            ];
        }else{
            return ['result'=>'Error'];
        }
    }
    private function saveConfigs($exports,$id,$model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isSaved = true;
        $column = $model . '_id';
        try{
            foreach($exports as $export){
                if(isset($export['id']) && is_numeric($export['id'])){
                    $model = OrderExport::findOne([
                        'id'=>$export['id'],
                        $column=>$id
                    ]);
                    if(!$model){
                        throw new \Exception('Wrong export id');
                    }
                }else{
                    $model = new OrderExport();
                }

                if(!$model->load($export,'')){
                    Yii::error('Report load error');
                }else {
                    $model->$column = $id;
                    $isSaved = $isSaved && $model->save();
                    if(!$isSaved && $model->errors){
                        Yii::error('Save Export error');
                        Yii::error(var_export($model->errors));
                        break;
                    }
                }
            }
            if ($isSaved) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        }catch (\Exception $e){
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }
        return $isSaved;
    }

    public function actionForm($model,$selectedExport = false)
    {
        return $this->renderPartial('_form', [
            'model' => $model,
            'selectedExport'=>$selectedExport
        ]);
    }

    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $exportId = $data['exportId'];
        $id = $data['id'];
        $model = $data['model'];
        OrderExport::findOne(['id'=>$exportId,$model.'_id'=>$id])->delete();
        return [
            'result'=>'Success',
            'export'=>OrderExport::find()->where([$model.'_id'=>$id])->asArray(true)->all()
        ];
    }
}