<?php

namespace tests\codeception\common\unit\models;

use Codeception\Util\Stub;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\ClientFixture;

/**
 * Login form test
 */
class ClientTest extends DineinDbTestCase
{
    use Specify;

    public function testClientCreateDelete()
    {
        Yii::$app->set(
            'globalCache',
            Stub::make(
                '\common\components\cache\GlobalCache',
                [
                    'getLabel'=>function($label){
                        return $label;
                    },
                    'loadClients'=>Stub::atLeastOnce(),
                    'loadRestaurantsByClientKey'=>Stub::atLeastOnce(),
                    'loadCompaniesByClient'=>Stub::atLeastOnce()
                ]
            )
        );
        $fixtures = require Yii::getAlias("@tests/codeception/common/unit/fixtures/data/models/client.php");
        parent::testCreateDelete(
            "\\common\\models\\Client",
            $fixtures[0],
            [
                'name' => 'Sushi 1',
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
//                'dataFile'=>"@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
        ];
    }

}
