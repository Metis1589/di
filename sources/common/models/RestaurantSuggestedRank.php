<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_suggested_rank".
 *
 * @property string $id
 * @property integer $ranking
 * @property integer $restaurant_suggested_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property RestaurantSuggested $restaurantSuggested
 */
class RestaurantSuggestedRank extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_suggested_rank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ranking', 'restaurant_suggested_id'], 'integer'],
            [['restaurant_suggested_id'], 'required'],
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
            'ranking' => Yii::t('app', 'Ranking'),
            'restaurant_suggested_id' => Yii::t('app', 'Restaurant Suggested ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantSuggested()
    {
        return $this->hasOne(RestaurantSuggested::className(), ['id' => 'restaurant_suggested_id']);
    }
}
