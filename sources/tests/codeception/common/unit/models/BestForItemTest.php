<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\ClientFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\BestForItemFixture;

/**
 * Login form test
 */
class BestForItemTest extends DineinDbTestCase
{
    use Specify;

    public function testBestForItemCreateDelete()
    {

        $fixtures = require Yii::getAlias("@tests/codeception/common/unit/fixtures/data/models/best_for_item.php");

        parent::testCreateDelete(
            "\\common\\models\\BestForItem",
            $fixtures[0],
            [
                'name_key' => 'Best For 2',
                'client_id' => 2,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "bestforitem" => [
                'class' => BestForItemFixture::className(),
//                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/bestforitem.php"
            ],
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
        ];
    }

}
