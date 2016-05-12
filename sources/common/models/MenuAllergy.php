<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_allergy".
 *
 * @property integer $id
 * @property string $name_key
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItem[] $menuItems
 */
class MenuAllergy extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_allergy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name_key'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_key' => Yii::t('app', 'Name Key'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_allergy_id' => 'id']);
    }
}
