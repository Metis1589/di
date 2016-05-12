<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/17/2015
 * Time: 12:22 AM
 */

namespace common\models;


class CustomFieldValue extends CustomFieldValueBase {

    public function rules()
    {
        return [
            [['custom_field_id'], 'required'],
            [['id', 'custom_field_id', 'restaurant_id', 'menu_item_id', 'restaurant_delivery_charge_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['value'], 'string', 'max' => 255]
        ];
    }


}