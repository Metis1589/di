<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date 5/31/15
 * @time 5:24 PM
 */
use frontend\components\language\T;
use yii\helpers\Url;
?>
<div class="sidebar_set_outer" ng-init="searchUrl='<?=Url::toRoute('restaurant/search')?>'">
    <div class="wrapper">
        <div class="sidebar_set">
            <p class="form_devider"><?=T::l('CREATE NEW ORDER')?></p>
            <form name="newOrderForm" class="items_form" ng-submit="submitNewOrder($event)">
                <delivery-type
                    class="sidebar_select slset"
                    element-id=""
                    open-class="'opened'"
                    type-list="<?= $types ?>"
                    date-list="<?= $dates ?>"
                    type="delivery_type"
                    delivery-date="delivery_date"
                    delivery-time="delivery_time"
                    close="closeDelivTypeMethod"
                    on-select="setDelivery(data)"
                    on-open="openTypeHandler()"
                    ></delivery-type>
                <input
                       type="text"
                       class="promo"
                       ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>"
                       ng-model="postcode"
                       placeholder="<?=T::l('POST CODE')?>" remove-html>
                <button type="submit" class="sidebar_submit apply" ><?=T::l('Find Your Meal')?></button>
            </form>
        </div>
    </div>
</div>