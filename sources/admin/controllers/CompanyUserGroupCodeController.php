<?php

namespace admin\controllers;

use Yii;
use common\models\CompanyUserGroupCode;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;
use admin\common\ArrayHelper;

/**
 * CompanyUserGroupUserController implements the CRUD actions for CompanyUserGroupUser model.
 */
class CompanyUserGroupCodeController extends BaseController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowAllActionsForRoles([
                        UserType::Admin,
                        UserType::CorporateAdmin,
                        UserType::ClientAdmin
                    ]),
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Finds the CompanyUserGroupUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CompanyUserGroupUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyUserGroupUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Company codes list
     *
     * @param integer $company_id
     */
    public function actionCompanyCodes($company_id)
    {
        return $this->renderPartial('_list', [ 'company_id' => $company_id ]);
    }

    /**
     * Get all company codes
     *
     * @param integer $id
     */
    public function actionGetCompanyCodes($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $groups = \common\models\Code::find()->where("record_type <> '" . \common\enums\RecordType::Deleted . "' AND company_id = {$id}")->all();
        $this->renderJson(ArrayHelper::convertArToArray($groups));
    }

    /**
     * Add / edit code form
     *
     * @param integer $group_id
     */
    public function actionPopupForm()
    {
        return $this->renderPartial('_popupForm');
    }

    /**
     * Save company group data
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $code = \common\models\Code::saveByPost($data['code']);
        if (count($code->errors) == 0) {
            $this->reloadCache($code);
            $code->refresh();
        }
        $this->renderJson(ArrayHelper::convertArToArray($code));
    }

    private function reloadCache($model)
    {
        Yii::$app->globalCache->addUpdateCacheAction("loadCompany($model->company_id)");
    }
}
