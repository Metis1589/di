<?php
namespace gateway\modules\v1\actions\common;

use ErrorException;
use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\GetDeliveryChargeForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use gateway\modules\v1\forms\common\GetPostcodeForm;
use Yii;

class GetDeliveryChargeAction extends GetApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return GetDeliveryChargeForm
     */
    protected function createRequestForm()
    {
        return new GetDeliveryChargeForm();
    }

	/**
	 * Get postcode
	 *
	 * @param GetDeliveryChargeForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();
            $postcode     = Yii::$app->locationService->getPostcode($requestForm->postcode);



            if (!$postcode) {
                throw new Exception('Postcode not found');
            }

            if (Yii::$app->locationService->isInBlacklist($requestForm->client_key, $requestForm->postcode)) {
                throw new ErrorException('We are sorry, we do not deliver to your location');
            }

            $session_user->postcode      = $requestForm->postcode;
            $session_user->latitude      = $postcode['latitude'];
            $session_user->longitude     = $postcode['longitude'];
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
            $restaurant_id = $session_user->restaurant_id;
            if (!empty($requestForm->restaurant_id)) {
                $restaurant_id = $requestForm->restaurant_id;
            }
            $restaurant = Yii::$app->globalCache->getRestaurant($requestForm->client_key,$restaurant_id);
            $delivery_charge = Yii::$app->restaurantService->getDeliveryCharge($restaurant, $requestForm->postcode);
            return [
                'delivery_charge' => $delivery_charge
            ];
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}