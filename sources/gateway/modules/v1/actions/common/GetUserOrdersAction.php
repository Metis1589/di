<?php
namespace gateway\modules\v1\actions\common;

use common\models\Order;
use gateway\modules\v1\forms\common\GetOrderHistoryForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class GetUserOrdersAction extends GetApiAction
{

	/**
	 * get user orders.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
        try {
            $orders = Order::find()
                ->joinWith('restaurant')
                ->joinWith('restaurant.currency')
                ->joinWith('orderItems.menuItem',true, 'INNER JOIN')
                ->where(['user_id' => Yii::$app->user->identity->id])->andWhere('status != :status', ['status' => \common\enums\OrderStatus::ProcessingPayment])
                ->orderBy('order.create_on DESC')
                ->asArray()->all();

            $result = [];

            foreach ($orders as $order) {
                foreach($order['orderItems'] as &$orderItem) {
                    $orderItem['menuItem']['name_key'] = Yii::$app->globalCache->getLabel($orderItem['menuItem']['name_key']);
                }
                $result[] = [
                    'id' => $order['id'],
                    'currency_symbol' => $order['restaurant']['currency']['symbol'],
                    'restaurant_id' => $order['restaurant']['id'],
                    'restaurant_name' => $order['restaurant']['name'],
                    'items' => $order['orderItems'],
                    'date' => $order['create_on'],
                    'total' => '',
                ];
            }

            return $result;
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}