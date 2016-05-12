<?php

namespace tests\codeception\common\unit\models;

use tests\codeception\common\fixtures\RestaurantFixture;
use tests\codeception\common\unit\DbTestCase;
use common\components\identity\RbacHelper;
use common\models\User;
use tests\codeception\common\fixtures\ClientFixture;
use tests\codeception\common\fixtures\RestaurantChainFixture;
use tests\codeception\common\fixtures\RestaurantGroupFixture;
use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\UserFixture;
use Codeception\Util\Stub;

/**
 * Login form test
 */
class UserPermissionsTest extends DbTestCase
{
    use Specify;

    public function testUserPermissions()
    {
        Yii::$app->set(
            'globalCache',
            Stub::make(
                '\common\components\cache\GlobalCache',
                ['loadCuisines'=>Stub::atLeastOnce(),'getLabel'=>function($label){ return $label; }]
            )
        );
        $admin = User::findByUsername('admin');
        $member = User::findByUsername('member');
        $corporateAdmin = User::findByUsername('corporateAdmin');
        $corporateMember = User::findByUsername('corporateMember');
        $restaurantGroupAdminOf111 = User::findByUsername('restaurantGroupAdmin111');
        $restaurantGroupAdminOf11 = User::findByUsername('RestaurantGroupAdmin11');
        $restaurantChainAdminOf1 = User::findByUsername('restaurantChainAdminOf1');
        $restaurantChainAdminOf2 = User::findByUsername('restaurantChainAdminOf2');
        $restaurantAdminOf1 = User::findByUsername('restaurantAdminOf1');
        $finance = User::findByUsername('finance');
        $clientAdmin1 = User::findByUsername('clientadmin1');
        $clientAdmin2 = User::findByUsername('clientadmin2');


        $this->specify('isRestaurantAllowed should work', function () use ($admin, $member, $restaurantGroupAdminOf111, $restaurantGroupAdminOf11, $restaurantChainAdminOf1, $restaurantChainAdminOf2, $restaurantAdminOf1, $finance, $clientAdmin1, $clientAdmin2) {
            expect('admin access should be allowed for 1', RbacHelper::isRestaurantAllowed($admin,33))->true();
            expect('admin access should be allowed for 2', RbacHelper::isRestaurantAllowed($admin, 2))->true();

            expect('member access should NOT be allowed for 1', RbacHelper::isRestaurantAllowed($member, 33))->false();
            expect('member access should NOT be allowed for 2', RbacHelper::isRestaurantAllowed($member, 2))->false();

            // todo corporate admin/member

            expect('restaurantGroupAdmin Of 111 access should be allowed for 1', RbacHelper::isRestaurantAllowed($restaurantGroupAdminOf111, 33))->true();
            expect('restaurantGroupAdmin Of 111 access should NOT be allowed for 2', RbacHelper::isRestaurantAllowed($restaurantGroupAdminOf111, 11))->false();

            expect('restaurantGroupAdmin Of 11 access should be allowed for 1', RbacHelper::isRestaurantAllowed($restaurantGroupAdminOf11, 33))->true();
            expect('restaurantGroupAdmin Of 11 access should be allowed for 2', RbacHelper::isRestaurantAllowed($restaurantGroupAdminOf11, 11))->true();

            expect('restaurant Chain Admin Of 1 access should be allowed for 1', RbacHelper::isRestaurantAllowed($restaurantChainAdminOf1, 33))->true();
            expect('restaurant Chain Admin Of 1 access should be allowed for 2', RbacHelper::isRestaurantAllowed($restaurantChainAdminOf1, 11))->true();

            expect('restaurant Chain Admin Of 2 access should NOT be allowed for 1', RbacHelper::isRestaurantAllowed($restaurantChainAdminOf2, 33))->false();
            expect('restaurant Chain Admin Of 2 access should NOT be allowed for 2', RbacHelper::isRestaurantAllowed($restaurantChainAdminOf2, 11))->false();

            expect('restaurant Admin Of 1 access should be allowed for 1', RbacHelper::isRestaurantAllowed($restaurantAdminOf1, 33))->true();
            expect('restaurant Admin Of 1 access should NOT be allowed for 2', RbacHelper::isRestaurantAllowed($restaurantAdminOf1, 11))->false();

            expect('finance access should be NOT allowed for 1', RbacHelper::isRestaurantAllowed($finance, 33))->false();
            expect('finance access should be NOT allowed for 2', RbacHelper::isRestaurantAllowed($finance, 11))->false();

            expect('clientAdmin1 access should be allowed for 1', RbacHelper::isRestaurantAllowed($clientAdmin1, 33))->true();
            expect('clientAdmin1 access should NOT be allowed for 2', RbacHelper::isRestaurantAllowed($clientAdmin1, 11))->false();

            expect('clientAdmin2 access should NOT be allowed for 1', RbacHelper::isRestaurantAllowed($clientAdmin2, 33))->false();
            expect('clientAdmin2 access should be allowed for 2', RbacHelper::isRestaurantAllowed($clientAdmin2, 11))->true();
        });
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/user.php'
            ],
            'client' => [
                'class' => ClientFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/client.php'
            ],
            'restaurantchain' => [
                'class' => RestaurantChainFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/restaurant_chain.php'
            ],
            'restaurantgroup' => [
                'class' => RestaurantGroupFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/restaurant_group.php'
            ],
            'restaurant' => [
                'class' => RestaurantFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/restaurant.php'
            ],
        ];
    }

}
