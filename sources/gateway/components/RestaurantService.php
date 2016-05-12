<?php

namespace gateway\components;

use common\enums\Day;
use common\enums\DeliveryType;
use common\enums\RestaurantDeliveryRateType;
use common\enums\RestaurantScheduleType;
use DateInterval;
use DateTime;
use Yii;
use yii\base\Component;

class RestaurantService extends Component {

    /**
     * Find restaurants corresponded to the provided postcode
     *
     * @param $restaurants Array of Restaurants as array
     * @param $postcode
     * @return array
     */
    public function filterAvailableRestaurants($restaurants, $postcode, $delivery_type, $later_date, $later_time) {
        $result = [];
        foreach($restaurants as $restaurant) {
            if ($this->isRestaurantAvailableForPostcode($restaurant, $postcode)) {
                $restaurant['is_available_for_time'] = $this->isRestaurantAvailableForTime($restaurant, $delivery_type, $later_date, $later_time);
                $result[] = $restaurant;
            }
        }
        return $result;
    }

    public function filterRestaurantsBySeoArea($restaurants, $seo_area_id) {
        $result = [];
        foreach($restaurants as $restaurant) {
            if ($restaurant['seo_area_id'] == $seo_area_id) {
                $result[] = $restaurant;
            }
        }
        return $result;
    }

    public function filterRestaurantsByCuisine($restaurants, $cuisine_id) {
        $result = [];
        foreach($restaurants as $restaurant) {
            foreach ($restaurant['restaurantCuisines'] as $cuisine) {
                if ($cuisine['cuisine_id'] == $cuisine_id) {
                    $result[] = $restaurant;
                }
            }
        }
        return $result;
    }

    /**
     * get delivery charge
     *
     * @param $restaurant
     * @param $postcode
     * @return mixed
     */
    public function getDeliveryCharge($restaurant, $postcode) {
        $charge = $this->getDeliveryChargeModel($restaurant, $postcode);
        if (is_array($charge)) {
            return $charge['charge'];
        }
        return $charge;
    }

    /**
     * get delivery charge model as array
     *
     * @param $restaurant
     * @param $postcode
     * @return mixed
     */
    public function getDeliveryChargeModel($restaurant, $postcode) {
        // 1. calculate distance
        $postcode = Yii::$app->locationService->getPostcode($postcode);
        if (!$postcode) {
            return null;
        }

        // 2. find charge
        $deliveryService = $restaurant['restaurantDelivery'];
        if (!isset($deliveryService)) {
            return null;
        }

        if ((isset($deliveryService['has_dinein']) || isset($deliveryService['has_own']))
            && ($deliveryService['has_dinein'] === true || $deliveryService['has_own'] === true)) {
            switch ($deliveryService['rate_type']){
                case RestaurantDeliveryRateType::Free:
                    return 0;
                case RestaurantDeliveryRateType::Fixed:
                    return $deliveryService['fixed_charge'];
                case RestaurantDeliveryRateType::Float:
                    $charges = $deliveryService['restaurantDeliveryCharges'];
                    if (empty($charges)) {
                        return 0;
                    }
                    $distance = $this->calculateDistanceBetweenAddressAndPostcode($restaurant['addressBase'], $restaurant['pickupAddress']['postcode']) +
                        $this->calculateDistanceBetweenAddressAndPostcode($restaurant['pickupAddress'], $postcode['postcode']);

                    foreach ($charges as $charge) {
                        if (0.00062137*$distance <= $charge['distance_in_miles']) {
                            return (float)$charge;
                        }
                    }
                    return $charges[count($charges) -1];
            }
        }

        if (isset($deliveryService['has_collection']) && $deliveryService['has_collection'] === true) {
            return 0;
        }

        return null;
    }


