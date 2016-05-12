<?php
namespace gateway\modules\v1\controllers;

use common\components\language\T;
use common\enums\UserType;
use gateway\modules\v1\actions\intouch\OrderGetAction;
use Yii;
use yii\rest\Controller;
use common\components\identity\RbacHelper;

class InntouchController extends Controller
{
    /**
     * Returns array of controller actions.
     *
     * @return array
     */
    public function actions()
    {
        return [
            'order.get'             => 'gateway\modules\v1\actions\inntouch\InnTouchOrderGetAction',
            'order.confirmed.list'  => 'gateway\modules\v1\actions\inntouch\InnTouchOrderConfirmedListAction',
            'order.unconfirmed.list'=> 'gateway\modules\v1\actions\inntouch\InnTouchOrderUnconfirmedListAction',
            'order.complete'        => 'gateway\modules\v1\actions\inntouch\InnTouchOrderCompleteAction',
            'order.cancel'          => 'gateway\modules\v1\actions\inntouch\InnTouchOrderCancelAction',
        ];
    }

    public function createAction($id) {
        return parent::createAction(Yii::$app->request->get('requesttype'));
    }
}
