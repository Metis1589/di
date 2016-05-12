<?php
/* @var $this yii\web\View */

use frontend\components\language\T;
$this->title = $model['name'];
?>
<script type="text/ng-template" id="title-select">
<!--    --><?//= $this->render('../common/_title-select')?>
</script>
<input type="hidden" id="restaurant_id" value="<?= $model['id'] ?>">
<input type="hidden" id="restaurant_name" value="<?= T::cut($model['name'], 40) ?>">
<input type="hidden" id="lat" value="<?= $model['physicalAddress']['latitude'] ?>">
<input type="hidden" id="long" value="<?= $model['physicalAddress']['longitude'] ?>">

<?= $this->render('_deliveryinfo', [ 'deliveryTypeFiler' => $deliveryTypeFiler ]) ?>
<div ng-app="dineinApp" ng-controller="restaurantController" ng-cloak>
    <nav class="pushmenu pushmenu-right" ng-show="view_plate === true" ng-controller="cartController" ng-init="init()">
        <div class="pushmenu-push-inner">
            <div class="sidebar_set">
            <a class="form_devider popup_info" href="#delivery_info_mobile"><?= T::l('DELIVERY INFO') ?> ></a>
            <div class="time_tracker">
                <div class="time_tracker_content">
                    <h4><?= T::l('DELIVERY TRACKER') ?><span class="only_mobile">TM</span></h4>
                    <div ng-if="eta != null" class="counter_wrap"><?=T::l('ETA');?> <span>{{eta-<?=Yii::$app->params['etaDiff']?>}}-{{eta+<?=Yii::$app->params['etaDiff']?>}}</span> <?= T::l('min')?></div>
                    <div ng-if="eta == null" class="counter_wrap"><?=T::l('ETA');?> <span><?=T::l('N/A');?></span></div>
                </div>
            </div>
            <h3 class="form_devider"><?= T::l('YOUR PLATE') ?></h3>
            <div class="delivery_items">
                <ul>
                    <li ng-repeat="item in cart.items | filter:filterDeleted" repeat-end="cartItemsRendered()">
                        <a href="#">
                            <i class="button_minus only_mobile" ng-click="subtract(item)"></i>
                            <span class="number">{{item.quantity}}</span>
                            <i class="button_plus only_mobile" ng-click="add(item)"></i>
                            <span class="cross only_desctop" ng-click="drop(item)">x</span>
                            <span class="menu_item">{{item.name}}</span>
                            <div class="delivery_sum">{{currency_symbol}}<span>{{getItemPrice(item)}}</span><i class="button_close only_mobile" ng-click="drop(item)"></i></div>
                        </a>
                    </li>
                    <li class="charge">
                        <a href="#">
                            <span class="charge_title"><?= T::l('Delivery Charge')?></span>
                            <div class="delivery_sum">
                                {{currency_symbol}}<span class="charge_sum">{{cart.delivery_charge | number:2}}</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="form_devider only_desctop"></div>
            <form action="" class="items_form bottom_buttons_set bottom_block">
                <div class="bottom_buttons_set_wrapper">
                    <input type="text" placeholder="PROMO" class="promo" ng-model="cart.voucher_code" ng-change="setVoucher()" style="padding: 7px 0 9px 0;">
                    <div class="sidebar_select driver_charge bottom">
                        <span class="pseudo_input"></span>
                        <input type="hidden" placeholder="<?= T::l('TIP') ?> {{currency_symbol}}{{cart.driver_charge || 1}}">
                        <ul ng-model="cart.driver_charge" style="width: 90px;">
                            <li ng-repeat="i in [] | range: 5" ng-click="setDriverCharge(i)" ng-class="i === 1 ? 'default' : ''">
                                <?= T::l('TIP') ?> {{currency_symbol}}{{i}}
                            </li>
                        </ul>
                    </div>
                    <button type="button" ng-click="toCheckout()" ng-disabled="cart.items.length === 0" class="sidebar_submit">{{currency_symbol}}<span>{{cart.total | number:2}}</span> <?= T::l('Checkout')?></button>
                </div>
            </form>
            </div>
        </div>
    </nav>

    <section class="restaurant_nav under">
        <div class="wrapper">
            <ul class="restaurant_nav_menu only_mobile" ng-show="tab !== 'menu'">
                <li class="menus link_menu" ng-class="{'active_item': tab === 'submenus'}">
                    <a href="#" ng-click="selected('submenus')" class="mask_modal"><?= T::l('MENUS') ?></a>
                </li>
                <li class="photos" ng-class="{'active_item': tab === 'photo'}">
                    <a href="#" ng-click="selected('photo')"><?= T::l('PHOTOS') ?></a>
                </li>
                <li class="reviews" ng-class="{'active_item': tab === 'reviews'}">
                    <a href="#" ng-click="selected('reviews')"><?= T::l('REVIEWS') ?></a>
                </li>
                <li class="about" ng-class="{'active_item': tab === 'about'}">
                    <a href="#" ng-click="selected('about')"><?= T::l('ABOUT') ?></a>
                </li>
                <li class="location" ng-class="{'active_item': tab === 'location'}">
                    <a href="#" ng-click="initializeMap()"><?= T::l('LOCATION') ?></a>
                </li>
            </ul>
            <ul class="restaurant_nav_menu only_desctop">
                <li class="menus link_menu" ng-class="{'active_item': tab === 'menu'}">
                    <a href="#" ng-click="selected('menu')" class=""><?= T::l('MENUS') ?></a>
                    <ul class="link_submenu">
                        <li><a href="#" ng-click="selected('menu')"><?= T::l('MENUS') ?></a></li>
                        <li class="link_submenu_inner">
                            <ul ng-repeat="menu in menus.menus">
                                <li ng-repeat="category in menu.menuCategories">
                                    <a href="#cat-{{category.id}}" ng-class="category_id == category.id ? 'active' : ''" target="_self" ng-click="selected('menu', category.id)">{{category.name_key}}</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="photos" ng-class="{'active_item': tab === 'photo'}">
                    <a href="#" ng-click="selected('photo')"><?= T::l('PHOTOS') ?></a>
                </li>
                <li class="reviews" ng-class="{'active_item': tab === 'reviews'}">
                    <a href="#" ng-click="selected('reviews')"><?= T::l('REVIEWS') ?></a>
                </li>
                <li class="about" ng-class="{'active_item': tab === 'about'}">
                    <a href="#" ng-click="selected('about')"><?= T::l('ABOUT') ?></a>
                </li>
                <li class="location" ng-class="{'active_item': tab === 'location'}">
                    <a href="#" ng-click="initializeMap()"><?= T::l('LOCATION') ?></a>
                </li>
            </ul>
            <div class="only_mobile" ng-show="tab === 'menu' && view_plate === false">
                <h3><?= T::l('MENU COURSE') ?></h3>
                <span class="only_mobile"><?= T::l('Menu course description goes here') ?></span>
                <button type="button" class="orange_stripes_menu only_mobile" ng-click="selected('submenus')"></button>
            </div>
        </div>
    </section>
    <section ng-class="{'menus items_form first_section' : (tab === 'menu' || tab === 'submenus'), 'photos items_form first_section' : tab === 'photo', 'location items_form first_section' : tab === 'location',
            'about items_form first_section' : tab === 'about', 'reviews items_form first_section' : tab === 'reviews'}" class="restaurant_page">
        <div class="wrapper">
            <div class="sidebar_set_outer">
                <div class="wrapper">
                   <?= $this->render('_cart', [
                       'allow_checkout'    => true,
                       'deliveryTypeFiler' => $deliveryTypeFiler,
                       'allowModifying' => true
                    ]) ?>
                </div>
            </div>
            <div class="content_set only_mobile menusubmenu" ng-show="tab === 'submenus'">
                <div ng-repeat="menu in menus.menus">
                    <div ng-repeat="category in menu.menuCategories" class="submenus" style="position: relative;">
                        <a href="#cat-{{category.id}}" target="_self" class="only_mobile" style="background:transparent;position:absolute;width:100%;bottom:-2px;top:-10px;left:0;display:block;margin-left:0;" ng-click="selected('menu');"></a>
                        <a>{{category.name_key}}</a>
                        <span>{{category.description_key}}</span>
                    </div>
                </div>
            </div>
            <div class="content_set" ng-show="tab === 'menu'" style="padding-top: 0px;">
                <?= $this->render('_menu', ['model' => $model, 'allergies' => $allergies]) ?>
            </div>
            <div class="content_set" ng-show="tab === 'photo'" >
                <?= $this->render('_photos', ['model' => $model]) ?>
            </div>
            <div class="content_set" ng-show="tab === 'reviews'" >
                <?= $this->render('_reviews', ['model' => $model]) ?>
            </div>
            <div class="content_set" ng-show="tab === 'about'" >
                <?= $this->render('_about', ['model' => $model]) ?>
            </div>
            <div class="content_set" ng-show="tab === 'location'" >
                <?= $this->render('_location', ['model' => $model]) ?>
            </div>
        </div>
    </section>
    <div ng-if="!postcode">
        <a href="#restaurant_index_modal" class="restaurant_index"></a>
        <div id="mask_modal" class="white-popup-block mfp-hide"></div>
        <div id="load_modal" class="white-popup-block mfp-hide"></div>
        <div id="restaurant_index_modal" class="white-popup-block mfp-hide main change_delivery">
            <div class="wrapper">
                <h1><?= T::l('Please enter below to order.') ?></h1>
                <?= $this->render('_search', ['delivery_types' => $deliveryTypeFiler['types'], 'delivery_dates' => $deliveryTypeFiler['dates'], 'page' => 'restaurant']) ?>
            </div>
        </div>
    </div>
</div>
