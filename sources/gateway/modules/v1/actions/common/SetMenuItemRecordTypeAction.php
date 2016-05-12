<?php

namespace gateway\modules\v1\actions\common;

use common\models\MenuItem;
use gateway\modules\v1\forms\common\SetMenuItemRecordTypeForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;
use yii\base\ErrorException;

class SetMenuItemRecordTypeAction extends PostApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return SetMenuItemRecordTypeForm
     */
    protected function createRequestForm() {
        return new SetMenuItemRecordTypeForm();
    }

    /**
     * Returns candidate's gender.
     *
     * @param SetMenuItemRecordTypeForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm) {
        try {

            $restaurant_id = Yii::$app->user->identity->restaurant_id;
            $result = Yii::$app->globalCache->updateMenuItemRecordType($restaurant_id, $requestForm->menu_item_id, $requestForm->record_type);

            if (!$result) {
                throw new ErrorException('Menu item not found');
            }

            /** @var MenuItem $menu_item */
            $menu_item = MenuItem::find()->where(['id' => $requestForm->menu_item_id])->one();

            if (!$menu_item) {
                throw new ErrorException('Menu item not found');
            }

            $menu_item->record_type = $requestForm->record_type;

            if (!$menu_item->save()) {
                throw new ErrorException('Error saving Menu Item');
            }

            return true;
        } catch (Exception $ex) {
            return $ex;
        }
    }

}
