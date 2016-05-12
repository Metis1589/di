<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\ClientFixture;
use tests\codeception\common\fixtures\CompanyFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\CompanyUserGroupFixture;

/**
 * Login form test
 */
class CompanyUserGroupTest extends DineinDbTestCase
{
    use Specify;

    public function testCompanyUserGroupCreateDelete()
    {
        $fixtures = require Yii::getAlias("@tests/codeception/common/unit/fixtures/data/models/company_user_group.php");
        parent::testCreateDelete(
            "\\common\\models\\CompanyUserGroup",
            $fixtures[0],
            [
                'name' => 'company user group 1',
                'company_id' => 2,
                'max_order_per_day_per_user'=>15
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "companyusergroup" => [
                'class' => CompanyUserGroupFixture::className(),
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
