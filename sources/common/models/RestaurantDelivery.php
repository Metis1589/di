<?php

namespace common\models;

use common\components\language\T;
use common\enums\RestaurantDeliveryRateType;
use Yii;
use common\enums\RecordType;

/**
 * This is the model class for table "restaurant_delivery".
 *
 * @property integer $id
 * @property integer $client_id
 * @property integer $restaurant_group_id
 * @property integer $restaurant_chain_id
 * @property integer $restaurant_id
 * @property string $driver_instructions
 * @property string $driving_instructions
 * @property boolean $has_collection
 * @property boolean $has_dinein
 * @property boolean $has_own
 * @property double $range
 * @property double $fixed_charge
 * @property double $collect_time_in_min
 * @property string $rate_type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 * @property RestaurantDeliveryCharges[] $restaurantDeliveryCharges
 */
class RestaurantDelivery extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['restaurant_id', 'common\validators\CustomUniqueValidator', 'message' => T::e('Restaurant has delivery model')],
//            ['driver_instructions', 'required', 'message' => T::e('Driver instructions is missing')],
//            ['driving_instructions', 'required', 'message' => T::e('Driver instructions is missing')],
            ['range', 'required', 'message' => T::e('Max range is missing')],
            ['range', 'number', 'min' => 0, 'max' => 100000, 'message' => T::e('Invalid Max Range'), 'tooBig' => T::e('Max Range is too big'), 'tooSmall' => T::e('Max Range is too small')],

            ['rate_type', 'required', 'message' => T::e('Rate Type is missing'), 'when' => function($model) {
                return $model->has_own || $model->has_dinein;
            }],

            ['fixed_charge', 'required', 'message' => T::e('Fixed Charge is missing'), 'when' => function($model) {
                return $model->rate_type == RestaurantDeliveryRateType::Fixed;
            }],

            ['fixed_charge', 'number', 'min' => 0, 'max' => 100000, 'message' => T::e('Invalid Fixed Charge'), 'tooBig' => T::e('Fixed Charge is too big'), 'tooSmall' => T::e('Fixed Charge is too small'), 'when' => function($model) {
                return $model->rate_type == RestaurantDeliveryRateType::Fixed;
            }],
            
            ['collect_time_in_min', 'required', 'message' => T::e('Collect Time is missing'), 'when' => function($model) {
                return $model->has_collection;
            }],

            ['collect_time_in_min', 'integer', 'min' => 0, 'max' => 100000, 'message' => T::e('Invalid collect time'), 'tooBig' => T::e('Collect time is too big'), 'tooSmall' => T::e('Collect time is too small'), 'when' => function($model) {
                return $model->has_collection;
            }],

            [['restaurant_id'], 'integer'],
            [['has_collection','has_dinein','has_own'], 'boolean'],
            [['record_type','rate_type'], 'string'],
            
            [['create_on', 'last_update'], 'safe'],
            [['driver_instructions','driving_instructions'], 'safe'],

            ['restaurant_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_group_id) && !isset($model->restaurant_chain_id) && !isset($model->client_id);
            }],

            ['restaurant_group_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_id) && !isset($model->restaurant_chain_id) && !isset($model->client_id);
            }],

            ['restaurant_chain_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_group_id) && !isset($model->restaurant_id) && !isset($model->client_id);
            }],

            ['client_id', 'required', 'when' => function($model) {
                return !isset($model->restaurant_group_id) && !isset($model->restaurant_chain_id) && !isset($model->restaurant_id);
            }]
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true){
        parent::validate($attributeNames, $clearErrors);
        if (count($this->restaurantDeliveryCharges) == 0 && $this->rate_type == RestaurantDeliveryRateType::Float) {
            $this->addError('',T::e('Charges are missing'));
        }
        return !$this->hasErrors();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'restaurant_id' => T::l('Restaurant'),
            'driver_instructions' =>  T::l('Driver Instructions'),
            'driving_instructions' =>  T::l('Driving Instructions'),
            'has_collection' =>  T::l('Has Collection Delivery'),
            'has_dinein' =>  T::l('Has Dinein Delivery'),
            'has_own' =>  T::l('Has Own Delivery'),
            'range' =>  T::l('Max Range'),
            'fixed_charge' =>  T::l('Fixed Charge'),
            'rate_type' =>  T::l('Rate Type'),
            'collect_time_in_min' => T::l('Collect Time In Min'),
            'record_type' => T::l('Record Type'),
            'create_on' => T::l('Create On'),
            'last_update' =>  T::l('Last Update'),
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
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
//    public function afterSave($insert, $changedAttributes){
//        parent::afterSave($insert, $changedAttributes);
//        $client_key = null;
//        if (isset($this->client_id)) {
//            $client_key = $this->client->key;
//        } else if (isset($this->restaurant_chain_id)) {
//            $client_key = $this->restaurantChain->client->key;
//        } else if (isset($this->restaurant_group_id)) {
//            $client_key = $this->restaurantGroup->restaurantChain->client->key;
//        } else if (isset($this->restaurant_id)) {
//            $client_key = $this->restaurant->client->key;
//        } else {
//            throw new Exception('Client was not found');
//        }
//        Yii::$app->globalCache->loadRestaurantsByClient(Yii::$app->globalCache->getClient($client_key));
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryCharges()
    {
        return $this->hasMany(RestaurantDeliveryCharge::className(), ['restaurant_delivery_id' => 'id'])->andOnCondition(['restaurant_delivery_charge.record_type' => RecordType::Active]);
    }

    public function saveByPost($charges) {
        if (Yii::$app->request->isPost) {
            $charges_copy = $charges;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $charges = $this->mergeChargesByPost($charges);
                $this->populateRelation('restaurantDeliveryCharges', $charges);
                $this->refreshAssingment();
                $isSaved = $this->save();

                foreach($this->restaurantDeliveryCharges as $key => $charge) {
                    $charge->restaurant_delivery_id = $this->id;
                    $isSaved = $isSaved && $charge->save();
                    $this->restaurantDeliveryCharges[$key]->refresh();

                    $fields = CustomField::getKeyValues(
                        Yii::$app->request->getImpersonatedClientId(),
                        null,
                        null,
                        $charge->id,
                        true
                    );

                    foreach ($fields as $field_key => $value) {

                        if (!array_key_exists('custom_fields', $charges_copy[$key])) {
                            continue;
                        }

                        $fields[$field_key]->customFieldValue->value = $charges_copy[$key]['custom_fields'][$field_key];
                        if ($fields[$field_key]->customFieldValue->value == null) {
                            $fields[$field_key]->customFieldValue->value = '';
                        }
                        $isSaved = $isSaved && $fields[$field_key]->customFieldValue->save();
                    }
                }
                if ($isSaved) {

                    $transaction->commit();
                    return true;

                } else {
                    $transaction->rollBack();
                    return false;
                }
                $this->refresh();
            }
            catch(Exception $ex){
                $transaction->rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    public function mergeChargesByPost($postCharges) {
        $existedCharges = [];
        foreach($postCharges as $charge) {
            $existedCharge = RestaurantDeliveryCharge::findOne($charge['id']);
            if ($existedCharge == null) {
                $existedCharge = new RestaurantDeliveryCharge();
            }
            $existedCharge->load($charge,'');
            if ($existedCharge->isNewRecord && empty($existedCharge->distance_in_miles) && empty($existedCharge->charge)) {
                continue;
            }

            array_push($existedCharges, $existedCharge);
        }
        return $existedCharges;
    }

    private function refreshAssingment() {
        if (isset($this->restaurant_id)) {
            $this->restaurant_group_id = null;
            $this->restaurant_chain_id = null;
            $this->client_id = null;
        } else if (isset($this->restaurant_group_id)) {
            $this->restaurant_id = null;
            $this->restaurant_chain_id = null;
            $this->client_id = null;
        } else if (isset($this->restaurant_chain_id)) {
            $this->restaurant_id = null;
            $this->restaurant_group_id = null;
            $this->client_id = null;
        } else if (isset($this->client_id)) {
            $this->restaurant_id = null;
            $this->restaurant_group_id = null;
            $this->restaurant_chain_id = null;
        }
    }

}

