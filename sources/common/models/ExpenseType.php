<?php

namespace common\models;

use common\enums\RecordType;
use Yii;
use common\components\language\T;

/**
 * This is the model class for table "expense_type".
 *
 * @property integer $id
 * @property integer $company_user_group_id
 * @property string $name
 * @property string $last_update
 * @property double $limit_per_order
 * @property string $limit_type
 * @property double $soft_limit_max
 * @property string $record_type
 * @property string $create_on
 *
 * @property CompanyUserGroup $companyUserGroup
 */
class ExpenseType extends \common\models\BaseModel
{
    public $company_group;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expense_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['company_user_group_id', 'required', 'message' => T::e('Expense type group cannot be blank')],
            [['name', 'limit_per_order', 'limit_type'], 'required'],
            ['soft_limit_max', 'required',
                'when'       => function($model)  { return $model->limit_type == \common\enums\CompanyLimitType::Soft; },
                'whenClient' => "function() { return $('#expt_limit_type option:selected').val() === '" . \common\enums\CompanyLimitType::Soft . "' }"
            ],
            [['soft_limit_max', 'limit_per_order'], 'integer', 'max' => '999' ],
            ['company_user_group_id',               'integer'],
            [['last_update', 'create_on'],          'safe'],
            [['limit_per_order', 'soft_limit_max'], 'number'],
            [['limit_type', 'record_type'],         'string'],
            ['name',                                'string', 'max' => 250]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => T::l('ID'),
            'company_user_group_id' => T::l('Company User Group ID'),
            'name'                  => T::l('Name'),
            'last_update'           => T::l('Last Update'),
            'limit_per_order'       => T::l('Limit Per Order'),
            'limit_type'            => T::l('Limit Type'),
            'soft_limit_max'        => T::l('Soft Limit Max'),
            'record_type'           => T::l('Record Type'),
            'create_on'             => T::l('Create On'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseTypeSchedules()
    {
        return $this->hasMany(ExpenseTypeSchedule::className(), ['expense_type_id' => 'id']);
    }

    public function getActiveExpenseTypeSchedules()
    {
        return $this->hasMany(ExpenseTypeSchedule::className(), ['expense_type_id' => 'id'])->andOnCondition(['expense_type_schedule.record_type' => RecordType::Active]);;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroup()
    {
        return $this->hasOne(CompanyUserGroup::className(), [ 'id' => 'company_user_group_id' ]);
    }


    /**
     * Save expense type
     *
     * @param array $postData
     *
     * @return boolean
     */
    public static function saveByPost($postData)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isSaved     = true;

        try {
            $existedExpense = static::findOne($postData['id']);
            $isNew          = false;

            if ($existedExpense == null) {
                $existedExpense = new ExpenseType;
                $existedExpense->record_type = \common\enums\RecordType::Active;
                unset($postData['id']);
                $isNew = true;
            }

            if (isset($postData['schedules'])) {
                $shedules = $postData['schedules'];
            }

            $existedExpense->company_user_group_id = isset($postData['groups']['id']) ? $postData['groups']['id'] : $postData['company_user_group_id'];
            unset($postData['schedules'], $postData['groups']);
            $existedExpense->load($postData, '');

            if ($existedExpense->validate()) {
                $isSaved = $existedExpense->save();
                if (isset($shedules)) {
                    $isSaved = $isSaved && self::_saveSchedules($shedules, $existedExpense->id);
                }
            } else {
                $isSaved = false;
            }

            if ($isSaved) {
                $transaction->commit();
                Yii::$app->globalCache->loadCompany($existedExpense->companyUserGroup->company->id);
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
        }

        return $existedExpense;
    }

    /**
     * Save expense type shedule
     *
     * @param array $postData
     *
     * @return boolean
     */
    public static function _saveSchedules($postData, $expense_id)
    {
        $isSaved = true;

        foreach ($postData as $postedSchedule) {
            $existedSchedule = ExpenseTypeSchedule::findOne($postedSchedule['id']);
            if ($existedSchedule == null) {
                $existedSchedule = new ExpenseTypeSchedule;
            }

            $existedSchedule->expense_type_id = $expense_id;
            $existedSchedule->from            = $postedSchedule['from'];
            $existedSchedule->to              = $postedSchedule['to'];
            $existedSchedule->day             = $postedSchedule['day'];
            $existedSchedule->day_time_type   = $postedSchedule['day_time_type'];
            $existedSchedule->record_type     = \common\enums\RecordType::Active;

            if (empty($existedSchedule->from) && empty($existedSchedule->to)) {
                $existedSchedule->record_type = \common\enums\RecordType::Deleted;
                $existedSchedule->save();
                continue;
            }
            $isSaved = $isSaved && $existedSchedule->save();
        }
        return $isSaved;
    }

    /**
     * Load schedules
     *
     * @param integer $expense_type_id
     *
     * @return mixed
     */
    public static function getExpenseTypeSchedulesById($expense_type_id)
    {
        $expenceType = self::find()->where(['id' => $expense_type_id])->with(['expenseTypeSchedules'])->one();
        $schedules   = ExpenseTypeSchedule::getEmptySchedule();

        if (count($expenceType->expenseTypeSchedules) > 0) {
            $existedSchedules = $expenceType->expenseTypeSchedules;

            foreach ($schedules as $index => $schedule) {
                $existedSchedule = \admin\common\ArrayHelper::searchRowInArArray($existedSchedules, [ 'day' => $schedule->day, 'day_time_type' => $schedule->day_time_type ]);
                if ($existedSchedule !== false) {
                    $schedules[$index] = $existedSchedule;
                }
            }
        }

        return $schedules;
    }
}
