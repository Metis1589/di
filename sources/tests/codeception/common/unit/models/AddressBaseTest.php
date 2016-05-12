<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\ClientFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\AddressBaseFixture;

/**
 * Login form test
 */
class AddressBaseTest extends DineinDbTestCase
{
    use Specify;

    public function testAddressBaseCreateDelete()
    {
        $fixtures = require Yii::getAlias("@tests/codeception/common/unit/fixtures/data/models/address_base.php");

        parent::testCreateDelete(
            "\\common\\models\\AddressBase",
            $fixtures[0],
            [
                'name' => 'base updated',
                'delivery_delay_time' => '00:00:30',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "addressBase" => [
                'class' => AddressBaseFixture::className(),
//                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/address_base.php"
            ]
        ];
    }

}
