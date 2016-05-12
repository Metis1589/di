<?php

namespace tests\codeception\common\unit\models;

use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\VatFixture;

/**
 * Login form test
 */
class VatTest extends DineinDbTestCase
{
    use Specify;

    public function testVatCreateDelete()
    {
        parent::testCreateDelete(
            "\\common\\models\\Vat",
            [
                'type' => 'Zero',
                'value' => 15,
                'record_type' => 'Active',
            ],
            [

            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "vat" => [
                'class' => VatFixture::className(),
//                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/vat.php"
            ],
        ];
    }

}
