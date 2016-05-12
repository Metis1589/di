<?php
namespace tests\codeception\common\components;

use tests\codeception\common\fixtures\AddressFixture;
use tests\codeception\common\fixtures\OrderFixture;
use tests\codeception\common\fixtures\RestaurantAddressFixture;
use tests\codeception\common\unit\models\DineinDbTestCase;
use tests\codeception\common\fixtures\CountryFixture;
use Codeception\Specify;
use Codeception\Util\Stub;
use common\models\Country;
use common\models\Order;
use common\models\Address;
use common\models\OrderItem;
use common\components\DispatchService;
use tests\codeception\common\fixtures\AddressBaseFixture;
use tests\codeception\common\fixtures\ClientFixture;
use tests\codeception\common\fixtures\CurrencyFixture;
use tests\codeception\common\fixtures\RestaurantChainFixture;
use tests\codeception\common\fixtures\RestaurantGroupFixture;
use tests\codeception\common\fixtures\SeoAreaFixture;
use tests\codeception\common\fixtures\RestaurantFixture;
use Yii;

class DispatchServiceTest extends DineinDbTestCase
{
    use Specify;
    /**
     * @var \tests\codeception\common\UnitTester
     */
    protected $tester;

    public $appConfig = '@tests/codeception/config/common/unit.php';

    protected $orderItems = [];
    protected $globalCacheMock;
    protected function _before()
    {
        $this->globalCacheMock = Stub::make(
            '\common\components\cache\GlobalCache',
            [
                'loadCurrencies' => Stub::atLeastOnce(),
                'getLabel'       => function ($label) {
                    return $label;
                },
                'getPostcode'=>function($postcode){
                    return [
                        'longitude'=>-0.0903768,
                        'latitude'=>51.5345
                    ];
                }
            ]
        );
    }

    protected function _after() { }

    // tests
    public function testMe()
    {
        $this->initMocks();
        \Yii::$app->set( 'globalCache',$this->globalCacheMock);

        /** @var \common\components\DispatchService $dispatchService */
        $date = new \DateTime();
        $restaurantId = $date->format('YmdHis');
        $restaurant =[
            'id' => $restaurantId,
            'name'           => 'Test depot '.$restaurantId,
            'pickupAddress'        => (object)[
                'address1'=>'Test address 1',
                'address2'=>'Test address 2',
                'address3'=>'Test address 3',
                'city'=>'London',
                'postcode'=>"N1 3LY",
                'country'=>(object)['native_name'=>'Great Britain']
            ],
        ];
        $createResult = DispatchService::createDepot($restaurant);
        codecept_debug($createResult);
        $this->specify('Create restaurant response',function()use ($createResult){
            expect('Response should be array',is_array($createResult))->true();
            expect('Has key id',array_key_exists('id',$createResult))->true();
            expect('Key is integer',is_integer($createResult['id']))->true();
        });

        $submitResult = DispatchService::orderSubmit(
            $this->order->getModel(0),
            $this->orderItems,
            $this->restaurant->getModel(0));
        $this->specify('Submit order',function() use ($submitResult){
            expect('Submit order',$submitResult)->true();

        });

        $updateResult = DispatchService::orderUpdate($this->order->getModel(0));
        $this->specify('Update order',function() use ($updateResult){
            expect('Update order status true',$updateResult)->equals('AWAITING_READY_BY_TIME');
        });

        /** @var \common\components\DispatchService $dispatchService */

        $deliveryTimeResult = DispatchService::depotsDeliveryTime([$restaurantId]);
        codecept_debug($deliveryTimeResult);
        $deliveryTimeResult = DispatchService::depotsDeliveryTime([500]);
        codecept_debug($deliveryTimeResult);

        //Delete
        $deleteResult = DispatchService::deleteDepot($restaurantId);
        codecept_debug($deleteResult);
        $this->specify('Delete message',function()use($deleteResult){
            expect('Response should be array',is_array($deleteResult))->true();
            expect('Has key message',array_key_exists('message',$deleteResult))->true();
            expect('Message is string',is_string($deleteResult['message']))->true();
        });
    }

    protected function initMocks()
    {
        $this->orderItems = [
            new OrderItem(
                [
                    'name'     => 'Item 1',
                    'quantity' => 2
                ]
            ),
            new OrderItem(
                [
                    'name'     => 'Item 2',
                    'quantity' => 3
                ]
            ),
            new OrderItem(
                [
                    'name'     => 'Item 4',
                    'quantity' => 5
                ]
            ),
            new OrderItem(
                [
                    'name'     => 'Item 6',
                    'quantity' => 7
                ]
            ),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "country" => [
                'class' => CountryFixture::className(),
                'dataFile'=>'@tests/codeception/common/unit/fixtures/data/models/country.php'
            ],
            'address'=>[
                'class'=>AddressFixture::className(),
                'dataFile'=>'@tests/codeception/common/unit/fixtures/data/models/address.php'
            ],
            'seoArea'=>[
                'class'=>SeoAreaFixture::className(),
                'dataFile'=>"@tests/codeception/common/unit/fixtures/data/models/seo_area.php"
            ],
            "client" => [
                'class' => ClientFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/client.php"
            ],
            "currency" => [
                'class' => CurrencyFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/currency.php"
            ],
            "restaurantchain" => [
                'class' => RestaurantChainFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant_chain.php"
            ],
            "restaurantgroup" => [
                'class' => RestaurantGroupFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant_group.php"
            ],
            "addressbase" => [
                'class' => AddressBaseFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/address_base.php"
            ],
            'restaurantaddress'=>[
                'class'=>RestaurantAddressFixture::className(),
                'dataFile'=>'@tests/codeception/common/unit/fixtures/data/models/restaurant_address.php'
            ],
            "restaurant" => [
                'class' => RestaurantFixture::className(),
                'dataFile' => "@tests/codeception/common/unit/fixtures/data/models/restaurant.php"
            ],
            'order'=>[
                'class'=>OrderFixture::className(),
                'dataFile'=>'@tests/codeception/common/unit/fixtures/data/models/order.php',
            ]
        ];
    }
}