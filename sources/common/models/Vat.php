<?php

namespace common\models;

use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "vat".
 *
 * @property string $id
 * @property string $type
 * @property double $value
 * @property integer $is_default
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItem[] $menuItems
 */
class Vat extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['type', 'required','message' => Yii::t('error', 'VAT Type is missing')],
            ['value', 'required','message' => Yii::t('error', 'VAT value is missing')],
            [['type', 'record_type'], 'string'],
            [['record_type'], 'required', 'message' => Yii::t('error', 'Record Type is missing')],
            
            ['value', 'double', 'min' => 0,  'max' => 100, 'message' => Yii::t('error', 'VAT value is invalid'),
               'tooBig' => Yii::t('error', 'VAT value is invalid'), 'tooSmall' => Yii::t('error', 'VAT value is invalid')],

            ['is_default', 'boolean' ],
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
            'type' => Yii::t('label', 'Type'),
            'value' => Yii::t('label', 'Value'),
            'is_default' => Yii::t('label', 'Is Default'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['vat_id' => 'id']);
    }

    public function saveVatWithDefault()
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
        $oldDefaultVat = self::getDefault();
        if (!isset($oldDefaultVat)) {
            $this->is_default = true;
            return true;
        }
        if ($this->is_default && $this->id != $oldDefaultVat->id)
        {
            $oldDefaultVat->is_default = false;
            return $oldDefaultVat->save(true, ['is_default']);
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

    public static function getDefault()
    {
        return self::find()->where("record_type <> '".RecordType::Deleted."' AND is_default = 1")->one();
    }
    
    public static function getVATForSelect() {
        return yii\helpers\ArrayHelper::map(self::findAll(['record_type' => RecordType::Active]), 'id', 'type');
    }
}
