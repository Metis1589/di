<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_cuisine".
 *
 * @property string $id
 * @property integer $restaurant_id
 * @property string $cuisine_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Cuisine $cuisine
 * @property Restaurant $restaurant
 */
class RestaurantCuisine extends \yii\db\ActiveRecord
{
    public $restaurant_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_cuisine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'cuisine_id'], 'required'],
            [['restaurant_id', 'cuisine_id'], 'integer'],
            ['restaurant_id', 'unique', 'targetAttribute' => ['restaurant_id','cuisine_id'], 'filter' => "record_type <> 'Deleted'", 'attributes' => ['restaurant_id', 'cuisine_id'],  'message' => Yii::t('error', 'FIELDS ARE NOT UNIQUE') ],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            ['restaurant_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Restaurant', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid restaurant')],
            ['cuisine_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Cuisine', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid cuisine')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'restaurant_id' => Yii::t('label', 'Restaurant ID'),
            'cuisine_id' => Yii::t('label', 'Cuisine ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCuisine()
    {
        return $this->hasOne(Cuisine::className(), ['id' => 'cuisine_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    public static function saveByPost($postedItem)
    {
        $restaurant_id = $postedItem['restaurant_id'];
        $cuisine_id = $postedItem['cuisine_id'];
        $existedRelation = static::findOne(['restaurant_id' => $restaurant_id, 'cuisine_id' => $cuisine_id]);
        if (!isset($existedRelation)) {
            $existedRelation = new RestaurantCuisine();
            $existedRelation->load($postedItem,'');
        }
        $existedRelation->record_type = $postedItem['record_type'];
        if ($existedRelation->save()) {
            return true;
        }
        return false;
    }
}
