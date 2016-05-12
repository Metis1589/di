<?php

namespace common\models;

use common\enums\DefaultCompanyGroup;
use Yii;
use \common\enums\RecordType;
use \common\components\language\T;

/**
 * This is the model class for table "company".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $payment_frequency
 * @property double $payment_frequency_amount
 * @property double $sales_fee
 * @property boolean $is_vat_exclusive
 * @property double $daily_limit
 * @property double $weekly_limit
 * @property double $monthly_limit
 * @property string $limit_type
 * @property string $vat_number
 * @property string $min_order_morning_time_from
 * @property string $min_order_morning_time_to
 * @property string $min_order_evening_time_from
 * @property string $min_order_evening_time_to
 * @property double $min_order_morning_amount
 * @property double $min_order_evening_amount
 * @property integer $client_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 * @property CompanyAddress[] $companyAddresses
 * @property Address[] $addresses
 * @property CompanyContact[] $companyContacts
 * @property Contact[] $contacts
 * @property CompanyDomain[] $companyDomains
 * @property CompanyPhone[] $companyPhones
 * @property Phone[] $phones
 * @property CompanySchedule[] $companySchedules
 * @property CompanyUserGroup[] $companyUserGroups
 * @property Project[] $projects
 */
class Company extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $MAX_NUM = 1000000000.0;
        return [
            ['name', 'required', 'message' => T::e('Name is missing')],
            ['name', 'string',   'message' => T::e('Name is invalid'), 'max' => 190] ,
            ['name', 'common\validators\CustomUniqueValidator', 'message' => T::l('name is already in use')],

            ['code', 'required', 'message' => T::e('Code is missing')],
            ['code', 'string',   'message' => T::e('Code is invalid'), 'max' => 190] ,

            ['payment_frequency', 'required', 'message' => T::e(' is missing')],
            ['payment_frequency', 'string',   'message' => T::e('Payment frequency is invalid'), 'max' => 190] ,

            ['payment_frequency_amount', 'required', 'message' => T::e(' is missing')],
            ['payment_frequency_amount', 'double',   'message' => T::e('payment frequency amount is invalid'), 'min' => 0, 'max' => $MAX_NUM,
               'tooBig' => T::e('payment frequency amount is invalid'), 'tooSmall' => T::e('payment frequency amount is invalid')],

            ['min_order_morning_amount', 'required', 'message' => T::e(' is missing')],
            ['min_order_morning_amount', 'double',   'message' => T::e('min order morning amount is invalid'), 'min' => 0, 'max' => $MAX_NUM,
               'tooBig' => T::e('payment frequency amount is invalid'), 'tooSmall' => T::e('min order morning amount is invalid')],

            ['min_order_evening_amount', 'required', 'message' => T::e(' is missing')],
            ['min_order_evening_amount', 'double',   'message' => T::e('min order evening amount is invalid'), 'min' => 0, 'max' => $MAX_NUM,
               'tooBig' => T::e('payment frequency amount is invalid'), 'tooSmall' => T::e('min order evening amount is invalid')],

            [['daily_limit', 'weekly_limit', 'monthly_limit'], 'required', 'message' => T::e('Limit is missing')],
            [['daily_limit', 'weekly_limit', 'monthly_limit'], 'double',   'message' => T::e('Limit is invalid'), 'min' => 0, 'max' => $MAX_NUM,
                'tooBig' => T::e('limit is invalid'), 'tooSmall' => T::e('limit is invalid')],

            [ 'limit_type','required','message' => T::e('Limit type is missing')],

            ['client_id', 'integer'],
            ['client_id', 'required', 'message' => T::e('Client is missing')],
            ['client_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Client', 'targetAttribute' => 'id', 'message' => T::l('Invalid client')],

            [ 'sales_fee', 'double', 'min' => 0, 'max' => $MAX_NUM, 'message' => T::e('Fee is invalid'),
                'tooBig' => T::e('Fee is invalid'), 'tooSmall' => T::e('Fee is invalid')],
            [ 'sales_fee', 'default', 'value' => 0],

            [['is_vat_exclusive'], 'boolean'],
            [['limit_type', 'record_type'], 'string'],

            [['create_on', 'last_update'], 'safe'],

            [['min_order_morning_time_from', 'min_order_morning_time_to', 'min_order_evening_time_from', 'min_order_evening_time_to'], 'safe'],

            ['vat_number',  'string',   'max' => 25, 'message' => T::e('Vat number is invalid')],
            ['record_type', 'required', 'message' => T::e('Record Type is missing')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                          => T::l('ID'),
            'name'                        => T::l('Name'),
            'code'                        => T::l('Code'),
            'payment_frequency'           => T::l('Payment Frequency'),
            'payment_frequency_amount'    => T::l('Payment Frequency Amount'),
            'sales_fee'                   => T::l('Sales Fee'),
            'is_vat_exclusive'            => T::l('Is Vat Exclusive'),
            'daily_limit'                 => T::l('Daily Limit'),
            'weekly_limit'                => T::l('Weekly Limit'),
            'monthly_limit'               => T::l('Monthly Limit'),
            'limit_type'                  => T::l('Limit Type'),
            'vat_number'                  => T::l('Vat Number'),
            'client_id'                   => T::l('Client ID'),
            'record_type'                 => T::l('Record Type'),
            'create_on'                   => T::l('Create On'),
            'last_update'                 => T::l('Last Update'),
            'min_order_morning_time_from' => T::l('Morning Time From'),
            'min_order_morning_time_to'   => T::l('To'),
            'min_order_evening_time_from' => T::l('Evening Time From'),
            'min_order_evening_time_to'   => T::l('To'),
            'min_order_morning_amount'    => T::l('min_order_morning_amount'),
            'min_order_evening_amount'    => T::l('min_order_evening_amount')
        ];
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
    public function getCompanyAddresses()
    {
        return $this->hasMany(CompanyAddress::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['id' => 'address_id'])->viaTable('company_address', ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyDomains()
    {
        return $this->hasMany(CompanyDomain::className(), ['company_id' => 'id'])->andOnCondition(['company_domain.record_type' => RecordType::Active]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroups()
    {
        return $this->hasMany(CompanyUserGroup::className(), ['company_id' => 'id'])->andOnCondition(['company_user_group.record_type' => RecordType::Active]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyDefaultExternalUserGroup()
    {
        return $this->hasOne(CompanyUserGroup::className(), ['company_id' => 'id'])->andOnCondition(['company_user_group.record_type' => RecordType::Active, 'company_user_group.name' => DefaultCompanyGroup::DefaultExternal]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryAddresses()
    {
        return $this->hasMany(Address::className(), ['id' => 'address_id'])->viaTable('company_address', ['company_id' => 'id'], function($query) {
            $query->onCondition(['address_type' => \common\enums\CompanyAddressType::Delivery])->where('record_type <> "'.RecordType::Deleted.'"');
        });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhysicalAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id'])->viaTable('company_address', ['company_id' => 'id'], function($query) {
            $query->onCondition(['address_type' => \common\enums\CompanyAddressType::Physical])->where('record_type <> "'.RecordType::Deleted.'"');
        });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id'])->viaTable('company_address', ['company_id' => 'id'], function($query) {
            $query->onCondition(['address_type' => \common\enums\CompanyAddressType::Billing])->where('record_type <> "'.RecordType::Deleted.'"');
        });
    }

    public static function getActive()
    {
        return self::findAll(['record_type' => RecordType::Active]);
    }

    public static function getCompaniesForSelect()
    {
        return yii\helpers\ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name');
    }

    public static function getActiveById($id)
    {
        return self::find([
            'id'          => $id,
            'record_type' => RecordType::Active
        ])->one();
    }

    public static function getCompanyGroups($id)
    {
        $groups = \common\models\CompanyUserGroup::find()->where("company_id = {$id} AND record_type <> '" . RecordType::Deleted . "' AND name <> '" . \common\enums\DefaultCompanyGroup::DefaultExternal . "'")->all();
        return \yii\helpers\ArrayHelper::map($groups, 'id', 'name');
    }

    /**
     * Save company data
     *
     * @param array $postData
     *
     * @return boolean
     */
    public function saveCompanyDetails($postData)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->load($postData);
            $isNew   = !isset($this->id);
            $isSaved = $this->save();

            // Physical address
            $isSaved = $isSaved && $this->_saveCompanyAddress($postData['Address']['physical'], \common\enums\CompanyAddressType::Physical);
            // Billing address
            $isSaved = $isSaved && $this->_saveCompanyAddress($postData['Address']['billing'],  \common\enums\CompanyAddressType::Billing);
            // "Deliver only to" addresses
            if (isset($postData['Address']['delivery']) && sizeof($postData['Address']['delivery'])) {
                $isSaved = $isSaved && $this->_saveCompanyAddress($postData['Address']['delivery'], \common\enums\CompanyAddressType::Delivery, $postData['specific_delivery']);
            }

            // Create default groups
            if ($isNew) {
                $isSaved = $isSaved && $this->_createDefaultGroups($this->id, \common\enums\DefaultCompanyGroup::DefaultExternal);
                $isSaved = $isSaved && $this->_createDefaultGroups($this->id, \common\enums\DefaultCompanyGroup::DefaultInternal);
            }

            if ($isSaved) {
                $transaction->commit();
                Yii::$app->globalCache->loadCompany($this->id);
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        return $isSaved;
    }

    /**
     * Create default user groups
     *
     * @param integer $company_id
     * @param string  $name
     *
     * @return boolean
     */
    private function _createDefaultGroups($company_id, $name) {
        $isSaved = true;

        $group = new CompanyUserGroup;
        $group->company_id  = $company_id;
        $group->name        = $name;
        $group->record_type = 'Active';

        return $isSaved && $group->save();
    }

    /**
     * Save company address
     *
     * @param array  $data
     * @param string $type
     *
     * @return boolean
     */
    private function _saveCompanyAddress($data, $type, $specific_delivery = 'No')
    {
        $isSaved = true;
        if ($type == \common\enums\CompanyAddressType::Delivery && $data) {
            if ($specific_delivery === 'Yes') {
                $companyDeliveryAddresses = $this->deliveryAddress;

                if (sizeof($companyDeliveryAddresses)) {
                    $submittedAddressesIds = [];
                    foreach ($data as $singleAddress) {
                        if (isset($singleAddress['id']) && $singleAddress['id']) {
                            $submittedAddressesIds[] = $singleAddress['id'];
                        }
                    }

                    $existedAddresses = CompanyAddress::find()->where("company_id = {$this->id} AND record_type <> '".RecordType::Deleted."' AND address_type = '".\common\enums\CompanyAddressType::Delivery."'")->all();
                    foreach ($existedAddresses as $addressModel) {
                        if (!in_array($addressModel->address_id, $submittedAddressesIds)) {
                            $address = CompanyAddress::findOne([ 'id' => $addressModel->id ]);
                            $address->record_type = RecordType::Deleted;
                            $address->save();
                        }
                    }

                    foreach ($data as $k => $singleAddress) {
                        if (isset($singleAddress['id']) && $singleAddress['id']) {
                            $finded = false;

                            foreach ($companyDeliveryAddresses as $addressModel) {
                                if ($addressModel->id == $singleAddress['id']) {
                                    $finded = true;
                                    break;
                                }
                            }
                            if (!$finded) {
                                $deliveryAddressModel = new Address;
                            } else {
                                $deliveryAddressModel = Address::findOne(['id' => $singleAddress['id']]);
                            }
                        } else {
                            $deliveryAddressModel = new Address;
                        }
                        $address_is_new = $deliveryAddressModel->getIsNewRecord();

                        $deliveryAddressModel->setAttributes($data[$k]);
                        $isSaved = $isSaved && $deliveryAddressModel->save();

                        if ($address_is_new) {
                            $companyAddress               = new CompanyAddress;
                            $companyAddress->address_id   = $deliveryAddressModel->id;
                            $companyAddress->company_id   = $this->id;
                            $companyAddress->address_type = $type;
                            $isSaved                      = $isSaved && $companyAddress->save();
                        }
                    }
                }
            } else {
                CompanyAddress::updateAll([ 'record_type' => RecordType::Deleted ], [ 'company_id' => $this->id, 'address_type' => \common\enums\CompanyAddressType::Delivery ]);
            }
        } else {
            if ($type == \common\enums\CompanyAddressType::Physical) {
                $addressModel   = $this->physicalAddress;
                $address_is_new = $addressModel->getIsNewRecord();
            } else if ($type == \common\enums\CompanyAddressType::Billing) {
                $addressModel   = $this->billingAddress;
                $address_is_new = $addressModel->getIsNewRecord();
            }

            $addressModel->attributes = $data;
            $isSaved                  = $isSaved && $addressModel->save();

            if ($address_is_new) {
                $companyAddress               = new CompanyAddress;
                $companyAddress->address_id   = $addressModel->id;
                $companyAddress->company_id   = $this->id;
                $companyAddress->address_type = $type;
                $isSaved                      = $isSaved && $companyAddress->save();
            }
        }

        return $isSaved;
    }

    /**
     * Prepare form / model data
     *
     * @param array $postData
     */
    public function prepareRelationRecords($postData)
    {
        if (empty($this->physicalAddress)) {
            $this->populateRelation('physicalAddress', new Address());
        }

        if (empty($this->billingAddress)) {
            $this->populateRelation('billingAddress', new Address());
        }

        if (empty($this->deliveryAddress)) {
            if ($this->deliveryAddresses) {
                $this->populateRelation('deliveryAddress', $this->deliveryAddresses);
                $this->populateRelation('noAddress', false);
            } else {
                $this->populateRelation('deliveryAddress', [new Address()]);
                $this->populateRelation('noAddress', true);
            }
        }

        if (isset($postData['Address']['physical'])){
            $this->physicalAddress->setAttributes($postData['Address']['physical']);
        }

        if (isset($postData['Address']['billing'])){
            $this->billingAddress->setAttributes($postData['Address']['billing']);
        }

        if (isset($postData['Address']['delivery']) && $postData['Address']['delivery']) {
            $addressModel = $this->deliveryAddress;
            $oldRelations = $addressModel;
            $relations    = [];

            foreach ($postData['Address']['delivery'] as $k => $deliveryAddress) {
                $finded = false;
                if (isset($deliveryAddress['id']) && $deliveryAddress['id'] && $oldRelations) {
                    foreach ($oldRelations as $oldRelation) {
                        if ($oldRelation->id == $deliveryAddress['id']) {
                            $oldRelation->setAttributes($deliveryAddress);
                            $relations[] = $oldRelation;
                            $finded      = true;
                            break;
                        }
                    }
                }

                if (!$finded) {
                    $oldRelation = new Address;
                    $oldRelation->setAttributes($deliveryAddress);
                    $relations[] = $oldRelation;
                }
                $this->populateRelation('deliveryAddress', $relations);
            }
        }
    }
}
