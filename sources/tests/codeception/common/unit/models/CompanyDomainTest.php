<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\ClientFixture;
use tests\codeception\common\fixtures\CompanyFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\CompanyDomainFixture;

/**
 * Login form test
 */
class CompanyDomainTest extends DineinDbTestCase
{
    use Specify;

    public function testCompanyDomainCreateDelete()
    {
        $fixtures = require Yii::getAlias("@tests/codeception/common/unit/fixtures/data/models/company_domain.php");
        parent::testCreateDelete(
            "\\common\\models\\CompanyDomain",
            $fixtures[0],
            [
                'domain' => 'www.example.com',
                'company_id' => 2,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "companydomain" => [
                'class' => CompanyDomainFixture::className(),
            ],
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
            "company" => [
                'class' => CompanyFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/company.php"
            ],
        ];
    }

}
