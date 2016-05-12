<?php

namespace common\models;

use Yii;
use common\enums\RecordType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cuisine".
 *
 * @property string $id
 * @property string $name_key
 * @property string $seo_name
 * @property string $description_key
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property RestaurantCuisine[] $restaurantCuisines
 * @property Restaurant[] $restaurants
 */
class Cuisine extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cuisine';
    }

    /**
     * @inheritdoc
     */
            
    public function rules()
    {
        return [
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['seo_name'], 'required', 'message' => Yii::t('error', 'Seo Name is missing')],
            [['description_key'], 'string', 'max' => 190, 'message' => Yii::t('error', 'Description is invalid')],
            [['seo_name'], 'string', 'max' => 255, 'message' => Yii::t('error', 'Seo Name is invalid')],
            [['name_key'], 'string', 'max' => 190, 'message' => Yii::t('error', 'Name is invalid')],
            [['record_type'], 'required', 'message' => Yii::t('error', 'Record Type is missing')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'name_key' => Yii::t('label', 'Name'),
            'seo_name' => Yii::t('label', 'Seo Name'),
            'description_key' => Yii::t('label', 'Description'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    public function translatedProperties() {
        return ['name_key', 'description_key'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantCuisines()
    {
        return $this->hasMany(RestaurantCuisine::className(), ['cuisine_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['id' => 'restaurant_id'])->viaTable('restaurant_cuisine', ['cuisine_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['code' => 'name_key']);
    }
    
    public static function getCuisinesForSelect()
    {
        $cuisines = ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name_key');
        foreach($cuisines as $key=>$val)
        {
            $cuisines[$key] = Yii::$app->globalCache->getLabel($val);
        };
        return $cuisines;
    }

}
