<?php

namespace gateway\modules\v1\actions\common;

use common\models\Feedback;
use common\models\Order;
use gateway\modules\v1\forms\common\AddReviewForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;
use yii\base\ErrorException;

class AddReviewAction extends PostApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return AddReviewForm
     */
    protected function createRequestForm() {
        return new AddReviewForm();
    }

    /**
     * Add review.
     *
     * @param AddReviewForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm) {
        try {
            $feedback = new Feedback();

            $order = Order::find()->where(['order_number' => $requestForm->order_number])->one();

            if (!$order) {
                throw new ErrorException('Order not found');
            }

            $feedback->restaurant_id = $requestForm->restaurant_id;
            $feedback->user_id = Yii::$app->user->identity == null ? null : Yii::$app->user->getId();
            $feedback->order_id = $order->id;
            $feedback->title = $requestForm->title;
            $feedback->text = $requestForm->text;
            $feedback->rating = is_numeric($requestForm->rating) ? $requestForm->rating : 0;

            if (!$feedback->save()) {
                throw new ErrorException('Error saving review');
            }

            Yii::$app->globalCache->addUpdateCacheAction("loadRatings($requestForm->restaurant_id)");

            return true;
        } catch (Exception $ex) {
            return $ex;
        }
    }

}
