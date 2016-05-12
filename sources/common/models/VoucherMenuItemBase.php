<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voucher_menu_item".
 *
 * @property string $id
 * @property string $voucher_id
 * @property string $menu_item_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Voucher $voucher
 * @property MenuItem $menuItem
 */
class VoucherMenuItemBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher_menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher_id', 'menu_item_id'], 'required'],
            [['voucher_id', 'menu_item_id'], 'integer'],
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
            'menu_item_id' => Yii::t('app', 'Menu Item ID'),
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
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }
}
