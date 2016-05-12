<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voucher_restaurant_group".
 *
 * @property string $voucher_id
 * @property string $restaurant_group_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Voucher $voucher
 * @property RestaurantGroup $restaurantGroup
 */
class VoucherRestaurantGroup extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher_restaurant_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher_id', 'restaurant_group_id'], 'required'],
            [['voucher_id', 'restaurant_group_id'], 'integer'],
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
            'voucher_id' => Yii::t('app', 'Voucher ID'),
            'restaurant_group_id' => Yii::t('app', 'Restaurant Group ID'),
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
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }
}
