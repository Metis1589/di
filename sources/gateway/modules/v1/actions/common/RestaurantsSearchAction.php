<?php
namespace gateway\modules\v1\actions\common;

use common\enums\DeliveryType;
use common\components\DispatchService;
use ErrorException;
use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\RestaurantsSearchForm;
use gateway\modules\v1\components\GetApiAction;
use Yii;
use yii\db\Exception;

class RestaurantsSearchAction extends GetApiAction
{

    /**
     * Creates request form used to validate request parameters.
     *
     * @return RestaurantsSearchForm
     */
    protected function createRequestForm()
    {
            return new RestaurantsSearchForm();
    }

    /**
     * Returns candidate's gender.
     *
     * @param RestaurantsSearchForm $requestForm form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            if ($requestForm->postcode) {
                $postcode = Yii::$app->locationService->getPostcode($requestForm->postcode);

                if (!$postcode) {
                    throw new ErrorException('Postcode not found');
                }

                // validate for blacklist

                if (Yii::$app->locationService->isInBlacklist($requestForm->client_key, $requestForm->postcode)) {
                    throw new ErrorException('We are sorry, we do not deliver to your location');
                }

                $session_user->postcode = $requestForm->postcode;
                $session_user->latitude = $postcode['latitude'];
                $session_user->longitude = $postcode['longitude'];
            }
            $session_user->delivery_type = $requestForm->delivery_type;
            if (isset($requestForm->later_date)) {
                $session_user->later_date_from    = $requestForm->later_date . ' ' . substr($requestForm->later_time, 0, strpos($requestForm->later_time, '-'));
                $session_user->later_date_to      = $requestForm->later_date . ' ' . substr($requestForm->later_time, strpos($requestForm->later_time, '-') + 1) ;
            }
            else {
                $session_user->later_date_from    = null;
                $session_user->later_date_to    = null;
            }

            Yii::$app->userCache->setUser($session_user);

            $restaurants = Yii::$app->globalCache->getRestaurants($requestForm->client_key);
            if ($requestForm->postcode) {
                $restaurants = Yii::$app->restaurantService->filterAvailableRestaurants($restaurants, $requestForm->postcode, $requestForm->delivery_type, $requestForm->later_date, $requestForm->later_time);
            }
            if ($requestForm->seo_area_id) {
                $restaurants = Yii::$app->restaurantService->filterRestaurantsBySeoArea($restaurants, $requestForm->seo_area_id);
            }
            if ($requestForm->cuisine_id) {
                $restaurants = Yii::$app->restaurantService->filterRestaurantsByCuisine($restaurants, $requestForm->cuisine_id);
            }
            $cuisines    = Yii::$app->globalCache->getCuisinesByLanguage(Yii::$app->language);
            $seo_areas   = Yii::$app->globalCache->getSeoAreas();
            $result      = [];

            // Delivery time
            $restaurantIds = array_map(function($rest){
                return $rest['id'];
            },$restaurants);
            $restaurantIds = array_filter($restaurantIds);
            $delivery = [];
            if($restaurantIds){
                $delivery = DispatchService::depotsDeliveryTime($restaurantIds);
                $delivery = $delivery && is_array($delivery)
                    ? array_map(function($depot){return $depot['dt'];},$delivery) : [];
            }
            // Delivery time end

            foreach ($restaurants as $restaurant) {

                if ($requestForm->postcode) {
                    // filter by delivery type
                    switch ($session_user->delivery_type) {
                        case DeliveryType::CollectionAsap :
                        case DeliveryType::CollectionLater:
                            if ($restaurant['restaurantDelivery']['has_collection'] !== "1") {
                                continue;
                            }
                            break;
                        case DeliveryType::DeliveryAsap :
                        case DeliveryType::DeliveryLater:
                            if ($restaurant['restaurantDelivery']['has_dinein'] !== "1" || $restaurant['restaurantDelivery']['has_own'] !== "1") {
                                continue;
                            }
                            break;
                    }
                }

                $restaurantCuisines = [];

                foreach ($restaurant['restaurantCuisines'] as $restaurantCuisine) {

                    $cuisine_id = $restaurantCuisine['cuisine']['id'];

                    if (array_key_exists($cuisine_id, $cuisines)) {
                        $restaurantCuisines[] = [
                            'id' => $restaurantCuisine['cuisine']['id'],
                            'name' => $cuisines[$cuisine_id]['name'],
                        ];
                    }
                }

                $delivery_charge = null;

                if ($requestForm->postcode) {
                    $delivery_charge = Yii::$app->restaurantService->getDeliveryCharge($restaurant, $requestForm->postcode);
                }

                $eta = $delivery
                        && !empty($delivery[$restaurant['id']])
                        ? $delivery[$restaurant['id']] : null;

                // Restaurants which are they deliver can not have ETA
                if ($restaurant['restaurantDelivery']['has_own'] == '1') {
                    $eta = null;
                }

                $rating   = Yii::$app->globalCache->getRating($restaurant['id']);
                $result[] = [
                    'id'              => $restaurant['id'],
                    'name'            => $restaurant['name'],
                    'eta'             => $eta,
                    'is_newest'       => $restaurant['is_newest'],
                    'price_range'     => (int)$restaurant['price_range'],
                    'first_cuisine'   => count($restaurantCuisines) > 0 ? $restaurantCuisines[0] : '',
                    'cuisines'        => $restaurantCuisines,
                    'seo_area'        => $seo_areas[$restaurant['seo_area_id']]['name'],
                    'slug'            => $restaurant['slug'],
                    'delivery_charge' => $delivery_charge !== null ? number_format($delivery_charge, 2) : null,
                    'is_available_for_time' => (array_key_exists('is_available_for_time', $restaurant) ? $restaurant['is_available_for_time'] : true),
                    'currency_sign'   => Yii::$app->globalCache->getCurrency($restaurant['currency_id'])['symbol'],
                    'rating'          => $rating['rating'],
                    'reviews_count'   => $rating['count'],
                    'is_active'       => $restaurant['record_type'] === 'Active',
                    'has_collection'  => $restaurant['restaurantDelivery']['has_collection'] == '1',
                    'has_delivery'    => $restaurant['restaurantDelivery']['has_dinein'] == '1' || $restaurant['restaurantDelivery']['has_own'] == '1',
                ];
            }

            return $result;
        } catch (Exception $ex) {
            Yii::error($ex->__toString());
            return $ex;
        }
    }
}