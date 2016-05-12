<?php

namespace admin\controllers;

use Yii;
use admin\common\ArrayHelper;
use common\models\CompanyDomain;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;

/**
 * CompanyDomainController implements the CRUD actions for CompanyDomain model.
 */
class CompanyDomainController extends BaseController
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
                        UserType::ClientAdmin
                    ]),
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'post' ],
                    'save'   => [ 'post' ]
                ],
            ],
        ];
    }

    /**
     * Company domains list
     *
     * @param integer $company_id
     */
    public function actionCompanyDomains($company_id)
    {
        return $this->renderPartial('_list', [ 'company_id' => $company_id ]);
    }

    /**
     * Add / edit domains form
     *
     * @param integer $company_id
     */
    public function actionPopupForm()
    {
        return $this->renderPartial('_popupForm');
    }

    /**
     * Get company domains
     *
     * @param integer $id
     */
    public function actionGetCompanyDomains($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $domains = CompanyDomain::find()->where("record_type <> '" . \common\enums\RecordType::Deleted . "' AND company_id = {$id}")->all();
        $this->renderJson(ArrayHelper::convertArToArray($domains));
    }

    /**
     * Save company domain (edit / create)
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data   = json_decode(Yii::$app->request->rawBody, true);
        $domain = CompanyDomain::saveByPost($data['domain']);
        if (count($domain->errors) == 0) {
            $domain->refresh();
        }
        $this->renderJson(ArrayHelper::convertArToArray($domain));
    }
}
