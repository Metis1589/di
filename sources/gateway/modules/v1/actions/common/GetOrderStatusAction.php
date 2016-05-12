<?php
namespace gateway\modules\v1\actions\common;

use common\models\MenuOption;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderOption;
use gateway\models\SessionUser;
use gateway\modules\v1\components\OrderHelper;
use gateway\modules\v1\forms\common\GetOrderForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use gateway\modules\v1\forms\common\GetOrderStatusForm;
use Yii;

class GetOrderStatusAction extends GetApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return GetOrderStatusForm
     */
    protected function createRequestForm()
    {
        return new GetOrderStatusForm();
    }

    /**
     * Returns order information.
     *
     * @param GetOrderStatusForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            $order = Order::findOne(['order_number' => $requestForm->order_number]);

            if (!$order) {
                return null;
            }

            if ($requestForm->clear_order) {
                $session_user = Yii::$app->userCache->getUser();
                if (isset($session_user->order) && isset($session_user->order->order_number) && $requestForm->order_number == $session_user->order->order_number) {
                    $session_user->clearOrder();
                }
            }

            $physicalAddress = $order->restaurant->physicalAddress;
            return [
                'delivery_type'       => $order->delivery_type,
                'later_date_from'     => $order->later_date_from,
                'later_date_to'       => $order->later_date_to,
                'status'              => $order->status,
                'restaurant_name'     => $order->restaurant->name,
                'restaurant_phone'    => $physicalAddress && $physicalAddress->phone ? $physicalAddress->phone : '',
                'restaurant_delivery' => $order->restaurant->restaurantDelivery->has_own
            ];
        } catch (Exception $ex) {
            Yii::error($ex->__toString());
            return $ex;
        }
    }
}