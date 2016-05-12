<?php

namespace common\models;

use common\components\language\T;
use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "menu_category".
 *
 * @property string $id
 * @property integer $menu_id
 * @property string $name_key
 * @property string $reference_name
 * @property string $description_key
 * @property string $image_file_name
 * @property integer $is_optional
 * @property integer $sort_order
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Menu $menu
 * @property MenuItem[] $menuItems
 */
class MenuCategory extends \common\models\BaseModel
{
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'name_key', 'sort_order', 'reference_name', 'record_type'], 'required', 'message' => T::e('Field is missing')],
            [['menu_id', 'is_optional', 'sort_order'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name_key', 'description_key'], 'string', 'max' => 250],
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'menu_id' => Yii::t('label', 'Menu ID'),
            'name_key' => Yii::t('label', 'Category Name'),
            'reference_name' => Yii::t('label', 'Category Reference Name'),
            'description_key' => Yii::t('label', 'Description'),
            'image_file_name' => Yii::t('label', 'Image File Name'),
            'is_optional' => Yii::t('label', 'Is Optional'),
            'sort_order' => Yii::t('label', 'Sort Order'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    public function translatedProperties() {
        return ['name_key', 'description_key'];
    }
    
    public static function cacheAttributes() {
        return ['menu_category.id', 
                'menu_category.menu_id',
                'menu_category.name_key', 
                'menu_category.description_key', 
                'menu_category.image_file_name', 
                'menu_category.is_optional'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_category_id' => 'id']);
    }

    public static function getMenuCategoriesByClientId($client_id, $menu_id = null)
    {
        $options = [];

        $q = \common\models\Menu::find()->where(['record_type' => \common\enums\RecordType::Active, 'client_id' => $client_id]);

        if ($menu_id) {
            $q->andWhere(['id' => $menu_id]);
        }

        $parents = $q->orderBy('reference_name')->all();
        foreach ($parents as $id => $p) {
            $children = self::find()->joinWith(['menu'])->where("menu_id=:menu_id and menu_category.record_type=:record_type and menu.client_id=:client_id", [":menu_id" => $p->id, ":client_id" => $client_id, ":record_type" => \common\enums\RecordType::Active])->orderBy('reference_name')->all();
            $child_options = [];
            foreach ($children as $child) {
                $child_options[$child->id] = $child->reference_name;
            }
            $options[$p->reference_name] = $child_options;
        }
        return $options;
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

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if ($this->record_type == RecordType::Deleted) {
            foreach ($this->menuItems as $item) {
                $item->record_type = RecordType::Deleted;
                //$item->scenario = 'CascadeDelete';
                $item->save();
            }
        }

    }
}
