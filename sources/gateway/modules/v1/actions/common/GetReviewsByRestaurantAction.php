<?php
namespace gateway\modules\v1\actions\common;

use common\enums\RecordType;
use common\models\Feedback;
use gateway\modules\v1\forms\common\GetReviewsByRestaurantForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class GetReviewsByRestaurantAction extends GetApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return GetReviewsByRestaurantForm
	 */
	protected function createRequestForm()
	{
		return new GetReviewsByRestaurantForm();
	}

	/**
	 * Returns candidate's gender.
	 *
	 * @param GetReviewsByRestaurantForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            $feedbacks = Feedback::find()->where(['restaurant_id' => $requestForm->restaurant_id, 'record_type' => RecordType::Active])->with('user')->asArray()->all();
            $rating   = Yii::$app->globalCache->getRating($requestForm->restaurant_id);

            return [
                'reviews' => $feedbacks,
                'rating' => $rating,
            ];
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}