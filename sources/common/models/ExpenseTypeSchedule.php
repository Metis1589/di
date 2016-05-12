<?php

namespace common\models;

use common\components\language\T;

/**
 * This is the model class for table "expense_type_schedule".
 *
 * @property string $id
 * @property string $day
 * @property integer $schedule_id
 * @property string $expense_type_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Schedule $schedule
 * @property ExpenseType $expenseType
 */
class ExpenseTypeSchedule extends \common\models\BaseModel
{
    public $from_label;
    public $day_label;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expense_type_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['day',                  'required', 'message' => T::e('Day is missing')],
            ['expense_type_id',      'required', 'message' => T::e('Expense type is missing')],
            ['record_type',          'required', 'message' => T::e('Record Type is missing')],
            [['day', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => T::l('ID'),
            'day'             => T::l('Day'),
            'expense_type_id' => T::l('Expense Type ID'),
            'record_type'     => T::l('Record Type'),
            'create_on'       => T::l('Create On'),
            'last_update'     => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseType()
    {
        return $this->hasOne(ExpenseType::className(), ['id' => 'expense_type_id']);
    }

    public static function getEmptySchedule()
    {
        $schedules = [];
        foreach (\common\enums\Day::values() as $day) {
            foreach (\common\enums\DayTimeType::values() as $dayTimeType) {
                $schedule                = new ExpenseTypeSchedule;
                $schedule->day           = $day;
                $schedule->day_time_type = $dayTimeType;
                $schedule->populateAdditionalAttributes();
                $schedules[] = $schedule;
            }
        }

        return $schedules;
    }

    private function populateAdditionalAttributes()
    {
        $this->from_label = T::l($this->day_time_type. 'delivery from');
        $this->day_label  = \common\enums\Day::getLabels()[$this->day];
    }
}
