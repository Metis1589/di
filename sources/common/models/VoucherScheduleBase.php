<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voucher_schedule".
 *
 * @property string $id
 * @property string $voucher_id
 * @property string $from
 * @property string $to
 * @property string $day
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Voucher $voucher
 */
class VoucherScheduleBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher_id', 'day'], 'required'],
            [['voucher_id'], 'integer'],
            [['from', 'to', 'create_on', 'last_update'], 'safe'],
            [['day', 'record_type'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'voucher_id' => Yii::t('app', 'Voucher ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'day' => Yii::t('app', 'Day'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['id' => 'voucher_id']);
    }
}
