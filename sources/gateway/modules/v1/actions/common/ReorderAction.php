<?php
namespace gateway\modules\v1\actions\common;

use common\models\Order;
use common\models\OrderItem;
use common\models\OrderOption;
use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\ReorderForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use gateway\modules\v1\services\VoucherCalculator;
use Yii;
use yii\base\ErrorException;

class ReorderAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return ReorderForm
	 */
	protected function createRequestForm()
	{
		return new ReorderForm();
	}

	/**
	 * Reorder previous order.
	 *
	 * @param ReorderForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            $sourceOrder = Order::find()
                ->joinWith('restaurant')
                ->joinWith('orderItems')
                ->joinWith('orderItems.menuItem')
                ->joinWith('orderItems.orderOptions')
                ->joinWith('orderItems.orderOptions.menuOption')
                ->where(['order_id' => $requestForm->order_id, 'user_id' => Yii::$app->user->identity->id])
                ->asArray()->one();

            if ($sourceOrder == null) {
                throw new ErrorException('Order not found');
            }

            // todo check if restaurant enabled

            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();
            if (empty($session_user->postcode)) {
                $session_user->postcode = $sourceOrder['postcode'];
            }

            if (empty($session_user->delivery_type)) {
                $session_user->delivery_type = $sourceOrder['delivery_type'];
            }

            VoucherCalculator::clearVoucher($session_user);

            $session_user->restaurant_id = $sourceOrder['restaurant']['id'];

            $restaurant = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $session_user->restaurant_id);
            $session_user->delivery_charge = Yii::$app->restaurantService->getDeliveryCharge($restaurant, $session_user->postcode);

            $session_user->clearOrder();

            $id = 1;

            //todo validate if all order items available and prices match

            foreach ($sourceOrder['orderItems'] as $sourceOrderItem) {

                $orderItem = new OrderItem();
                $orderItem->id = $id++;
                $orderItem->quantity = $sourceOrderItem['quantity'];
                $orderItem->menu_item_id = $sourceOrderItem['menuItem']['id'];
                $orderItem->name = Yii::$app->globalCache->getLabel($sourceOrderItem['menuItem']['name_key']);
                $orderItem->web_price = (float)$sourceOrderItem['menuItem']['web_price'];
                $orderItem->restaurant_price = (float)$sourceOrderItem['menuItem']['restaurant_price'];
                $orderItem->is_alcohol = $sourceOrderItem['menuItem']['is_alcohol'];
                $orderItem->web_total = $orderItem->web_price;
                $orderItem->restaurant_total = $orderItem->restaurant_price;
                $orderItem->cook_time = $sourceOrderItem['menuItem']['cook_time'];

                foreach ($sourceOrderItem['orderOptions'] as $sourceOrderOption) {

                    $option = new OrderOption();

                    $option->menu_option_id = $sourceOrderOption['menuOption']['id'];
                    $option->quantity = $sourceOrderOption['quantity'];

                    $option->web_price = $sourceOrderOption['menuOption']['web_price'];
                    $option->restaurant_price = $sourceOrderOption['menuOption']['restaurant_price'];
                    $option->name = $sourceOrderOption['menuOption']['name_key'];

                    $orderItem->web_total += $option->web_price * $option->quantity;
                    $orderItem->restaurant_total += $option->restaurant_price * $option->quantity;
                    $orderItem->options[] = $option;
                }

                $session_user->order_items[$orderItem->id] = $orderItem;

            }

            Yii::$app->userCache->setUser($session_user);

            return true;
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}