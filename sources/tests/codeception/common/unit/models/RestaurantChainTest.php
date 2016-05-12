<?php

namespace tests\codeception\common\unit\models;

use Codeception\Util\Stub;
use tests\codeception\common\fixtures\ClientFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\RestaurantChainFixture;

/**
 * Login form test
 */
class RestaurantChainTest extends DineinDbTestCase
{
    use Specify;

    public function testRestaurantChainCreateDelete()
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
        parent::testCreateDelete(
            "\\common\\models\\RestaurantChain",
            [
                'name_key' => 'rest chain',
                'client_id' => 1,
                'record_type' => 'Active',
            ],
            [
                'name_key' => 'rest 2 chain',
                'client_id' => 2,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "restaurantchain" => [
                'class' => RestaurantChainFixture::className(),
//                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant_chain.php"
            ],
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
        ];
    }

}
