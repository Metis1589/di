<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/17/2015
 * Time: 12:21 AM
 */

namespace common\models;


use common\enums\CustomFieldType;
use common\enums\RecordType;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class CustomField extends CustomFieldBase {

    /**
     * @var CustomFieldValue
     */
    public $customFieldValue;

    /**
     * @param $client_id
     * @param null $restaurant_id
     * @param null $menu_item_id
     * @param null $delivery_charge_id
     * @param bool $load_default_value
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getKeyValues($client_id, $restaurant_id = null, $menu_item_id = null, $delivery_charge_id = null, $load_default_value = true) {

        $type = CustomFieldType::Client;

        if (isset($restaurant_id)) {
            $type = CustomFieldType::Restaurant;
        }
        else if (isset($menu_item_id)) {
            $type = CustomFieldType::MenuItem;
        }
        else if (isset($delivery_charge_id)) {
            $type = CustomFieldType::DeliveryCharge;
        }

        $fields = CustomField::find()
            ->indexBy('key')
            ->where(['client_id' => $client_id, 'custom_field.record_type' => RecordType::Active, 'custom_field.type' => $type])
            ->joinWith(
                [
                    'customFieldValues' => function (ActiveQuery $q) use ($restaurant_id, $menu_item_id, $delivery_charge_id) {
                        $q->onCondition(
                            [
                                'restaurant_id' => $restaurant_id,
                                'menu_item_id' => $menu_item_id,
                                'restaurant_delivery_charge_id' => $delivery_charge_id,
                                'custom_field_value.record_type' => RecordType::Active
                            ]);
                    }
                ]
            )->all();

        /** @var CustomField $field */
        foreach ($fields as &$field) {

            if (count($field->customFieldValues) == 0) {
                $value = new CustomFieldValue();
                $value->custom_field_id = $field->id;
                $value->restaurant_id = $restaurant_id;
                $value->menu_item_id = $menu_item_id;
                $value->restaurant_delivery_charge_id = $delivery_charge_id;

                if ($load_default_value) {
                    $value->value = $field->default_value;
                }

                $field->customFieldValue = $value;
            }
            else {
                $field->customFieldValue = $field->customFieldValues[0];
            }
        }

        return $fields;
    }

    /**
     * @param $client_id
     * @param null $restaurant_id
     * @param null $menu_item_id
     * @param null $delivery_charge_id
     * @param bool $load_default_value
     * @return array
     */
    public static function getKeyValuesArray($client_id, $restaurant_id = null, $menu_item_id = null, $delivery_charge_id = null, $load_default_value = true) {

        $fields = static::getKeyValues($client_id, $restaurant_id, $menu_item_id, $delivery_charge_id, $load_default_value);

        $result = [];

        /** @var CustomField $field */
        foreach ($fields as $field) {
            $result[$field->key] = $field->customFieldValue->value;
        }

        return $result;
    }

    public static function getForDropDown($type) {
        $result = CustomField::find()
            ->where(
                [
                    'client_id' => Yii::$app->request->getImpersonatedClientId(),
                    'type' => $type,
                ])
            ->asArray()
            ->all();

        return ArrayHelper::map($result, 'id', 'key');
    }
}