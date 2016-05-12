<?php
namespace gateway\modules\v1\actions\common;

use common\enums\RecordType;
use common\models\Feedback;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class GetReviewsByUserAction extends GetApiAction
{


	/**
	 * Returns candidate's gender.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            $feedbacks = Feedback::find()
                ->joinWith('restaurant')
                ->where([
                    'user_id' => Yii::$app->user->identity->id,
                    'feedback.record_type' => RecordType::Active,
                    'restaurant.record_type' => RecordType::Active
                ])
                ->asArray()
                ->all();

            return $feedbacks;
		}
		catch (Exception $ex) {
            Yii::error($ex->__toString());
			return $ex;
		}
	}
}