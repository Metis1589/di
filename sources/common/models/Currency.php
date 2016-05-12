<?php

namespace common\models;

use common\enums\RecordType;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "currency".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property integer $is_default
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property RestaurantGroup[] $restaurantGroups
 */
class Currency extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required', 'message' => Yii::t('error', 'Name is missing')],
            ['name', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('label', 'name is already in use')],
            
            ['code', 'required', 'message' => Yii::t('error', 'Code is missing')],
            ['code', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('label', 'code is already in use')],
            
            ['symbol', 'required', 'message' => Yii::t('error', 'Symbol is missing')],
            
            ['name', 'string', 'max' => 149, 'message' => Yii::t('error', 'Name is invalid')],
            ['code', 'string', 'max' => 149, 'message' => Yii::t('error', 'Code is invalid')],
            [['symbol'], 'string', 'max' => 1],
            [['is_default'], 'boolean'],
            [['record_type'], 'string'],
            [['record_type'], 'required', 'message' => Yii::t('error', 'Record Type is missing')],
            [['create_on', 'last_update'], 'safe'],
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
            'name' => Yii::t('label', 'Name'),
            'code' => Yii::t('label', 'Code'),
            'symbol' => Yii::t('label', 'Symbol'), 
            'is_default' => Yii::t('label', 'Is Default'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroups()
    {
        return $this->hasMany(RestaurantGroup::className(), ['currency_id' => 'id']);
    }

    public function saveCurrencyWithDefault()
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
        $oldDefaultCurrency = self::getDefault();
        if (!isset($oldDefaultCurrency)) {
            $this->is_default = true;
            return true;
        }
        if ($this->is_default && $this->id != $oldDefaultCurrency->id)
        {
            $oldDefaultCurrency->is_default = false;
            return $oldDefaultCurrency->save(true, ['is_default']);
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

    public static function getCurrenciesForSelect()
    {
        return ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'code');
    }

    public static function getDefault()
    {
        return self::find()->where("record_type <> '".RecordType::Deleted."' AND is_default = 1")->one();
    }

}
