<?php
use frontend\components\language\T;
use \yii\helpers\Url;
$this->title = T::l('Delivery Tracker');
?>
<script>
    var clearOrder = <?= $clearOrder ? 'true' : 'false' ?>;
</script>
<section class="first_section delivery_tracker">
    <div class="wrapper ng-cloak" ng-app="dineinApp" ng-controller="orderTrackerController">
        <div class="content_set" ng-init="initOrderNumber(<?=$order_number?>)">
            <div class="top_line form_devider">
                <div class="delivery_tracker_logo"></div>
                <form class="enter_id" name="number_form">
                    <input ng-model="order_number"
                           type="text"
                           placeholder="<?=T::l('ENTER ORDER ID #')?>"

                           ng-keypress="keypressHandler($event)" remove-html>
                    <input type="submit" value="<?=T::l('TRACK');?>" ng-click="startTrack($event)" ng-submit="startTrack($event)">
                </form>
            </div>
            <div class="estimated">
                <h3 ng-show="estimated_time"><?=T::l('estimated delivery time')?></h3>
                <span ng-show="estimated_time" class="eta"><?=T::l('ETA:')?> {{estimated_time}}</span>
                <p ng-show="restaurant_delivery && restaurant_phone"><?=T::l('If you have any questions about your order please phone the restaurant:')?></p>
                <p ng-show="!restaurant_delivery && restaurant_delivery!== null">
                    <?=T::l('If you have any questions about your order please');?>
                    <a href="<?=Url::toRoute(['page/page','url'=>'contact-us'])?>"><?=T::l('contact us');?></a>.
                </p>
                <div ng-show="restaurant_delivery" class="estimated_address">
                    <span>{{restaurant_name}}</span>
                </div>
                <div ng-show="restaurant_delivery" class="estimated_tel">
                    <span>{{restaurant_phone}}</span>
                </div>
            </div>
            <div class="enjoy_your_meal">
                <span class="processing"><?=T::l('PROCESSING PAYMENT')?></span>
                <span class="received"><?=T::l('Payment Received. Thank You!')?></span>
                <span class="transferring"><?=T::l('Transferring to restaurant')?></span>
                <span class="confirmed"><?=T::l('Order Confirmed by Restaurant')?></span>
                <span class="prepared"><?=T::l('Your Food is Being Prepared')?></span>
                <span class="estimated_delivery_time"><?=T::l('Estimated Delivery Time')?></span>
                <span class="food_en_route"><?=T::l('FOOD EN ROUTE')?></span>
                <span class="your_meal"><?=T::l('ENJOY YOUR MEAL')?></span>
                <div class="chart" data-percent="0"></div>
            </div>
        </div>
    </div>
</section>
