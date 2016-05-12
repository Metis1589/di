<?php

namespace common\models;

use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "restaurant_group".
 *
 * @property string $id
 * @property string $restaurant_chain_id
 * @property string $parent_id
 * @property string $currency_id
 * @property string $name_key
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuAssignment[] $menuAssignments
 * @property Currency $currency
 * @property RestaurantGroup $parent
 * @property RestaurantGroup[] $restaurantGroups
 * @property User[] $users
 */
class RestaurantGroup extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_chain_id', 'name_key'], 'required'],
            [['restaurant_chain_id', 'parent_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name_key'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'restaurant_chain_id' => Yii::t('label', 'Restaurant Chain ID'),
            'parent_id' => Yii::t('label', 'Parent ID'),
            'currency_id' => Yii::t('label', 'Currency ID'),
            'name_key' => Yii::t('label', 'Name Key'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuAssignments()
    {
        return $this->hasMany(MenuAssignment::className(), ['restaurant_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroups()
    {
        return $this->hasMany(RestaurantGroup::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['restaurant_group_id' => 'id']);
    }
    
    private static function getTreeNode($restaurant_chain_id, $restaurant_group_id, $level, &$result)
    {
        $options = RestaurantGroup::find()
            ->where(
                [
                    'restaurant_chain_id' => $restaurant_chain_id,
                    'parent_id' => $restaurant_group_id])
            ->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')
            ->asArray()->all();

        foreach ($options as $option) {

            $option['level'] = $level + 1;
            $result[] = $option;

            RestaurantGroup::getTreeNode($restaurant_chain_id, $option['id'], $option['level'], $result);
        }
    }

    /**
     * get options tree as sorted table
     * @param $menu_item_id
     * @return array
     */
    public static function getTreeAsArray($restaurant_chain_id) {
        $result = [];
        RestaurantGroup::getTreeNode($restaurant_chain_id, null, 0, $result);

        return $result;
    }
    
    /**
     * @param $menu_item_id
     * @param $options
     * @return boolean
     */
    public static function saveTreeAsArray($restaurant_chain_id, $groups) {

        $mapping = [];

        $transaction = Yii::$app->db->beginTransaction();

        try {

            foreach ($groups as $group) {
                if (array_key_exists('is_new', $group) && $group['is_new'] == true) {

                    // insert new element
                    $previous_id = $group['id'];

                    unset($group['id']);
                    if (array_key_exists($group['parent_id'], $mapping)) {
                        $group['parent_id'] = $mapping[$group['parent_id']];
                    }

                    if (array_key_exists('record_type', $group) && $group['record_type']!='Deleted'){
                        $newGroup = new RestaurantGroup();
                        $newGroup->setAttributes($group);
                        $newGroup->restaurant_chain_id = $restaurant_chain_id;

                        $is_saved = $newGroup->save();

                        $mapping[$previous_id] = $newGroup->id;
                    }
                } else {
                    $newGroup = RestaurantGroup::findOne(['id' => $group['id']]);
                    $newGroup->setAttributes($group);

                    $is_saved = $newGroup->save();
                }

                if (!$is_saved) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $transaction->commit();

        }
        catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }



        return true;
    }

    public static function getDeliveryService($restaurant_group_id) {
        $delivery = RestaurantDelivery::find()->joinWith(['restaurantDeliveryCharges'])->where(['restaurant_group_id' => $restaurant_group_id])->andWhere(['restaurant_delivery.record_type' => RecordType::Active])->one();

        if (isset($delivery)) {
            return $delivery;
        }

        $group = RestaurantGroup::find()->where(['id' => $restaurant_group_id])->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')->one();

        if ($group != null && $group->parent_id != null) {
            return static::getRestaurantDelivery($group->parent_id);
        }

        return null;
    }

    /**
     * get assigned delivery service
     */
    public static function getAssignedDeliveryService($restaurant_group_id) {

        $restaurantGroup = RestaurantGroup::findOne($restaurant_group_id);

        $delivery = static::getDeliveryService($restaurant_group_id);

        if (isset($delivery)) {
            return $delivery;
        }

        return RestaurantChain::getAssignedDeliveryService($restaurantGroup->restaurant_chain_id);
    }
}
