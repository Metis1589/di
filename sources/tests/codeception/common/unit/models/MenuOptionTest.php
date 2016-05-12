<?php

namespace tests\codeception\common\unit\models;

use common\models\MenuOption;
use tests\codeception\common\fixtures\MenuCategoryFixture;
use tests\codeception\common\fixtures\MenuFixture;
use tests\codeception\common\fixtures\MenuOptionFixture;
use tests\codeception\common\fixtures\MenuItemFixture;
use tests\codeception\common\fixtures\VatFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\unit\DbTestCase;

/**
 * Login form test
 */
class MenuOptionTest extends DbTestCase
{
    use Specify;

    public function __testMenuOptionCreateDelete()
    {
//        parent::testCreateDelete(
//            "\\common\\models\\Country",
//            [
//                'name_key' => 'Country Name',
//                'native_name' => 'Native Name',
//                'iso_code' => 'CO',
//                'record_type' => 'Active',
//            ],
//            [
//                'name_key' => 'Country Name Upd',
//                'native_name' => 'Native Name Upd',
//                'iso_code' => 'CC',
//                'record_type' => 'Active',
//            ]);
    }

    public function testLoadAndSave()
    {
        // load tree from db
        $options = MenuOption::getTreeAsArray(1);

        // 1. change parent name

        $parent = $options[count($options) - 1];

        $parent['name_key'] = 'PARENT CHANGED';

        // 1. insert child category to the last element

        $optionNew1 = [
            'id'               => 1005,
            'parent_id'        => $parent['id'],
            'menu_item_id'     => $parent['menu_item_id'],
            'name_key'         => 'NEW 1',
            'web_price'        => '10.99',
            'restaurant_price' => '11.99',
            'is_new'           => true
        ];

        $options[] = $optionNew1;

        // 2. insert child category to the last element

        $optionNew12 = [
            'id'               => 1006,
            'parent_id'        => $optionNew1['id'],
            'menu_item_id'     => $optionNew1['menu_item_id'],
            'name_key'         => 'NEW 12',
            'web_price'        => '10.99',
            'restaurant_price' => '11.99',
            'is_new'           => true
        ];

        $options[] = $optionNew12;

        // 3. insert child category to New 12
        $optionNew123 = [
            'id'               => 1007,
            'parent_id'        => $optionNew12['id'],
            'menu_item_id'     => $optionNew12['menu_item_id'],
            'name_key'         => 'NEW 123',
            'web_price'        => '10.99',
            'restaurant_price' => '11.99',
            'is_new'           => true
        ];

        $options[] = $optionNew123;

        MenuOption::saveTreeAsArray(1, $options);


//        $options = MenuOption::find(['menu_item_id' => 1, 'parent_id' => null])->asArray()->all();
//
//        foreach ($options as $option) {
//            $options2 = MenuOption::find(['menu_item_id' => 1, 'parent_id' => $option->id])->asArray()->all();
//
//            foreach ($options as $option) {
//
//            }
//        }

        // add, edit, remove

        // load from db and verify
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'vat'          => [
                'class'    => VatFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/vat.php'
            ],
            'menu'         => [
                'class'    => MenuFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/menu.php'
            ],
            'menucategory' => [
                'class'    => MenuCategoryFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/menu_category.php'
            ],
            "menuoption"   => [
                'class'    => MenuOptionFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/menu_option.php"
            ],
            "menuitem"     => [
                'class'    => MenuItemFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/menu_item.php"
            ],
        ];
    }
}
