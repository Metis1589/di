<?php

namespace common\models;
use \common\enums\RecordType;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $name_key
 * @property string $native_name
 * @property string $iso_code
 * @property integer $is_default
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property AreaAddress[] $areaAddresses
 * @property Postcode[] $postcodes
 */
class Country extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['native_name', 'iso_code', 'record_type'], 'required'],
            [['record_type'], 'string'],
            [['is_default'], 'boolean'],
            [['iso_code'], 'common\validators\CustomUniqueValidator', 'message' => Yii::t('label', 'ISO code is already in use')],
            [['create_on', 'last_update'], 'safe'],
            [['name_key', 'native_name'], 'string', 'max' => 50],
            [['iso_code'], 'string', 'max' => 2],
            [['name_key'], 'unique', 'message' => Yii::t('label', 'Name is already in use')],
            [['native_name'], 'unique', 'message' => Yii::t('label', 'Native name is already in use')],
            ['is_default', 'common\validators\IsDefaultValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'name_key' => Yii::t('label', 'Name'),
            'native_name' => Yii::t('label', 'Native Name'),
            'iso_code' => Yii::t('label', 'Iso Code'),
            'is_default' => Yii::t('label', 'Is Default'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    public function translatedProperties() {
        return ['name_key'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreaAddresses()
    {
        return $this->hasMany(AreaAddress::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostcodes()
    {
        return $this->hasMany(Postcode::className(), ['country_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['code' => 'name_key']);
    }

    public function saveCountryWithDefault()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isSaved = true;
        try {
            $isSaved = $this->resetDefault();
            if ($isSaved) {
                $isSaved = $isSaved && $this->save();
            }
            if ($isSaved) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        return $isSaved;
    }

    public function resetDefault()
    {
        $oldDefaultCountry = self::getDefault();
        if (!isset($oldDefaultCountry)) {
            $this->is_default = true;
            return true;
        }
        if ($this->is_default && $this->id != $oldDefaultCountry->id)
        {
            $oldDefaultCountry = self::getDefault();
            $oldDefaultCountry->is_default = false;
            return $oldDefaultCountry->save(true, ['is_default']);
        }
        return true;
    }
    
    public function load($data, $formName = null) {
        if (Yii::$app->request->isPost) {
            $scope = $formName === null ? $this->formName() : $formName;
            if (!$data[$scope]['is_default'] && $this->is_default) {
                $data[$scope]['is_default'] = true;
            }
        }
        return parent::load($data, $formName);
    }

    public static function getActive()
    {
        return self::findAll(['record_type' => 'Active']);
    }
    
    public static function getCountriesForSelect()
    {
        return ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'native_name');
    }

    public static function getDefault()
    {
        return self::find()->where("record_type <> '".RecordType::Deleted."' AND is_default = 1")->one();
    }
}