    /**
     * Is distance between restaurant and postcode is less than max distance for the restaurant
     *
     * @param $restaurant
     * @param $postcode
     * @return bool
     */
    public function isRestaurantAvailableForPostcode($restaurant, $postcode){
        $deliveryService = $restaurant['restaurantDelivery'];
        if (!isset($deliveryService)) {
            return false;
        }

        $distanceInMeters = $this->calculateDistanceBetweenAddressAndPostcode($restaurant['pickupAddress'], $postcode);

        return (0.00062137*$distanceInMeters <= $deliveryService['range']);
    }

    /**
     * Is restaurant opened for specified time for specified delivery type
     *
     * @param $restaurant
     * @param $delivery_type
     * @param $later_date
     * @param $later_time
     * @return bool
     */
    public function isRestaurantAvailableForTime($restaurant, $delivery_type, $later_date, $later_time) {
        $schedules = $this->getScheduleByCurrentDate($restaurant, $delivery_type, $later_date);
        foreach($schedules as $schedule) {
            $from = $schedule['from'];
            $to = $schedule['to'];
            if ($to < $from) {
                $to = $schedule['to']->add(new DateInterval('P1D'));
            }
            if ($delivery_type == DeliveryType::DeliveryAsap || $delivery_type == DeliveryType::CollectionAsap) {
                $now = new DateTime();
            }
            else {

                $now = new DateTime($later_date . ' ' . explode('-', $later_time)[0]);
                $now2 = new DateTime($later_date . ' ' . explode('-', $later_time)[1]);
            }

            if ($now >= $from && $now < $to) {
                return true;
            }
            if (isset($now2) && $now2 >= $from && $now2 < $to) {
                return true;
            }
        }
        return false;
    }

    private function getScheduleByCurrentDate($restaurant, $delivery_type, $later_date) {
        if ($delivery_type == DeliveryType::DeliveryAsap || $delivery_type == DeliveryType::CollectionAsap) {
            $todayDay = (new DateTime('today'));
        }
        else { // later
            $todayDay = (new DateTime($later_date));
        }
        $yesterdayDay = (new DateTime($todayDay->format('Y-m-d')))->sub(new DateInterval('P1D'));
        $result = [];
        $schedules = $restaurant['restaurantSchedules'];

        foreach($schedules as $schedule) {
            if (($schedule['type'] == RestaurantScheduleType::DeliveryTime && ($delivery_type == DeliveryType::DeliveryAsap || $delivery_type == DeliveryType::DeliveryLater)) ||
                ($schedule['type'] == RestaurantScheduleType::OpenTime && ($delivery_type == DeliveryType::CollectionAsap || $delivery_type == DeliveryType::CollectionLater))) {
                $scheduleDay = Day::getDay($schedule['day']);
                if ($scheduleDay == $todayDay->format('w')) {
                    $schedule['from'] = new DateTime($todayDay->format('Y-m-d') . ' ' . $schedule['from']);
                    $schedule['to'] = new DateTime($todayDay->format('Y-m-d') . ' ' . $schedule['to']);
                    $result[] = $schedule;
                } else if ($scheduleDay == $yesterdayDay->format('w')) {
                    $schedule['from'] = new DateTime($yesterdayDay->format('Y-m-d') . ' ' . $schedule['from']);
                    $schedule['to'] = new DateTime($yesterdayDay->format('Y-m-d') . ' ' . $schedule['to']);
                    $result[] = $schedule;
                }
            }
        }

        return $result;
    }

    /**
     * Calculate distance between address and postcode
     *
     * @param $address should contain postcode key and optional latitude,longitude
     * @param $postcode
     * @return float in meters
     */
    private function calculateDistanceBetweenAddressAndPostcode($address, $postcode) {
        $postcode = Yii::$app->locationService->getPostcode($postcode);
        if (!$postcode) {
            return false;
        }

        if (!isset($address['latitude']) || !isset($address['longitude'])) {
            $restaurantPostcode = Yii::$app->locationService->getPostcode($address['postcode']);
            if (!$restaurantPostcode) {
                return false;
            }
            $address['latitude'] = $restaurantPostcode['latitude'];
            $address['longitude'] = $restaurantPostcode['longitude'];
        }
        return Yii::$app->locationService->getDistance($postcode, $address);
    }
}