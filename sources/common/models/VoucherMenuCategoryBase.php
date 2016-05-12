<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voucher_menu_category".
 *
 * @property string $id
 * @property string $voucher_id
 * @property string $menu_category_id
 * @property string $menu_category_type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuCategory $menuCategory
 * @property Voucher $voucher
 */
class VoucherMenuCategoryBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher_menu_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher_id', 'menu_category_id'], 'required'],
            [['voucher_id', 'menu_category_id'], 'integer'],
            [['menu_category_type', 'record_type'], 'string'],
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
            'menu_category_id' => Yii::t('app', 'Menu Category ID'),
            'menu_category_type' => Yii::t('app', 'Menu Category Type'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategory()
    {
        return $this->hasOne(MenuCategory::className(), ['id' => 'menu_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['id' => 'voucher_id']);
    }
}
