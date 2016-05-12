<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/23/2015
 * Time: 9:53 PM
 */

namespace common\models;


use common\components\language\T;
use common\enums\Day;
use common\enums\RecordType;
use Yii;

class VoucherSchedule extends VoucherScheduleBase {
    
    public $from_label;
    public $day_label;

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
            $schedule       = new VoucherSchedule();
            $schedule->day  = $day;
            $schedule->populateAdditionalAttributes();
            $schedules[] = $schedule;
        }
        return $schedules;
    }

    public static function getVoucherSchedulesById($id) {
        $voucherSchedules = static::find()->where(['voucher_id' => $id])->andWhere(['<>','record_type', RecordType::Deleted])->all();
        $schedules = static::getEmptySchedule();
        if (count($voucherSchedules) > 0) {
            $existedSchedules = $voucherSchedules;
            foreach($schedules as $index => $schedule) {
                $existedSchedule = \admin\common\ArrayHelper::searchRowInArArray($existedSchedules, ['day' => $schedule->day]);
                if ($existedSchedule !== false) {
                    $schedules[$index] = $existedSchedule;
                }
            }
        }
        return $schedules;
    }

    public static function saveSchedules($postedSchedules, $id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isSaved     = true;

        try {
            foreach($postedSchedules as $postedSchedule) {
                $existedSchedule = static::findOne($postedSchedule['id']);
                if ($existedSchedule == null) {
                    $existedSchedule = new VoucherSchedule();
                }
                $existedSchedule->load($postedSchedule,'');
                if (empty($existedSchedule->from) && empty($existedSchedule->to)) {
                    if (!$existedSchedule->isNewRecord) {
                        $existedSchedule->record_type = RecordType::Deleted;
                        $isSaved = $isSaved && $existedSchedule->save();
                    }
                    continue;
                }

                $existedSchedule->voucher_id = $id;
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
        $this->from_label = T::l($this->day. ' ' . ' from');
        $this->day_label  = Day::getLabels()[$this->day];
    }
}