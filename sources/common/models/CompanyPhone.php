<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_phone".
 *
 * @property string $phone_id
 * @property string $company_id
 *
 * @property Phone $phone
 * @property Company $company
 */
class CompanyPhone extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_id', 'company_id'], 'required'],
            [['phone_id', 'company_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone_id' => Yii::t('app', 'Phone ID'),
            'company_id' => Yii::t('app', 'Company ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhone()
    {
        return $this->hasOne(Phone::className(), ['id' => 'phone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
