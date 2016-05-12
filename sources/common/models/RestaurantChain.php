<?php

namespace common\models;

use Yii;
use common\enums;
use common\enums\RecordType;

/**
 * This is the model class for table "restaurant_chain".
 *
 * @property string $id
 * @property string $name_key
 * @property integer $client_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 * @property RestaurantChainUser[] $restaurantChainUsers
 * @property User[] $users
 * @property RestaurantGroup[] $restaurantGroups
 * @property VoucherRestaurantChain[] $voucherRestaurantChains
 * @property Voucher[] $vouchers
 */
class RestaurantChain extends \common\models\BaseModel
{
    public $client_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_chain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_type'], 'required', 'message' => Yii::t('error', 'Record Type is missing')],
            [['name_key'], 'required', 'message' => Yii::t('error', 'Name is missing')],
            [['client_id'], 'required', 'message' => Yii::t('error', 'Client is missing')],
            [['create_on', 'last_update'], 'safe'],
            [['name_key'], 'string', 'max' => 190],
            ['client_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Client', 'targetAttribute' => 'id', 'message' => Yii::t('label', 'Invalid client')],
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
            'client_id' => Yii::t('label', 'Client ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    public function translatedProperties() {
        return ['name_key'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantChainUsers()
    {
        return $this->hasMany(RestaurantChainUser::className(), ['restaurant_chain_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('restaurant_chain_user', ['restaurant_chain_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroups()
    {
        return $this->hasMany(RestaurantGroup::className(), ['restaurant_chain_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherRestaurantChains()
    {
        return $this->hasMany(VoucherRestaurantChain::className(), ['restaurant_chain_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['id' => 'voucher_id'])->viaTable('voucher_restaurant_chain', ['restaurant_chain_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['code' => 'name_key']);
    }

    public static function getActive()
    {
        return self::findAll(['record_type' => 'Active']);
    }
    
    public static function getChainsForSelect()
    {
        $chain = yii\helpers\ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name_key');
        foreach($chain as $key=>$val)
        {
            $chain[$key] = Yii::$app->globalCache->getLabel($val);
        };
        return $chain;
    }

    public static function getHierarchyForSelect($client_id = null)
    {
        $items = [];
        $options =[];
        $condition = "record_type <> '". RecordType::Deleted . "'";
        if (isset($client_id)) {
            $condition .= ' and client_id ='.$client_id;
        }
        $chains = self::find()->where($condition)->all();
        foreach ($chains as $chain) {
            $groups = RestaurantGroup::getTreeAsArray($chain->id);
            $child_items = [];
            foreach ($groups as $group) {
                $child_items[$group['id']] = Yii::$app->globalCache->getLabel($group['name_key']);
                $options[$group['id']] = ['style' => 'padding-left:'.($group['level'] * 15) .'px;'];
            }
            $name = Yii::$app->globalCache->getLabel($chain->name_key);
            if (count($child_items) > 0) {
                $items[$name] = $child_items;
            }

        }
        return ['options' => $options, 'items' => $items];
    }

    /**
     * @param $restaurant_chain_id
     * @param $restaurant_group_id
     * @param $result
     */
    private static function getTreeNode($restaurant_chain_id, $restaurant_group_id, &$result)
    {
        $groups = RestaurantGroup::find()
            ->where(
                [
                    'restaurant_chain_id' => $restaurant_chain_id,
                    'parent_id' => $restaurant_group_id])
            ->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')
            ->orderBy('name_key')->asArray()->all();

        foreach ($groups as &$group) {

            $group['groups'] = [];

            RestaurantChain::getTreeNode($restaurant_chain_id, $group['id'], $group['groups']);

            $result[] = $group;
        }
    }

    /**
     * get options tree as sorted table
     * @param $client_id
     * @return array
     */
    public static function getTree($client_id = null) {
        $query = RestaurantChain::find()->where('record_type != "'.\common\enums\RecordType::Deleted.'"');
        if (isset($client_id))
        {
            $query->andWhere(['client_id' => $client_id]);
        }
        $chains = $query->orderBy('name_key')->asArray()->all();

        foreach ($chains as &$chain) {

            $chain['groups'] = [];

            RestaurantChain::getTreeNode($chain['id'], null, $chain['groups']);
        }

        return $chains;
    }


    public static function getDeliveryService($restaurant_chain_id) {
        return RestaurantDelivery::find()->joinWith(['restaurantDeliveryCharges'])->where(['restaurant_chain_id' => $restaurant_chain_id])->andWhere(['restaurant_delivery.record_type' => RecordType::Active])->one();
    }

    /**
     * get assigned delivery service
     */
    public static function getAssignedDeliveryService($restaurant_chain_id) {

        $restaurantChain = RestaurantChain::findOne($restaurant_chain_id);

        $delivery = static::getDeliveryService($restaurant_chain_id);

        if (isset($delivery)) {
            return $delivery;
        }

        // load assigned to client
        return Client::getDeliveryService($restaurantChain->client_id);

    }
   
}
