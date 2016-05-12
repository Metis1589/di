<?php

namespace common\models;

use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "restaurant_best_for_item".
 *
 * @property integer $restaurant_id
 * @property string $best_for_item_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 * @property BestForItem $bestForItem
 */
class RestaurantBestForItem extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_best_for_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'best_for_item_id', 'record_type'], 'required'],
            [['restaurant_id', 'best_for_item_id'], 'integer'],
            [['restaurant_id'], 'unique', 'targetAttribute' => ['restaurant_id', 'best_for_item_id'], 'filter' => "record_type <> '".RecordType::Deleted."'", 'message' => Yii::t('label', 'This combination has been already taken')],
            ['best_for_item_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\BestForItem', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid Best For')],
            ['restaurant_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Restaurant', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid restaurant')],
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
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
            'best_for_item_id' => Yii::t('app', 'Best For Item ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBestForItem()
    {
        return $this->hasOne(BestForItem::className(), ['id' => 'best_for_item_id']);
    }

    public static function saveByPost($postedItem)
    {
        $restaurant_id = $postedItem['restaurant_id'];
        $best_item_id = $postedItem['best_for_item_id'];
        $existedRelation = static::findOne(['restaurant_id' => $restaurant_id, 'best_for_item_id' => $best_item_id]);
        if (!isset($existedRelation)) {
            $existedRelation = new RestaurantBestForItem();
            $existedRelation->load($postedItem,'');
        }
        $existedRelation->record_type = $postedItem['record_type'];
        return $existedRelation->save();
    }
}
