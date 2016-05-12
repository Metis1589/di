<?php

namespace admin\controllers;

use Yii;
use common\models\CompanyUserGroup;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;
use admin\common\ArrayHelper;

/**
 * CompanyUserGroupController implements the CRUD actions for CompanyUserGroup model.
 */
class CompanyUserGroupController extends BaseController
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Comnpany users groups list
     *
     * @param integer $company_id
     *
     * @return mixed
     */
    public function actionCompanyGroups($company_id)
    {
        return $this->renderPartial('_list', [ 'company_id' => $company_id ]);
    }

    /**
     * Add / edit groups form
     *
     * @param integer $group_id
     */
    public function actionPopupForm()
    {
        return $this->renderPartial('_popupForm');
    }

    /**
     * Get company groups list
     *
     * @param integer $id
     */
    public function actionGetCompanyGroups($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $groups = CompanyUserGroup::find()
            ->joinWith('companyUserGroupCodeNames')
            ->joinWith('companyUserGroupUsers')
            ->where("company_user_group.record_type <> '" . \common\enums\RecordType::Deleted . "' AND company_user_group.company_id = {$id}")
            ->all();

        $export = [
            'groups' => ArrayHelper::convertArToArray($groups),
            'codes'  => ArrayHelper::convertArToArray(\common\models\Code::find()->where("record_type <> '" . \common\enums\RecordType::Deleted . "' AND company_id = {$id}")->all())
        ];
        $this->renderJson($export);
    }

    /**
     * Save company group data
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data  = json_decode(Yii::$app->request->rawBody, true);
        $group = CompanyUserGroup::saveByPost($data['group']);

        if (count($group->errors) == 0) {
            $group->refresh();
            $groups = CompanyUserGroup::find()
                ->joinWith('companyUserGroupCodeNames')
                ->joinWith('companyUserGroupUsers')
                ->where("company_user_group.record_type <> '" . \common\enums\RecordType::Deleted . "' AND company_user_group.company_id = {$data['group']['company_id']}")
                ->all();
            $this->reloadCache($group);
            $this->renderJson(ArrayHelper::convertArToArray($groups));

        } else {
            $this->renderJson(ArrayHelper::convertArToArray($group));
        }
    }

    private function reloadCache($model) {
        $clientId = $model->company->client->id;
        Yii::$app->globalCache->addUpdateCacheAction("loadCompaniesByClient($clientId)");
    }
}
