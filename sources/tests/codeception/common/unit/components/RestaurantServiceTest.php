<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   5/7/15
 * @time   3:41 PM
 */
namespace tests\codeception\common\unit\components;

use common\enums\DeliveryType;
use Yii;
use \Codeception\Util\Fixtures;
use \Codeception\Util\Stub;

/**
 * Class RestaurantServiceTest
 *
 * @package tests\codeception\common\unit\components
 *
 */
class RestaurantServiceTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/codeception/config/gateway/unit.php';

    protected function _before()
    {
        parent::_before();
        Fixtures::add('globalCacheRestaurants',require dirname(__DIR__).'/fixtures/globalCacheFixture.php');
        $result = [];
        foreach (Fixtures::get('globalCacheRestaurants') as $restaurant_id=>$restaurantFixture) {
            if(isset($restaurantFixture['restaurantDelivery'])){
                Fixtures::add(
                    'restaurantDelivery_'.$restaurantFixture['restaurantDelivery']['id'],
                    $restaurantFixture['restaurantDelivery']
                );
            }
            $restaurantFixture['restaurantSchedules'] = require dirname(__DIR__).'/fixtures/restaurantSchedule_'.$restaurant_id.'.php';
            $result[$restaurant_id]=$restaurantFixture;
        }
        Fixtures::add('globalCacheRestaurants',$result);
        Fixtures::add('N13LY',require dirname(__DIR__).'/fixtures/postcode_N13LY_.php');
    }


    public function testFilterAvailableRestaurants()
    {
        $globalCacheMock = Stub::make(
            '\common\components\cache\GlobalCache',
            [
                'getLabel'=>function($label){ return $label; },
                'getRestaurants'=>function(){
                    return Fixtures::get('globalCacheRestaurants');
                },
                'getPostcode'=>function(){
                    return Fixtures::get('N13LY');
                },
            ]
        );
        $locationServiceMock = Stub::make(
            'common\components\LocationService',
            [
                'getPostcode'=>function(){
                    return Fixtures::get('N13LY');
                }
            ]
        );

        Yii::$app->set('globalCache',$globalCacheMock);
        Yii::$app->set('locationService',$locationServiceMock);
        /** @var \gateway\components\RestaurantService $restaurantService */
        $restaurantService = Yii::$app->restaurantService;
        $postcode    = 'N13LY';
        $restaurants = Yii::$app->globalCache->getRestaurants(3);
        $restaurants = $restaurantService->filterAvailableRestaurants($restaurants, $postcode,DeliveryType::DeliveryAsap,null,null);
        $this->assertCount(3,$restaurants);
    }
}
