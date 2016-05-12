<?php

namespace admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;
use admin\common\ArrayHelper;

/**
 * ExpenseTypeController implements the CRUD actions for ExpenseType model.
 */
class ExpenseTypeController extends BaseController
{
    public $enableCsrfValidation = false;
    public $company_group;

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
     * Company codes list
     *
     * @param integer $company_id
     */
    public function actionCompanyExpenseTypes($company_id)
    {
        return $this->renderPartial('_list', [ 'company_id' => $company_id ]);
    }

    /**
     * Get all company codes
     *
     * @param integer $id
     */
    public function actionGetCompanyExpenseTypes($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $expenceTypes = \common\models\ExpenseType::find()
            ->select([
                'expense_type.id', 'expense_type.name', 'company_group' => 'company_user_group.name', 'soft_limit_max',
                'company_user_group_id', 'limit_type', 'expense_type.id', 'limit_per_order', 'expense_type.record_type'
            ])
            ->leftJoin('company_user_group', 'company_user_group.id = expense_type.company_user_group_id')
            ->where("company_user_group.company_id = {$id} AND expense_type.record_type <> '" . \common\enums\RecordType::Deleted . "'")->all();

        $array = ArrayHelper::convertArToArray($expenceTypes);
        foreach ($expenceTypes as $id => $type) {
            $array[$id]['company_group'] = $type->company_group;
        }
        $this->renderJson($array);
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

    public function actionGetSchedule($company_id, $schedule_id = false, $clear = false)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $schedules = \common\models\ExpenseTypeSchedule::getEmptySchedule();
        if (!$clear) {
            $filledSchedule = \common\models\ExpenseType::getExpenseTypeSchedulesById($schedule_id);
        }

        $export = [
            'schedule' => ArrayHelper::convertArToArray($clear ? $schedules : $filledSchedule),
            'groups'   => ArrayHelper::convertArToArray(\common\models\CompanyUserGroup::find()->where("company_id = {$company_id} AND record_type <> '" . \common\enums\RecordType::Deleted . "'")->all())
        ];

        for ($idx = 0; $idx < sizeof($export['schedule']); $idx++) {
            $export['schedule'][$idx]['from_label'] = $schedules[$idx]->from_label;
            $export['schedule'][$idx]['day_label']  = $schedules[$idx]->day_label;
        }

        $this->renderJson($export);
    }

    /**
     * Save company group data
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data    = json_decode(Yii::$app->request->rawBody, true);
        $exptype = \common\models\ExpenseType::saveByPost($data['extype']);
        if (count($exptype->errors) == 0) {
            $exptype->refresh();
            $this->reloadCache($exptype);
            $expenceType = \common\models\ExpenseType::find()
                ->select([
                    'expense_type.id', 'expense_type.name', 'company_group' => 'company_user_group.name', 'soft_limit_max',
                    'company_user_group_id', 'limit_type', 'expense_type.id', 'limit_per_order', 'expense_type.record_type'
                ])
                ->leftJoin('company_user_group', 'company_user_group.id = expense_type.company_user_group_id')
                ->where("expense_type.record_type <> '" . \common\enums\RecordType::Deleted . "' AND expense_type.id = {$exptype->id}")->one();
            if ($expenceType) {
                $exptype = ArrayHelper::convertArToArray($expenceType);
                $exptype['company_group'] = $expenceType->company_group;
            } else {
                $exptype = ArrayHelper::convertArToArray($exptype);
            }

        } else {
            $exptype = ArrayHelper::convertArToArray($exptype);
        }
        $this->renderJson($exptype);
    }

    public function reloadCache($model) {
        $clientId = $model->companyUserGroup->company->client->id;
        Yii::$app->globalCache->addUpdateCacheAction("loadCompaniesByClient($clientId)");
    }
}
