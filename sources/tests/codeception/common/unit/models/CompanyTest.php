<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\ClientFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\CompanyFixture;
use Codeception\Util\Stub;

/**
 * Login form test
 */
class CompanyTest extends DineinDbTestCase
{
    use Specify;

    public function testCompanyCreateDelete()
    {
        $fixtures = require Yii::getAlias('@tests/codeception/common/unit/fixtures/data/models/company.php');
        Yii::$app->set(
            'globalCache',
            Stub::make(
                '\common\components\cache\GlobalCache',
                ['loadCompany'=>Stub::atLeastOnce()]
                )
            );
        parent::testCreateDelete(
            "\\common\\models\\Company",
            $fixtures[0],
            [
                'client_id' => 2,
                'min_order_morning_amount'=>14,
                'min_order_evening_amount'=>15,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "company" => [
                'class' => CompanyFixture::className(),
            ],
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
        ];
    }

}
