<?php

namespace common\models;

use Yii;
use common\enums\RecordType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "best_for_item".
 *
 * @property string $id
 * @property string $name_key
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property RestaurantBestForItem[] $restaurantBestForItems
 * @property Restaurant[] $restaurants
 */
class BestForItem extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'best_for_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
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
            'name_key' => Yii::t('label', 'Name Key'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBestForItems()
    {
        return $this->hasMany(RestaurantBestForItem::className(), ['best_for_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['id' => 'restaurant_id'])->viaTable('restaurant_best_for_item', ['best_for_item_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['code' => 'name_key']);
    }
    
    public static function getBestForItemsForSelect()
    {
        $bestForItems = self::find()->where("record_type <> '".RecordType::Deleted."'")->all();
        
        return ArrayHelper::map(\admin\common\ArrayHelper::translateList($bestForItems), 'id', 'name_key');
    }
}
