<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_suggested".
 *
 * @property integer $id
 * @property string $name
 * @property integer $cuisine_id
 * @property string $addDate
 * @property integer $sugRank
 * @property integer $contact_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Contact $contact
 * @property RestaurantSuggestedRank[] $restaurantSuggestedRanks
 */
class RestaurantSuggested extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_suggested';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cuisine_id', 'sugRank', 'contact_id'], 'integer'],
            [['addDate', 'create_on', 'last_update'], 'safe'],
            [['contact_id'], 'required'],
            [['record_type'], 'string'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'cuisine_id' => Yii::t('app', 'Cuisine ID'),
            'addDate' => Yii::t('app', 'Add Date'),
            'sugRank' => Yii::t('app', 'Sug Rank'),
            'contact_id' => Yii::t('app', 'Contact ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantSuggestedRanks()
    {
        return $this->hasMany(RestaurantSuggestedRank::className(), ['restaurant_suggested_id' => 'id']);
    }
}
