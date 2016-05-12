<?php

namespace common\models;

use common\components\language\T;
use common\enums\RecordType;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $name_key
 * @property string $reference_name
 * @property string $from
 * @property string $to
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property ManuAssigment[] $manuAssigments
 * @property Client $client
 * @property MenuCategory[] $menuCategories
 */
class Menu extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'name_key', 'reference_name', 'from', 'to', 'record_type'], 'required', 'message' => T::e('Field is missing')],
            [['client_id'], 'integer'],
            [['from', 'to', 'create_on', 'last_update'], 'safe'],
            [['record_type'], 'string', 'message' => T::e('Field is missing')],
            [['name_key'], 'string', 'max' => 250]
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
            'name_key' => Yii::t('label', 'Menu Name'),
            'reference_name' => Yii::t('label', 'Menu Reference Name'),
            'from' => Yii::t('label', 'From'),
            'to' => Yii::t('label', 'To'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    public function translatedProperties() {
        return ['name_key'];
    }
    
    public static function cacheAttributes() {
        return ['menu.id', 'menu.name_key', 'menu.from', 'menu.to'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManuAssigments()
    {
        return $this->hasMany(ManuAssigment::className(), ['menu_id' => 'id']);
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
    public function getMenuCategories()
    {
        return $this->hasMany(MenuCategory::className(), ['menu_id' => 'id']);
    }
    
    public static function getMenuForSelect() {
        return ArrayHelper::map(self::find()->where('record_type <> "'.  \common\enums\RecordType::Deleted.'"')->andWhere('menu.client_id = :client_id',['client_id' => Yii::$app->request->getImpersonatedClientId()])->orderBy('reference_name')->all(), 'id', 'reference_name');
    }

    public function save($runValidation = true, $attributeNames = null) {
        if (Yii::$app->request->isImpersonated()) {
            $this->client_id = Yii::$app->request->getImpersonatedClientId();
        }
        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if ($this->record_type == RecordType::Deleted) {
            foreach ($this->menuCategories as $category) {
                $category->record_type = RecordType::Deleted;
                //$category->scenario = 'CascadeDelete';
                $category->save();
            }
        }
    }
}
