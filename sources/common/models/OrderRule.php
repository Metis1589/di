<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/25/2015
 * Time: 10:25 PM
 */

namespace common\models;


use Yii;

class OrderRule extends OrderRuleBase {


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'custom_field_id' => Yii::t('app', 'Custom Field'),
            'delivery_type' => Yii::t('app', 'Delivery Type'),
            'value' => Yii::t('app', 'Value'),
            'message_key' => Yii::t('app', 'Message'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }
}