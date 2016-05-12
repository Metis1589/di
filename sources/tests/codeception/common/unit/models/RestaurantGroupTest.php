<?php

namespace tests\codeception\common\unit\models;

use Codeception\Util\Stub;
use tests\codeception\common\fixtures\CurrencyFixture;
use tests\codeception\common\fixtures\RestaurantChainFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\RestaurantGroupFixture;
use tests\codeception\common\fixtures\ClientFixture;

/**
 * Login form test
 */
class RestaurantGroupTest extends DineinDbTestCase
{
    use Specify;

    public function testRestaurantGroupCreateDelete()
    {
        $fixtures = require Yii::getAlias('@tests/codeception/common/unit/fixtures/data/models/restaurant_group.php');
        Yii::$app->set(
            'globalCache',
            Stub::make(
                '\common\components\cache\GlobalCache',
                [
                    'getLabel'=>function($label){
                        return $label;
                    },
                    'loadRestaurantsByClientKey'=>Stub::atLeastOnce()
                ]
            )
        );
        parent::testCreateDelete(
            "\\common\\models\\RestaurantGroup",
            $fixtures[0],
            [
                'name_key' => 'rest group 2',
                'restaurant_chain_id' => 2,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
            "restaurantgroup" => [
                'class' => RestaurantGroupFixture::className(),
//                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant_group.php"
            ],
            "currency" => [
                'class' => CurrencyFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/currency.php"
            ],
            "restaurantchain" => [
                'class' => RestaurantChainFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant_chain.php"
            ],

        ];
    }

}
