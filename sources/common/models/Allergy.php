<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "allergy".
 *
 * @property integer $id
 * @property string $name_key
 * @property string $description_key
 * @property string $symbol_key
 * @property string $image_file_name
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItemAllergy[] $menuItemAllergies
 * @property MenuItem[] $menuItems
 */
class Allergy extends \common\models\BaseModel {

    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'allergy';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name_key', 'description_key', 'symbol_key', 'record_type'], 'required'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name_key', 'description_key', 'symbol_key'], 'string', 'max' => 255],
            [['image_file_name'], 'string', 'max' => 500],
            [['file'], 'file', 'skipOnEmpty' => true],
            [['file'], 'file', 'skipOnEmpty' => true],
            [['file'], function($attribute) {
                if ($this->file && $this->file->name){
                    $file_parts = explode('.',$this->file->name);
                    $ext = strtolower($file_parts[count($file_parts)-1]);
                    if(!in_array($ext,['jpg','png','jpeg'])){
                        $this->addError('file', Yii::t('error', 'Wrong file type. Only jpg,png,jpeg supported.'));
                    }
                }
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('label', 'ID'),
            'name_key' => Yii::t('label', 'Name Key'),
            'description_key' => Yii::t('label', 'Description Key'),
            'symbol_key' => Yii::t('label', 'Symbol'),
            'image_file_name' => Yii::t('label', 'Image File Name'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    public function translatedProperties() {
        return ['name_key', 'description_key'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemAllergies() {
        return $this->hasMany(MenuItemAllergy::className(), ['allergy_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['code' => 'name_key']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems() {
        return $this->hasMany(MenuItem::className(), ['id' => 'menu_item_id'])->viaTable('menu_item_allergy', ['allergy_id' => 'id']);
    }

    public static function getAllergies($client_id) {
        $return = [];
        $AllergyRecords = self::findAll(['record_type' => \common\enums\RecordType::Active]);
        if($AllergyRecords){
            foreach($AllergyRecords as $AllergyRecord){
                $return[$AllergyRecord['id']] = '<div class=\'col-sm-4\'>'.$AllergyRecord['name_key'].'</div><div class=\'col-sm-8\'>'.($AllergyRecord['description_key'] ? '<span style=\'font-weight: normal;\'>'.$AllergyRecord['description_key'].'</span>' : '').'</div>';
            }
        }
        return $return;
    }
    
    public function load($data, $formName = null) {
        
        $scope = $formName === null ? $this->formName() : $formName;
        
        if (array_key_exists($scope, $data)) {

            $items = &$data[$scope];

            if (array_key_exists('image_file_name', $items) && empty($items['image_file_name'])) {
                unset($items['image_file_name']);
            }
        }

        return parent::load($data, $formName);
    }
}
