<?php
namespace gateway\modules\v1\actions\common;

use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\GetDeliveryChargeForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use gateway\modules\v1\forms\common\GetDeliveryTimeForm;
use common\components\DispatchService;
use Yii;

class GetDeliveryTimeAction extends GetApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return GetDeliveryChargeForm
     */
    protected function createRequestForm()
    {
        return new GetDeliveryTimeForm();
    }

	/**
	 * Get postcode
	 *
	 * @param GetDeliveryTimeForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	public function getResponseData($requestForm)
	{
		try {
            $delivery = DispatchService::depotsDeliveryTime([$requestForm->restaurant_id]);
            return [
                'delivery_time' => $delivery
                    && !empty($delivery[$requestForm->restaurant_id])
                    && array_key_exists('dt',$delivery[$requestForm->restaurant_id])
                    ? $delivery[$requestForm->restaurant_id]['dt']
                    : null
            ];
		}
		catch (Exception $ex) {
            Yii::error((string)$ex);
			return $ex;
		}
	}
}