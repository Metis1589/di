<?php

namespace common\models;

use common\enums\RestaurantPaymentType;
use Yii;

/**
 * This is the model class for table "restaurant_payment".
 *
 * @property integer $id
 * @property string $type
 * @property string $account_holder_name
 * @property string $bank_name
 * @property string $sort_code
 * @property string $account_number
 * @property integer $restaurant_id
 * @property double $sales_fee_value
 * @property string $sales_fee_type
 * @property string $sales_charge_type
 * @property double $commission_fee_value
 * @property string $commission_fee_type
 * @property string $commission_charge_type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 */
class RestaurantPayment extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_holder_name', 'bank_name', 'sort_code', 'account_number'], 'required', 'when' => function($model){
                return $model->type == RestaurantPaymentType::Bank;
            }],
            [['type', 'restaurant_id', 'sales_fee_value', 'sales_fee_type', 'sales_charge_type', 'collection_fee_value', 'collection_fee_type', 'collection_charge_type'], 'required'],
            [['id', 'restaurant_id'], 'integer'],
            [['type', 'sales_fee_type', 'sales_charge_type','collection_fee_type', 'collection_charge_type', 'record_type'], 'string'],
            [['sales_fee_value', 'collection_fee_value'], 'number'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    public static function saveByPost($postedPayment, $restautant_id)
    {
        $existedPayment = null;
        if (isset($postedPayment['id'])) {
            $existedPayment = static::findOne($postedPayment['id']);
        }

        if ($existedPayment == null) {
            $existedPayment = new RestaurantPayment();
            unset($postedPayment['id']);
        }
        $existedPayment->load($postedPayment,'');
        $existedPayment->restaurant_id = $restautant_id;
        if ($existedPayment->save()) {

            return $existedPayment;
        }
        return false;
    }


}
