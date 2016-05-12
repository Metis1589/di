<?php

namespace common\models;

use \common\components\language\T;

/**
 * This is the model class for table "company_domain".
 *
 * @property integer $id
 * @property string $domain
 * @property string $company_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Company $company
 */
class CompanyDomain extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_domain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['domain',      'required', 'message' => T::e('Domain is missing')],
            ['company_id',  'required', 'message' => T::e('Company is missing')],
            ['company_id',  'integer'],
            ['record_type', 'string'],
            ['record_type', 'required', 'message' => T::e('Record Type is missing')],
            ['domain',      'string',   'max' => 50, 'message' => T::e('Domain is Too Long')],
            ['domain',      'match',    'pattern' => '/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?$/i', 'message' => T::e('Domain is invalid')],
            ['domain',      'uniqueDomain'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => T::l('ID'),
            'domain'      => T::l('Domain'),
            'company_id'  => T::l('Company ID'),
            'record_type' => T::l('Record Type'),
            'create_on'   => T::l('Create On'),
            'last_update' => T::l('Last Update'),
        ];
    }

    /**
     * Domain validation
     *
     * @param string $attribute
     * @param string $param
     */
    public function uniqueDomain($attribute, $param)
    {
        $rule  = ($this->id) ? "AND id <> {$this->id}" : '';
        $count = self::find()->where("domain = '{$this->domain}' {$rule}  AND record_type <> '" . \common\enums\RecordType::Deleted . "'")->count();

        if ($count > 0) {
            $this->addError($attribute, T::e('Domain name must be unique'));
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * Save company domain
     *
     * @param array $postedDomain
     *
     * @return \common\models\CompanyDomain
     */
    public static function saveByPost($postedDomain)
    {
        $existedDomain = static::findOne($postedDomain['id']);
        $isNew         = false;

        if ($existedDomain == null) {
            $existedDomain = new CompanyDomain;
            $existedDomain->record_type = \common\enums\RecordType::InActive;
            unset($postedDomain['id']);
            $isNew = true;
        }
        $existedDomain->load($existedDomain, '');
        $existedDomain->attributes = $postedDomain;
        $existedDomain->save();

        return $existedDomain;
    }
}
