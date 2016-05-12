<?php

namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\components\PostApiAction;
use gateway\modules\v1\forms\common\UpdateOrderItemDisplayForm;
use Exception;

class UpdateOrderItemDisplayAction extends PostApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return UpdateOrderStatusForm
     */
    protected function createRequestForm() {
        return new UpdateOrderItemDisplayForm();
    }

    /**
     * Returns candidate's gender.
     *
     * @param UpdateOrderStatusForm $requestForm Request form class instance.
     * @throws
     * @return mixed
     */
    protected function getResponseData($requestForm) {
        try {
            
            $order_items = \common\models\OrderItem::find()->where(['id' => explode (',', $requestForm->order_item_id)])->all();
            
            $is_saved = true;
            
            foreach ($order_items as $item) {
                $item->display_index = $requestForm->display_index;
                $is_saved = $is_saved && $item->save();
            }
            
        } catch (Exception $ex) {
            $is_saved = false;
        }
        
        return 
        [
            'result' => $is_saved
        ];
    }

}
