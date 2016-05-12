<?php

namespace frontend\controllers;

use Yii;
use common\enums\DeliveryType;

class RestaurantController extends \yii\web\Controller
{
    public function actionSearch($seo_area_id = null, $cuisine_id = null)
    {
        $cuisines = Yii::$app->frontendCache->getCuisines(Yii::$app->language);
        $filters  = Yii::$app->frontendCache->getFilters();
        $filters['delivery_types'] = [
            [
                'name'           => 'Delivery',
                'has_delivery'   => true,
                'has_collection' => false,
            ],
            [
                'name'           => 'Collection',
                'has_delivery'   => false,
                'has_collection' => true,
            ]
        ];

        return $this->render('search', [
            'cuisines'          => $cuisines,
            'filters'           => $filters,
            'deliveryTypeFiler' => $this->getDeliveryTypeData(),
            'seo_area_id'       => $seo_area_id,
            'cuisine_id'        => $cuisine_id,
        ]);
    }

    public function actionView($id, $seoarea = null, $delivery = null, $slug = null)
    {
        $restaurant = Yii::$app->frontendCache->getRestaurant($id);
        $restaurant = $this->_sortSchedules($restaurant);

        return $this->render('view', [
            'model'             => $restaurant,
            'deliveryTypeFiler' => $this->getDeliveryTypeData(),
            'allergies'         => Yii::$app->frontendCache->getAllergies()
        ]);
    }

    /**
     * Organize opening / delivery schedules for restaurant
     * to usable format
     *
     * @param array $model
     *
     * @return array
     */
    private function _sortSchedules($model)
    {
        $schedules = $model['restaurantSchedules'];

        if (empty($schedules)) {
            return $model;
        }

        $result    = [];
        $filtered  = [
            'delivery' => [],
            'opening'  => []
        ];

        $filtered['delivery'] = array_filter($schedules, function($item) {
            return $item['type'] === \common\enums\RestaurantScheduleType::DeliveryTime;
        });

        foreach ($filtered['delivery'] as &$deliveryDay) {
            $day = $deliveryDay['day'];
            $result['delivery'][$day][] = $deliveryDay;
            unset($deliveryDay);
        }

        $filtered['opening'] = array_filter($schedules, function($item) {
            return $item['type'] === \common\enums\RestaurantScheduleType::OpenTime;
        });

        foreach ($filtered['opening'] as &$openingDay) {
            $day = $openingDay['day'];
            $result['opening'][$day][] = $openingDay;
            unset($openingDay);
        }

        $model['restaurantSchedules'] = $result;

        return $model;
    }

    /**
     * @return array
     */
    protected function getDeliveryTypeData()
    {
        $deliveryTypeFilter = [
            'types' => [
                DeliveryType::DeliveryAsap    => 'Delivery Asap',
                DeliveryType::DeliveryLater   => 'Delivery Later',
                DeliveryType::CollectionAsap  => 'Collect Asap',
                DeliveryType::CollectionLater => 'Collect Later',
            ],
            'dates' => Yii::$app->deliveryDatesService->generateDeliveryDates(),
        ];
        return $deliveryTypeFilter;
    }
}
