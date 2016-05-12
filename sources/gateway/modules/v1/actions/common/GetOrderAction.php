<?php
namespace gateway\modules\v1\actions\common;

use common\models\MenuOption;
use common\models\OrderItem;
use common\models\OrderOption;
use ErrorException;
use Exception;
use gateway\models\SessionUser;
use gateway\modules\v1\components\OrderHelper;
use gateway\modules\v1\forms\common\GetOrderForm;
use gateway\modules\v1\components\GetApiAction;
use Yii;

class GetOrderAction extends GetApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return GetOrderForm
	 */
	protected function createRequestForm()
	{
		return new GetOrderForm();
	}

	/**
	 * Returns order information.
	 *
	 * @param GetOrderForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            $restaurant_id = $requestForm->restaurant_id;

            if (!isset($restaurant_id)) {
                $restaurant_id = $session_user->restaurant_id;
            }

            if (is_null($restaurant_id)) {
                throw new ErrorException('Restaurant ID is missing');
            }

            $restaurant = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $restaurant_id);

            if ($session_user->restaurant_id != null && $session_user->restaurant_id != $restaurant_id) {
                $session_user->order_items = [];
            }

            $session_user->restaurant_id = $restaurant_id;
            $session_user->delivery_charge = Yii::$app->restaurantService->getDeliveryCharge($restaurant, $session_user->postcode);

            Yii::$app->userCache->setUser($session_user);

            return OrderHelper::getOrderResponse($requestForm->client_key);
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}