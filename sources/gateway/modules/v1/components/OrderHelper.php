<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 5/4/2015
 * Time: 2:31 PM
 */

namespace gateway\modules\v1\components;


use admin\common\ArrayHelper;
use common\enums\DeliveryType;
use common\enums\UserType;
use common\models\OrderItem;
use common\models\OrderOption;
use ErrorException;
use gateway\models\SessionUser;
use gateway\modules\v1\actions\common\GetDeliveryTimeAction;
use gateway\modules\v1\actions\common\GetMenusAction;
use gateway\modules\v1\forms\common\GetDeliveryTimeForm;
use gateway\modules\v1\services\OrderService;
use Yii;

class OrderHelper {


    public static function getOrderResponse($client_key, $validate_order = true) {
        /** @var SessionUser $session_user */
        $session_user = Yii::$app->userCache->getUser();

        $orderItems = &$session_user->order_items;

        $restaurant = Yii::$app->globalCache->getRestaurant($client_key, $session_user->restaurant_id);

        $resultItems = [];

        /** @var OrderItem $item */
        foreach ($orderItems as $item) {
            $resultItem = [
                'id' => $item->id,
                'menu_item_id' => $item->menu_item_id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->web_price,
                'web_price' => $item->web_price,
		        'discount' => (float)$item->discount,
		        'special_instructions' => $item->special_instructions
            ];

//            if (count($item->options) > 0) {
                $resultItem['selected_options'] = [];

                /** @var OrderOption $option */
                foreach ($item->options as $option) {
                    $resultItem['selected_options'][] = [
                        'option' => [
                            'id' => $option->id,
                            'name_key' => $option->name,
                            'price' => (float)$option->web_price,
                            'menu_option' => ArrayHelper::convertArToArray($option->menuOption),
                            'web_price' => (float)$option->web_price,
                        ],
                        'quantity' => (float)$option->quantity
                    ];

                    $resultItem['discount'] += (float)$option->discount;
                }
//            }

            $resultItems[] = $resultItem;
        }

        $is_available_for_time = Yii::$app->restaurantService->isRestaurantAvailableForTime(
            $restaurant,
            $session_user->delivery_type,
            date("Y-m-d", strtotime($session_user->later_date_from)),
            date("H:i", strtotime($session_user->later_date_from)) . '-' . date("H:i", strtotime($session_user->later_date_to)));

        $is_delivery = $session_user->delivery_type == DeliveryType::DeliveryAsap || $session_user->delivery_type == DeliveryType::DeliveryLater;

        // Restaurants which are they deliver can not have ETA
        // @todo Move to restaurant delivery service
        if ($restaurant['restaurantDelivery']['has_own'] == '1') {
            $getDeliveryTimeResponse = ['delivery_time'=>null];
        }else{
            $getDeliveryTimeAction = new GetDeliveryTimeAction(null, null);
            $form = new GetDeliveryTimeForm();
            $form->client_key = $client_key;
            $form->restaurant_id = $session_user->restaurant_id;
            $getDeliveryTimeResponse = $getDeliveryTimeAction->getResponseData($form);
        }


        $min_order_value = $is_delivery ? $restaurant['restaurantProperties']['min_delivery_order_value'] : $restaurant['restaurantProperties']['min_collection_order_value'];

        if (Yii::$app->user->identity && Yii::$app->user->identity->user_type == UserType::CorporateMember) {
            $company = Yii::$app->corporateOrderService->getActiveCompany(Yii::$app->user->identity->company_id);
            if (isset($company)) {
                $company_min_order_value = Yii::$app->corporateOrderService->getCompanyMinAmount(Yii::$app->user->identity->company_id);
                if (isset($company_min_order_value)) {
                    $min_order_value = $company_min_order_value;
                }
            }
        }

        // validate order

        $validate_error = null;
        $is_valid = true;

        if ($validate_order) {
            $validate_result = OrderService::validateOrder($client_key);
            if ($validate_result instanceof ErrorException) {
                $validate_error = $validate_result->getMessage();
                $is_valid = false;
            }
        }

        return array_merge($getDeliveryTimeResponse, [
            'items' => $resultItems,
            'delivery_charge' => (float)$session_user->delivery_charge,
            'discount_delivery_charge' => (float)$session_user->discount_delivery_charge,
            'discount_total' => (float)$session_user->discount_items + (float)$session_user->discount_delivery_charge,
            'voucher_code' => $session_user->voucher == null ? null : $session_user->voucher['code'],
            'driver_charge' => (float)$session_user->driver_charge,
            'restaurant_id' => $session_user->restaurant_id,
            'currency_symbol' => Yii::$app->globalCache->getRestaurant($client_key ,$session_user->restaurant_id)['currency']['symbol'],
            'is_available_for_time' => $is_available_for_time,
            'max_order_value' => $is_delivery ? $restaurant['restaurantProperties']['max_delivery_order_value'] : $restaurant['restaurantProperties']['max_collection_order_value'],
            'min_order_value' => $min_order_value,
            'max_order_amount' => $is_delivery ? $restaurant['restaurantProperties']['max_delivery_order_amount'] : $restaurant['restaurantProperties']['max_collection_order_amount'],
            'min_order_amount' => $is_delivery ? $restaurant['restaurantProperties']['min_delivery_order_amount'] : $restaurant['restaurantProperties']['min_collection_order_amount'],

            'is_valid' => $is_valid,
            'validate_error' => $validate_error,
        ]);
    }

}