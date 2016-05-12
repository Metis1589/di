<?php
namespace gateway\modules\v1\actions\common;
use common\enums\UserType;
use gateway\modules\v1\components\GetApiAction;
use gateway\modules\v1\forms\common\GetOrderListForm;
use Exception;
use gateway\modules\v1\services\OrderService;
use Yii;

class GetOrderListAction extends GetApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return GetOrderListForm
	 */
	protected function createRequestForm()
	{
		return new GetOrderListForm();
	}

	/**
	 * Returns candidate's gender.
	 *
	 * @param GetOrderListForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
    {
        try {

            $client_id = null;

            if ($requestForm->client_key) {
                $client = Yii::$app->globalCache->getClient($requestForm->client_key);
                $client_id = $client['id'];
            }

            $orders = OrderService::getOrders($client_id, null, $requestForm->custom_fields, $requestForm->filter_statuses);

            $user = Yii::$app->user->identity;

            if ($user->user_type == UserType::RestaurantApp){
                return OrderService::getRestaurantAppResponse($orders, $user);
            }

            $filter_orders = [];

            foreach ($orders as &$order) {
                if (\common\components\identity\RbacHelper::isRestaurantAllowed($user, $order['restaurant_id'])) {

                    if (isset($order['delivery_address_data']) && !empty($order['delivery_address_data'])) {
                        $order['delivery_address_data'] = unserialize(html_entity_decode($order['delivery_address_data']));
                    }

                    if (isset($order['billing_address_data']) && !empty($order['billing_address_data'])) {
                        $order['billing_address_data'] = unserialize(html_entity_decode($order['billing_address_data']));
                    }

                    if (isset($order['voucher_data']) && !empty($order['voucher_data'])) {
                        $order['voucher_data'] = unserialize(html_entity_decode($order['voucher_data']));
                    }
                    
                    $order['current_status'] = $order['status'];

                    $order['restaurant_refund_diff'] = 0;
                    $order['client_refund_diff'] = 0;

                    if (isset($order['orderHistories']) && !empty($order['orderHistories'])) {
                        foreach ($order['orderHistories'] as &$history) {
                            $history['status'] = \common\enums\OrderStatus::getAbbr($history['status']);
                            $history['create_on'] = date("H:i", strtotime($history['create_on']));
                        }
                    }

                    array_push($filter_orders, $order);
                }
            }

            return [
                'orders' => $filter_orders,
            ];
        } catch (Exception $ex) {
            return $ex;
        }
    }
}