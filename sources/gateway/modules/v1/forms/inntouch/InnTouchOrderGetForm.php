<?php
namespace gateway\modules\v1\forms\inntouch;

use common\components\language\T;
use common\enums\RecordType;
use common\models\Order;
use Yii;

class InnTouchOrderGetForm extends InnTouchApiForm
{
    public $orderid;
    public $order;

    protected function customRules() {
        return [
            ['orderid', 'required', 'message' => T::e('Order Id is missing')],
            ['orderid', 'validateOrder']
        ];
    }

    public function validateOrder(){
        if (!Yii::$app->user->isGuest) {
            $this->order = Order::find()->joinWith('restaurant')->where(['order.id' => $this->orderid, 'is_corporate' => true, 'restaurant.client_id' => Yii::$app->user->identity->client_id, 'order.record_type' => RecordType::Active])
                ->andWhere('user_id is NOT NULL')
                ->one();
            if (!isset($this->order)) {
                $this->addError('orderid', T::e('Invalid order'));
            }
        }
    }
}