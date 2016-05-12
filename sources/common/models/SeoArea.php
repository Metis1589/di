<?php

namespace common\models;

use common\components\language\T;
use common\enums\RecordType;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "seo_area".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $seo_name
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant[] $restaurants
 */
class SeoArea extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => T::l('Name is missing')],
            [['seo_name'], 'required', 'message' => T::l('Seo Name is missing')],
            [['description'], 'required', 'message' => T::l('Description is missing')],
            [['record_type'], 'required', 'message' => T::l('Record Type is missing')],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name'], 'string', 'max' => 255, 'message' => T::l('Name is invalid')],
            [['seo_name'], 'string', 'max' => 255, 'message' => T::l('Seo Name is invalid')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => T::l('Name'),
            'description' => T::l('Description'),
            'seo_name' => T::l('Seo Name'),
            'record_type' => T::l('Record Type'),
            'create_on' => T::l('Create On'),
            'last_update' => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['seo_area_id' => 'id']);
    }
    
    public static function getSeoAreaBaseForSelect()
    {
        return ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name');
    }
}
