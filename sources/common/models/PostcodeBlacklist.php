<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/19/2015
 * Time: 1:05 PM
 */

namespace common\models;


use Yii;

class PostcodeBlacklist extends PostcodeBlacklistBase {

    public $postcode_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'postcode_id', 'record_type'], 'required'],
            [['client_id', 'postcode_id'], 'integer'],
            [['record_type'], 'string'],
            [['postcode_name'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'client_id' => Yii::t('label', 'Client ID'),
            'postcode_id' => Yii::t('label', 'Postcode ID'),
            'postcode_name' => Yii::t('label', 'Postcode'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

}