<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\ClientFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\CuisineFixture;


/**
 * Login form test
 */
class CuisineTest extends DineinDbTestCase
{
    use Specify;

    public function testCuisineCreateDelete()
    {
        $fixtures = require Yii::getAlias('@tests/codeception/common/unit/fixtures/data/models/cuisine.php');
        Yii::$app->set(
            'globalCache',
            \Codeception\Util\Stub::make(
                '\common\components\cache\GlobalCache',
                ['loadCuisines'=>\Codeception\Util\Stub::atLeastOnce(),'getLabel'=>function($label){ return $label; }]
            )
        );
        parent::testCreateDelete(
            "\\common\\models\\Cuisine",
            $fixtures[0],
            [
                'name_key' => 'Cuisine 2',
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "cuisine" => [
                'class' => CuisineFixture::className(),
            ],
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
        ];
    }

}
