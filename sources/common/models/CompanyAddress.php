<?php

namespace common\models;

use Yii;
use common\components\language\T;

/**
 * This is the model class for table "company_address".
 *
 * @property string $id
 * @property string $address_id
 * @property string $company_id
 * @property string $address_type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Address $address
 * @property Company $company
 */
class CompanyAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id', 'company_id', 'address_type'], 'required'],
            [['address_id', 'company_id'], 'integer'],
            [['address_type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => T::l('ID'),
            'address_id'   => T::l('Address ID'),
            'company_id'   => T::l('Company ID'),
            'address_type' => T::l('Address Type'),
            'record_type'  => T::l('Record Type'),
            'create_on'    => T::l('Create On'),
            'last_update'  => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}