<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\AddressBaseFixture;
use tests\codeception\common\fixtures\ClientFixture;
use tests\codeception\common\fixtures\CurrencyFixture;
use tests\codeception\common\fixtures\RestaurantChainFixture;
use tests\codeception\common\fixtures\RestaurantGroupFixture;
use tests\codeception\common\fixtures\SeoAreaFixture;
use tests\codeception\common\fixtures\VatFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\RestaurantFixture;
use Codeception\Util\Stub;

/**
 * Login form test
 */
class RestaurantTest extends DineinDbTestCase
{
    use Specify;

    public function testRestaurantCreateDelete()
    {
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
        $fixtures = require Yii::getAlias('@tests/codeception/common/unit/fixtures/data/models/restaurant.php');
        parent::testCreateDelete(
            "\\common\\models\\Restaurant",
            $fixtures[0],
            [

            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'seoArea'=>[
                'class'=>SeoAreaFixture::className(),
                'dataFile'=>"@tests/codeception/common/unit/fixtures/data/models/seo_area.php"
            ],
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
            "currency" => [
                'class' => CurrencyFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/currency.php"
            ],
            "restaurantchain" => [
                'class' => RestaurantChainFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant_chain.php"
            ],
            "restaurantgroup" => [
                'class' => RestaurantGroupFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant_group.php"
            ],
            "addressbase" => [
                'class' => AddressBaseFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/address_base.php"
            ],
            "restaurant" => [
                'class' => RestaurantFixture::className(),
//                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant.php"
            ],
        ];
    }
}
