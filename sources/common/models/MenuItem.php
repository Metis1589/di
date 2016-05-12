<?php

namespace common\models;

use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "menu_item".
 *
 * @property string $id
 * @property string $vat_id
 * @property string $menu_category_id
 * @property string $name_key
 * @property double $restaurant_price
 * @property double $web_price
 * @property string $description_key
 * @property integer $cook_time
 * @property string $nutritional
 * @property integer $is_imported
 * @property integer $is_alcohol
 * @property integer $sort_order
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CustomFieldValue[] $customFieldValues
 * @property Vat $vat
 * @property MenuCategory $menuCategory
 * @property MenuItemAllergy[] $menuItemAllergies
 * @property Allergy[] $allergies
 * @property MenuItemLike[] $menuItemLikes
 * @property User[] $users
 * @property MenuItemMenuBundle[] $menuItemMenuBundles
 * @property MenuBundle[] $menuBundles
 * @property MenuItemMenuType[] $menuItemMenuTypes
 * @property MenuType[] $menuTypes
 * @property MenuItemSimilar[] $menuItemSimilars
 * @property MenuOption[] $menuOptions
 * @property VoucherMenuItem[] $voucherMenuItems
 * @property Voucher[] $vouchers
 */
class MenuItem extends \common\models\BaseModel
{
    public $file;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vat_id', 'menu_category_id', 'web_price', 'restaurant_price', 'record_type' ], 'required'],
            [['vat_id', 'menu_category_id', 'is_imported', 'is_alcohol', 'sort_order'], 'integer'],
            [['restaurant_price', 'web_price'], 'number'],
            [['record_type'], 'string'],
            [['create_on', 'last_update', 'cook_time'], 'safe'],
            [['name_key'], 'string', 'max' => 250],
            [['description_key'], 'string'],
            [['nutritional'], 'string', 'max' => 500],
            [['image_file_name'], 'string', 'max' => 500],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024 * 20],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024 * 20],
            [['file'], function($attribute) {
                if ($this->file && $this->file->name){
                    $file_parts = explode('.',$this->file->name);
                    $ext = strtolower($file_parts[count($file_parts)-1]);
                    if(!in_array($ext,['jpg', 'png', 'jpeg'])){
                        $this->addError('image_file_name', Yii::t('error', 'Wrong file type. Only jpg,png,jpeg supported.'));
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
            'vat_id' => Yii::t('label', 'Vat ID'),
            'menu_category_id' => Yii::t('label', 'Menu Category ID'),
            'name_key' => Yii::t('label', 'Menu Item Name'),
            'restaurant_price' => Yii::t('label', 'Restaurant Price'),
            'web_price' => Yii::t('label', 'Web Price'),
            'description_key' => Yii::t('label', 'Description'),
            'cook_time' => Yii::t('label', 'Cook Time'),
            'nutritional' => Yii::t('label', 'Nutritional'),
            'is_imported' => Yii::t('label', 'Is Imported'),
            'sort_order' => Yii::t('label', 'Sort Order'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomFieldValues()
    {
        return $this->hasMany(CustomFieldValue::className(), ['menu_item_id' => 'id']);
    }

    public function translatedProperties() {
        return ['name_key', 'description_key'];
    }
    
    public static function cacheAttributes() {
        return ['menu_item.id', 
                'menu_item.menu_category_id',
                'menu_item.name_key', 
                'menu_item.description_key', 
                'menu_item.restaurant_price', 
                'menu_item.web_price',
                'menu_item.image_file_name', 
                'menu_item.nutritional',
                'menu_item.cook_time',
                'menu_item.is_imported',
                'menu_item.is_alcohol',
                'menu_item.record_type',
            ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVat()
    {
        return $this->hasOne(Vat::className(), ['id' => 'vat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategory()
    {
        return $this->hasOne(MenuCategory::className(), ['id' => 'menu_category_id']);
    }

    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id'])->viaTable('menu_category', ['id' => 'menu_category_id']);;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemAllergies()
    {
        return $this->hasMany(MenuItemAllergy::className(), ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllergies()
    {
        return $this->hasMany(Allergy::className(), ['id' => 'allergy_id'])->viaTable('menu_item_allergy', ['menu_item_id' => 'id'],
            function($query) {
                $query->onCondition(['menu_item_allergy.record_type' => RecordType::Active]);
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemLikes()
    {
        return $this->hasMany(MenuItemLike::className(), ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('menu_item_like', ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemMenuBundles()
    {
        return $this->hasMany(MenuItemMenuBundle::className(), ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuBundles()
    {
        return $this->hasMany(MenuBundle::className(), ['id' => 'menu_bundle_id'])->viaTable('menu_item_menu_bundle', ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemMenuTypes()
    {
        return $this->hasMany(MenuItemMenuType::className(), ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuTypes()
    {
        return $this->hasMany(MenuType::className(), ['id' => 'menu_type_id'])->viaTable('menu_item_menu_type', ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemSimilars()
    {
        return $this->hasMany(MenuItemSimilar::className(), ['menu_item_similar_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptions()
    {
        return $this->hasMany(MenuOption::className(), ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherMenuItems()
    {
        return $this->hasMany(VoucherMenuItem::className(), ['menu_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['id' => 'voucher_id'])->viaTable('voucher_menu_item', ['menu_item_id' => 'id']);
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
            foreach ($this->menuOptions as $option) {
                $option->record_type = RecordType::Deleted;
                //$option->scenario = 'CascadeDelete';
                $option->save();
            }
        }
    }
}
