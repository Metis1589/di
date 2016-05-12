<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 4/5/2015
 * Time: 2:05 PM
 */
use frontend\components\language\T;

$this->title = T::l('Your Account');
?>
<div ng-app="dineinApp" ng-controller="userProfileController" ng-cloak>
    <section class="restaurant_nav only_desctop">
        <div class="wrapper">
            <ul class="restaurant_nav_menu">
                <li class="menus link_menu" ng-class="{'active_item': tab == 'addresses'}">
                    <a href="#mask_modal" class="mask_modal"><?= T::l('ADDRESSES') ?></a>
                    <ul class="link_submenu">
                        <li><a href="#"><?= T::l('ADDRESSES') ?></a></li>
                        <li ng-repeat="addr in addresses"><a href="#" ng-class="{active:addr.id == address.id}" ng-click="address_selected(addr.id)">{{addr.name}}</a></li>
                        <li><a href="#" ng-class="{active:address.id == null}" ng-click="address_selected(0)"><?= T::l('ADD NEW') ?></a></li>
                    </ul>
                </li>
                <li class="photos" ng-class="{'active_item': tab == 'membership'}"><a href="#" ng-click="menu_selected('membership')"><?= T::l('MEMBERSHIP') ?></a>
                </li>
                <li class="reviews" ng-class="{'active_item': tab == 'reviews'}"><a href="#" ng-click="menu_selected('reviews')"><?= T::l('REVIEWS') ?></a>
                </li>
                <li class="about" ng-class="{'active_item': tab == 'loyalityPoints'}"><a href="#" ng-click="menu_selected('loyalityPoints')"><?= T::l('LOYALTY POINTS') ?></a>
                </li>
                <li class="location" ng-class="{'active_item': tab == 'pastOrders'}"><a href="#" ng-click="menu_selected('pastOrders')"><?= T::l('PAST ORDERS') ?></a>
                </li>
            </ul>
        </div>
    </section>
    <section class="user_section items_form first_section" ng-class="{'reviews' : tab == 'reviews', 'past_orders' : tab == 'pastOrders',loyalty: tab == 'loyalityPoints' }" >
        <div class="wrapper">
            <?=$this->render('_new-order-form.php',['types'=>htmlspecialchars(json_encode($deliveryTypeFiler['types'])),'dates'=>htmlspecialchars(json_encode($deliveryTypeFiler['dates']))])?>
            <div class="content_set" ng-class="{'review_ptop' :  tab == 'reviews' || tab == 'pastOrders'}">
                <?= $this->render('_addresses') ?>
                <?= $this->render('_orders') ?>
                <?= $this->render('_profile') ?>
                <?= $this->render('_reviews') ?>
                <?= $this->render('_loyality-points') ?>
            </div>
        </div>
    </section>

</div>