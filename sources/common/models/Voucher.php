<?php

namespace common\models;
use common\components\language\T;
use common\enums\RecordType;
use yii\helpers\ArrayHelper;
use \common\enums\VoucherCategory;

use Yii;

class Voucher extends VoucherBase
{
    public $assignment;
    public $source_menu_category;
    public $target_menu_category;
    public $menu_category;
    public $menu_item_ids;


    public function rules()
    {
        return [
            ['code', 'required','message' => T::e('Code is missing')],
            ['client_id', 'required','message' => T::e('Client ID is missing')],
            ['start_date', 'required','message' => T::e('Start Date is missing')],
            ['end_date', 'required','message' => T::e('End Date is missing')],
            ['description', 'required','message' => T::e('Description is missing')],
            ['category', 'required','message' => T::e('Category is missing')],
            ['promotion_type', 'required','message' => T::e('Promotion_type is missing')],
            ['max_times_per_user', 'required','message' => T::e('Max times per user is missing')],
            ['record_type', 'required','message' => T::e('Record type is missing')],
            ['discount_value', 'number', 'message' => T::e('Discount value should be a number')],
            ['price_value', 'number', 'message' => T::e('Price value should be a number')],
            ['item_quantity', 'number', 'message' => T::e('Item quantity should be a number')],
            ['max_times_per_user', 'number', 'message' => T::e('Max times per user value should be a number')],
            [['start_date', 'end_date', 'validation_service', 'create_on', 'last_update'], 'safe'],
            [['value_type', 'description', 'generate_by', 'record_type', 'assignment'], 'string'],
            ['client_id', 'required', 'when' => function($model) {
                return (empty($model->restaurant_id) && empty($model->restaurant_chain_id) && empty($model->restaurant_group_id) && empty($model->user_id) );
            },'whenClient' => 'function (attribute, value) { return ($("input[type=\'radio\']:checked", ".voucher-form").val() == "'. \common\enums\VoucherAssignmentType::Client.'"); }'],
            ['restaurant_id', 'required', 'when' => function($model) {
                return (empty($model->client_id) && empty($model->restaurant_chain_id) && empty($model->restaurant_group_id) && empty($model->user_id) );
            },'whenClient' => 'function (attribute, value) { return ($("input[type=\'radio\']:checked", ".voucher-form").val() == "'. \common\enums\VoucherAssignmentType::Restaurant.'"); }'],
            ['restaurant_chain_id', 'required',  'when' => function($model) {
                return (empty($model->client_id) && empty($model->restaurant_id) && empty($model->restaurant_group_id) && empty($model->user_id) );
            }, 'whenClient' => 'function (attribute, value) { return ($("input[type=\'radio\']:checked", ".voucher-form").val() == "'. \common\enums\VoucherAssignmentType::RestaurantChain.'"); }'],
            ['restaurant_group_id', 'required', 'when' => function($model) {
                return (empty($model->client_id) && empty($model->restaurant_id) && empty($model->restaurant_chain_id) && empty($model->user_id) );
            }, 'whenClient' => 'function (attribute, value) { return ($("input[type=\'radio\']:checked", ".voucher-form").val() == "'. \common\enums\VoucherAssignmentType::RestaurantGroup.'"); }'],
            ['user_id', 'required', 'when' => function($model) {
                return (empty($model->client_id) && empty($model->restaurant_id) && empty($model->restaurant_chain_id) && empty($model->restaurant_group_id) );
            }, 'whenClient' => 'function (attribute, value) { return ($("input[type=\'radio\']:checked", ".voucher-form").val() == "'. \common\enums\VoucherAssignmentType::User.'"); }'],
            [['code_min_length', 'code_max_length'], 'required', 'when' => function($model) {
                return $model->validation_service == \common\enums\ValidationServiceType::EagleEye;
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-validation_service").val() == "'. \common\enums\ValidationServiceType::EagleEye.'"); }'],
            
            ['target_menu_category', 'required', 'when' => function($model) {
                return  in_array($model->category, [VoucherCategory::FreeWithinCategory])  ;
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::FreeWithinCategory.'"); }'],
                    
            ['menu_category', 'required', 'when' => function($model) {
                return  in_array($model->category, [VoucherCategory::OffByCategory, VoucherCategory::MultipleCategoriesSinglePrice])  ;
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::OffByCategory.'" '
                    . ' || $("#voucher-category").val() == "'. VoucherCategory::MultipleCategoriesSinglePrice.'"); }'],        
                    
            ['discount_value', 'required', 'when' => function($model) {
                return (in_array($model->category, [VoucherCategory::All, 
                                                    VoucherCategory::Delivery, 
                                                    VoucherCategory::Food,
                                                    VoucherCategory::FoodPrice,
                                                    VoucherCategory::Wine,
                                                    VoucherCategory::OffByCategory,
                                                    VoucherCategory::MultipleCategoriesSinglePrice,
                                                    VoucherCategory::MultipleItemsSinglePrice,
                    ]) || ($model->category == VoucherCategory::MenuItems && $model->discount_type == \common\enums\VoucherDiscountType::Discount));
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::All.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Food.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::FoodPrice.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MenuItems.'" &&  $("input[type=\'radio\']:checked", "#MenuItem").val() == "'.\common\enums\VoucherDiscountType::Discount.'"'
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Wine.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::OffByCategory.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MultipleCategoriesSinglePrice.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MultipleItemsSinglePrice.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Delivery.'"); }'],
            ['value_type', 'required', 'when' => function($model) {
                return (in_array($model->category, [VoucherCategory::All, 
                                                    VoucherCategory::Delivery, 
                                                    VoucherCategory::Food,
                                                    VoucherCategory::FoodPrice,
                                                    VoucherCategory::Wine,
                                                    VoucherCategory::OffByCategory,
                                                    VoucherCategory::MultipleCategoriesSinglePrice,
                                                    VoucherCategory::MultipleItemsSinglePrice,
                        ]) || ($model->category == VoucherCategory::MenuItems && $model->discount_type == \common\enums\VoucherDiscountType::Discount));
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::All.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Food.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::FoodPrice.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MenuItems.'" &&  $("input[type=\'radio\']:checked", "#MenuItem").val() == "'.\common\enums\VoucherDiscountType::Discount.'"'
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Wine.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::OffByCategory.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MultipleCategoriesSinglePrice.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MultipleItemsSinglePrice.'" '    
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Delivery.'"); }'],
            ['item_quantity', 'required', 'when' => function($model) {
                return (in_array($model->category, [
                                                    VoucherCategory::Delivery, 
                                                    VoucherCategory::Wine, 
                                                    VoucherCategory::Food, 
                                                    VoucherCategory::All, 
                                                    VoucherCategory::MenuItems,
                                                    VoucherCategory::FreeItem,
                                                    VoucherCategory::FreeWithinCategory,
                                                    VoucherCategory::OffByCategory,
                                                    VoucherCategory::MultipleCategoriesSinglePrice,
                                                    VoucherCategory::MultipleItemsSinglePrice
                    ]));
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::All.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Food.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Wine.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MenuItems.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::FreeItem.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::FreeWithinCategory.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::OffByCategory.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MultipleCategoriesSinglePrice.'" '
                . '|| $("#voucher-category").val() == "'. VoucherCategory::MultipleItemsSinglePrice.'" '    
                . '|| $("#voucher-category").val() == "'. VoucherCategory::Delivery.'"); }'],
            [['discount_type'], 'required', 'when' => function($model) {
                return ($model->category == VoucherCategory::MenuItems);
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::MenuItems.'"); }'],
            ['menu_item_id', 'required', 'when' => function($model) {
                return (in_array($model->category, [VoucherCategory::MenuItems]));
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::MenuItems.'"); }'],
            ['menu_item_ids', 'required', 'when' => function($model) {
                return (in_array($model->category, [VoucherCategory::FreeItem, VoucherCategory::MultipleItemsSinglePrice]));
            },'whenClient' => 'function (attribute, value) { return ($("#voucher-category").val() == "'. VoucherCategory::MultipleItemsSinglePrice.'"'
                . '|| $("#voucher-category").val() == "'. VoucherCategory::FreeItem.'"); }'],
            [['price_value'], 'required', 'when' => function($model) {
                return (($model->category == VoucherCategory::MenuItems && $model->discount_type == \common\enums\VoucherDiscountType::Price) || $model->category == VoucherCategory::FoodPrice);
            },'whenClient' => 'function (attribute, value) { return (($("#voucher-category").val() == "'. VoucherCategory::MenuItems.'" && $("input[type=\'radio\']:checked", "#MenuItem").val() == "'.\common\enums\VoucherDiscountType::Price.'")) || $("#voucher-category").val() == "'. VoucherCategory::FoodPrice.'" }'],        
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
            'restaurant_chain_id' => Yii::t('app', 'Restaurant Chain ID'),
            'restaurant_group_id' => Yii::t('app', 'Restaurant Group ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'validation_service' => Yii::t('app', 'Validation Service'),
            'code' => Yii::t('app', 'Code'),
            'code_min_length' => Yii::t('app', 'Code Min Length'),
            'code_max_length' => Yii::t('app', 'Code Max Length'),
            'category' => Yii::t('app', 'Category'),
            'discount_value' => Yii::t('app', 'Discount Value'),
            'discount_type' => Yii::t('app', 'Discount Type'),
            'promotion_type' => Yii::t('app', 'Promotion Type'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'value_type' => Yii::t('app', 'Value Type'),
            'price_value' => Yii::t('app', 'Price Value'),
            'item_quantity' => Yii::t('app', 'Item Quantity'),
            'description' => Yii::t('app', 'Description'),
            'max_times_per_user' => Yii::t('app', 'Max Times Per User'),
            'generate_by' => Yii::t('app', 'Generate By'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
            'assignment' => Yii::t('app', 'Assigned To'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherMenuCategories()
    {
        return $this->hasMany(VoucherMenuCategory::className(), ['voucher_id' => 'id'])->andOnCondition(['<>','voucher_menu_category.record_type', \common\enums\RecordType::Deleted]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherMenuItems()
    {
        return $this->hasMany(VoucherMenuItem::className(), ['voucher_id' => 'id'])->andOnCondition(['<>','voucher_menu_item.record_type', \common\enums\RecordType::Deleted]);
    }
    
    public function getSourceVoucherMenuCategories()
    {
        return $this->hasMany(VoucherMenuCategory::className(), ['voucher_id' => 'id'])->andOnCondition(['<>','voucher_menu_category.record_type', \common\enums\RecordType::Deleted])->where(['menu_category_type' => \common\enums\VoucherMenuCategoryType::Source]);
    }
    
    public function getTargetVoucherMenuCategories()
    {
        return $this->hasMany(VoucherMenuCategory::className(), ['voucher_id' => 'id'])->andOnCondition(['<>','record_type', RecordType::Deleted])->where(['menu_category_type' => \common\enums\VoucherMenuCategoryType::Target]);
    }

    public function getVoucherSchedules()
    {
        return $this->hasMany(VoucherSchedule::className(), ['voucher_id' => 'id'])->andOnCondition(['voucher_schedule.record_type' => RecordType::Active]);
    }

    public function load($data, $formName = null) {
        $result = parent::load($data, $formName);
        $scope = $formName === null ? $this->formName() : $formName;
        if (isset($data[$scope]['value_type']) && empty($data[$scope]['value_type'])) {
            $this->value_type = null;
        }
        if (isset($data[$scope]['discount_type']) && empty($data[$scope]['discount_type'])) {
            $this->discount_type = null;
        }
        if (isset($data[$scope]['validation_service']) && empty($data[$scope]['validation_service'])) {
            $this->validation_service = null;
        }
        return $result;
    }

    public static function getVouchersByClientForSelect()
    {
        return ArrayHelper::map(self::find()->where('record_type != :record_type and client_id = :client_id',['record_type' => \common\enums\RecordType::Deleted, 'client_id' => Yii::$app->request->getImpersonatedClientId()])->all(), 'id', 'code');
    }
}
