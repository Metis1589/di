<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voucher_user".
 *
 * @property string $id
 * @property string $emark_type
 * @property string $promo_used
 * @property string $start_date
 * @property string $end_date
 * @property integer $template_type
 * @property string $applicable_offer
 * @property string $user_id
 * @property string $voucher_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property User $user
 * @property Voucher $voucher
 */
class VoucherUser extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emark_type', 'promo_used', 'record_type'], 'string'],
            [['start_date', 'end_date', 'template_type', 'applicable_offer', 'user_id', 'voucher_id'], 'required'],
            [['start_date', 'end_date', 'create_on', 'last_update'], 'safe'],
            [['template_type', 'user_id', 'voucher_id'], 'integer'],
            [['applicable_offer'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'emark_type' => Yii::t('app', 'Emark Type'),
            'promo_used' => Yii::t('app', 'Promo Used'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'template_type' => Yii::t('app', 'Template Type'),
            'applicable_offer' => Yii::t('app', 'Applicable Offer'),
            'user_id' => Yii::t('app', 'User ID'),
            'voucher_id' => Yii::t('app', 'Voucher ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['id' => 'voucher_id']);
    }
}
