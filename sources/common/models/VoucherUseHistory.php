<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voucher_use_history".
 *
 * @property string $id
 * @property string $voucher_id
 * @property string $user_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Voucher $voucher
 * @property User $user
 */
class VoucherUseHistory extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher_use_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher_id'], 'required'],
            [['voucher_id', 'user_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
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
            'user_id' => Yii::t('app', 'User ID'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
