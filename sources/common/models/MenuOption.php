<?php

namespace common\models;

use Exception;
use Yii;

/**
 * This is the model class for table "menu_option".
 *
 * @property string $id
 * @property string $parent_id
 * @property string $copied_from_id
 * @property string $menu_item_id
 * @property string $menu_option_category_type_id
 * @property integer $max_category_items
 * @property string $name_key
 * @property string $description_key
 * @property double $web_price
 * @property double $restaurant_price
 * @property boolean $is_default
 * @property integer $sort_order
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItem $menuItem
 * @property MenuOption $parent
 * @property MenuOption[] $menuOptions
 * @property MenuOption $copiedFrom
 * @property MenuOptionCategoryType $menuOptionCategoryType
 */
class MenuOption extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id'], 'required'],
            [['id', 'parent_id','copied_from_id', 'menu_item_id', 'menu_option_category_type_id', 'max_category_items', 'sort_order'], 'integer'],
            [['web_price', 'restaurant_price'], 'number'],
            [['is_default'], 'boolean'],
            [['record_type'], 'string'],
            [['create_on', 'last_update', 'name_key', 'description_key'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'parent_id' => Yii::t('label', 'Parent ID'),
            'copied_from_id' => Yii::t('label', 'Copied From ID'),
            'menu_item_id' => Yii::t('label', 'Menu Item ID'),
            'menu_option_category_type_id' => Yii::t('label', 'Menu Option Category Type ID'),
            'max_category_items' => Yii::t('label', 'Max Category Items'),
            'name_key' => Yii::t('label', 'Name Key'),
            'description_key' => Yii::t('label', 'Description Key'),
            'web_price' => Yii::t('label', 'Web Price'),
            'restaurant_price' => Yii::t('label', 'Restaurant Price'),
            'is_default' => Yii::t('label', 'Is Default'),
            'sort_order' => Yii::t('label', 'Sort Order'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    public static function cacheAttributes() {
        return ['menu_option.id', 
                'menu_option.parent_id',
                'menu_option.menu_item_id',
                'menu_option.name_key', 
                'menu_option.description_key', 
                'menu_option.restaurant_price', 
                'menu_option.web_price',
                'menu_option.is_default', 
            ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MenuOption::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptions()
    {
        return $this->hasMany(MenuOption::className(), ['copied_from_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCopiedFrom()
    {
        return $this->hasOne(MenuOption::className(), ['id' => 'copied_from_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptionCategoryType()
    {
        return $this->hasOne(MenuOptionCategoryType::className(), ['id' => 'menu_option_category_type_id']);
    }


    /**
     * Load single node
     * @param $menu_item_id
     * @param $menu_option_id
     * @param $level
     * @param $result
     */
    private static function getTreeNodeAsArray($menu_item_id, $menu_option_id, $level, &$result)
    {
        $options = MenuOption::find()
            ->where(
                [
                    'menu_item_id' => $menu_item_id,
                    'parent_id' => $menu_option_id])
            ->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')
            ->orderBy('sort_order')->asArray()->all();

        foreach ($options as $option) {

            $option['level'] = $level + 1;
            $result[] = $option;

            MenuOption::getTreeNodeAsArray($menu_item_id, $option['id'], $option['level'], $result);
        }
    }

    /**
     * get options tree as sorted table
     * @param $menu_item_id
     * @return array
     */
    public static function getTreeAsArray($menu_item_id) {
        $result = [];
        MenuOption::getTreeNodeAsArray($menu_item_id, null, 0, $result);

        foreach ($result as &$option) {

            $option['is_default'] = (bool)$option['is_default'];
        }

        return $result;
    }

    /**
     * @param $menu_item_id
     * @param $options
     * @return boolean
     */
    public static function saveTreeAsArray($menu_item_id, $options) {

        $mapping = [];

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $is_saved = true;
            foreach ($options as $option) {
                if (array_key_exists('is_new', $option) && $option['is_new'] == true) {

                    // insert new element
                    $previous_id = $option['id'];

                    unset($option['id']);
                    if (array_key_exists($option['parent_id'], $mapping)) {
                        $option['parent_id'] = $mapping[$option['parent_id']];
                    }

                    $newOption = new MenuOption();
                    $newOption->setAttributes($option);
                    $newOption->menu_item_id = $menu_item_id;

                    $is_saved = $is_saved && $newOption->save();

                    $mapping[$previous_id] = $newOption->id;
                } else {
                    $newOption = MenuOption::findOne(['id' => $option['id']]);
                    $newOption->setAttributes($option);

                    $is_saved = $is_saved && $newOption->save();
                }
            }

            if ($is_saved) {
                $transaction->commit();
            } else  {
                $transaction->rollBack();
                return false;
            }
        }
        catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    /**
     * @param $menu_item_id
     * @param $menu_option_id
     * @param $result
     */
    private static function getTreeNode($menu_item_id, $menu_option_id, &$result)
    {
        $options = MenuOption::find()
            ->where(
                [
                    'menu_item_id' => $menu_item_id,
                    'parent_id' => $menu_option_id])
            ->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')
            ->orderBy('sort_order')->asArray()->all();

        foreach ($options as &$option) {

            $option['options'] = [];

            MenuOption::getTreeNode($menu_item_id, $option['id'], $option['options']);

            $option['is_category'] = !empty($option['menu_option_category_type_id']);

            if ($option['is_category']) {
                $isLastCategory = true;
                foreach($option['options'] as $o) {
                    if (!empty($o['menu_option_category_type_id'])) {
                        $isLastCategory = false;
                        break;
                    }
                }
                $option['is_last_category'] = $isLastCategory;
            }

            if (!isset($option['web_price'])) {
                foreach($result as $o) {
                    if ($o['id'] == $option['parent_id']) {
                        $option['web_price'] = $o['web_price'];
                        break;
                    }
                }
            }

            $result[] = $option;
        }
    }

    /**
     * get options tree as sorted table
     * @param $menu_item_id
     * @return array
     */
    public static function getTree($menu_item_id) {

        $result = [];
        MenuOption::getTreeNode($menu_item_id, null, $result);

        return $result;
    }

    /**
     * Get web price recursive
     * @return float
     */
    public function getWebPriceRecursive() {
        $option = $this;

        do {
            if ($option->web_price !== null) {
                return $option->web_price;
            }

            $option = $option->parent;
        }
        while ($option->parent_id);

        return 0;
    }

    /**
     * Get restaurant price recursive
     * @return float
     */
    public function getRestaurantPriceRecursive() {
        $option = $this;

        do {
            if ($option->restaurant_price !== null) {
                return $option->restaurant_price;
            }

            $option = $option->parent;
        }
        while ($option->parent_id);

        return 0;
    }
}
