<?php

namespace common\models;

use common\components\language\T;
use common\enums\Day;
use common\enums\DayTimeType;
use common\enums\RecordType;
use common\enums\RestaurantScheduleType;
use Yii;


/**
 * This is the model class for table "restaurant_schedule".
 *
 * @property integer $client_id
 * @property integer $restaurant_group_id
 * @property integer $restaurant_chain_id
 * @property integer $restaurant_id
 * @property string $from
 * @property string $to
 * @property string $type
 * @property string $day_time_type
 * @property string $day
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 */
class RestaurantSchedule extends \common\models\BaseModel
{
    public $from_label;
    public $day_label;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['restaurant_id',  'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Restaurant', 'targetAttribute' => 'id', 'message' => T::l('Invalid restaurant')],
            ['type',           'required', 'message' => T::e('Schedule Type is missing')],
            ['day',            'required', 'message' => T::e('Day is missing')],
            ['day_time_type',  'required', 'message' => T::e('Day Time Type is missing')],
            ['restaurant_id',  'integer'],
            [['type', 'day',], 'string'],
            [['create_on', 'last_update', 'from', 'to', 'record_type'], 'safe'],

            ['restaurant_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_group_id) && !isset($model->restaurant_chain_id) && !isset($model->client_id);
            }],

            ['restaurant_group_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_id) && !isset($model->restaurant_chain_id) && !isset($model->client_id);
            }],

            ['restaurant_chain_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_group_id) && !isset($model->restaurant_id) && !isset($model->client_id);
            }],

            ['client_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_group_id) && !isset($model->restaurant_chain_id) && !isset($model->restaurant_id);
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'restaurant_id' => T::l('Restaurant ID'),
            'from'          => T::l('From'),
            'to'            => T::l('To'),
            'type'          => T::l('Type'),
            'day'           => T::l('Day'),
            'record_type'   => T::l('Record Type'),
            'create_on'     => T::l('Create On'),
            'last_update'   => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }

    public function afterFind() {
        parent::afterFind();
        $this->populateAdditionalAttributes();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->populateAdditionalAttributes();
    }

    public function getAttributes($names = null, $except = [])
    {
        $attr = parent::getAttributes($names, $except);
        $attr['from_label'] = $this->from_label;
        $attr['day_label']  = $this->day_label;
        return $attr;
    }

    public static function getEmptySchedule()
    {
        $schedules = [];
        foreach (Day::values() as $day) {
            foreach (DayTimeType::values() as $dayTimeType) {
                foreach (RestaurantScheduleType::values() as $type) {
                    $schedule       = new RestaurantSchedule();
                    $schedule->type = $type;
                    $schedule->day  = $day;
                    $schedule->day_time_type = $dayTimeType;
                    $schedule->populateAdditionalAttributes();
                    $schedules[] = $schedule;
                }
            }
        }
        return $schedules;
    }

    public static function getRestaurantSchedulesById($id, $model) {
        $restaurantSchedules = RestaurantSchedule::find()->where([$model.'_id' => $id])->andWhere(['<>','record_type', RecordType::Deleted])->all();
        $schedules = RestaurantSchedule::getEmptySchedule();
        if (count($restaurantSchedules) > 0) {
            $existedSchedules = $restaurantSchedules;
            foreach($schedules as $index => $schedule) {
                $existedSchedule = \admin\common\ArrayHelper::searchRowInArArray($existedSchedules, ['type' => $schedule->type, 'day' => $schedule->day, 'day_time_type' => $schedule->day_time_type]);
                if ($existedSchedule !== false) {
                    $schedules[$index] = $existedSchedule;
                }
            }
        }
        return $schedules;
    }

    public static function saveSchedules($postedSchedules, $id, $assigment)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isSaved     = true;
        $assigment .= '_id';

        try {
            foreach($postedSchedules as $postedSchedule) {
                $existedSchedule = static::findOne($postedSchedule['id']);
                if ($existedSchedule == null) {
                    $existedSchedule = new RestaurantSchedule();
                }
                $existedSchedule->load($postedSchedule,'');
                if (empty($existedSchedule->from) && empty($existedSchedule->to)) {
                    if (!$existedSchedule->isNewRecord) {
                        $existedSchedule->record_type = RecordType::Deleted;
                        $isSaved = $isSaved && $existedSchedule->save();
                    }
                    continue;
                }

                $existedSchedule->$assigment = $id;
                $existedSchedule->refreshAssingment();
                $isSaved = $isSaved && $existedSchedule->save();
            }

            if ($isSaved) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        return $isSaved;
    }

    private function populateAdditionalAttributes()
    {
        $this->from_label = T::l($this->day_time_type. ' ' . $this->type . ' from');
        $this->day_label  = Day::getLabels()[$this->day];
    }

    public function refreshAssingment() {
        if (isset($this->restaurant_id)) {
            $this->restaurant_group_id = null;
            $this->restaurant_chain_id = null;
            $this->client_id = null;
        } else if (isset($this->restaurant_group_id)) {
            $this->restaurant_id = null;
            $this->restaurant_chain_id = null;
            $this->client_id = null;
        } else if (isset($this->restaurant_chain_id)) {
            $this->restaurant_id = null;
            $this->restaurant_group_id = null;
            $this->client_id = null;
        } else if (isset($this->client_id)) {
            $this->restaurant_id = null;
            $this->restaurant_group_id = null;
            $this->restaurant_chain_id = null;
        }
    }
}

