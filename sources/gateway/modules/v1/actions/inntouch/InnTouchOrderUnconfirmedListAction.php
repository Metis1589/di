<?php
namespace gateway\modules\v1\actions\inntouch;

use common\enums\OrderStatus;
use common\enums\RecordType;
use common\models\Order;
use gateway\modules\v1\components\inntouch\InnTouchApiAction;
use Exception;
use gateway\modules\v1\forms\common\ActivateAccountForm;
use gateway\modules\v1\forms\inntouch\InnTouchApiForm;
use gateway\modules\v1\models\InnTouchOrderUnconfirmedList;
use Yii;

class InnTouchOrderUnconfirmedListAction extends InnTouchApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return ActivateAccountForm
     */
    protected function createRequestForm()
    {
        return new InnTouchApiForm();
    }

    /**
     * Activate Account.
     *
     * @param InTouchOrderUnconfirmedListForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            $client = Yii::$app->user->identity->client;
            $query = Order::find()->joinWith(['restaurant'])->where(['restaurant.client_id' => $client->id, 'status' => OrderStatus::ProcessingPayment, 'is_corporate' => true, 'order.record_type' => RecordType::Active]);
            if (isset($this->intouchLastRequest)) {
                $query->andWhere(['>', 'order.create_on', $this->intouchLastRequest->time]);
            }
            $orders = $query->all();
            $intouchOrders = InnTouchOrderUnconfirmedList::create($orders);
            return $intouchOrders;
        }
        catch (Exception $ex) {
            return $ex;
        }
    }
}