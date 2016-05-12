<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phone".
 *
 * @property string $id
 * @property string $number
 * @property string $type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CompanyPhone[] $companyPhones
 * @property Company[] $companies
 * @property Contact[] $contacts
 * @property OrderPhone[] $orderPhones
 * @property Order[] $orders
 */
class Phone extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number'], 'required'],
            [['type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['number'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'number' => Yii::t('app', 'Number'),
            'type' => Yii::t('app', 'Type'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyPhones()
    {
        return $this->hasMany(CompanyPhone::className(), ['phone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['id' => 'company_id'])->viaTable('company_phone', ['phone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['phone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPhones()
    {
        return $this->hasMany(OrderPhone::className(), ['phone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('order_phone', ['phone_id' => 'id']);
    }
}
