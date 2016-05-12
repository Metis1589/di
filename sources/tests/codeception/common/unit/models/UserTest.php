<?php

namespace tests\codeception\common\unit\models;

use common\enums\UserType;
use tests\codeception\common\fixtures\ClientFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\UserFixture;

/**
 * Login form test
 */
class UserTest extends DineinDbTestCase
{
    use Specify;

    public function testUserCreateDelete()
    {
        parent::testCreateDelete(
            '\common\models\User',
            [
                'username'    => 'username@aa.aa',
                'password'    => 'password',
                'user_type'   => UserType::Admin,
                'record_type' => 'Active',
                'first_name'  => 'Test',
                'last_name'   => 'User',
                'client_id'   => 1
            ],
            [
                'username'  => 'username_updated@aa.aa',
                'password'  => 'password_updated',
                'user_type' => UserType::Member,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
            'user' => [
                'class' => UserFixture::className(),
//                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/user.php'
            ],
        ];
    }

}
