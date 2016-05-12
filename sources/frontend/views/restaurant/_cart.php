<?php
/* @var $this yii\web\View */
use frontend\components\language\T;
$disableDeliveryType = empty($disableDeliveryType) ? false : true;
?>

<div ng-controller="cartController" ng-init="init()" class="sidebar_set" ng-cloak>
    <?= $this->render('_menu_options', ['popupId' => 'cart-modal']) ?>
        <a class="form_devider popup_info" href="#sidebar-modal"><?=T::l('delivery INFO')?> ></a>
        <div id="sidebar-modal" class="white-popup-block mfp-hide sidebar-modal"
         ng-controller="deliveryInfoController"
         ng-class="{add_delivery_adress:!mainVisible}"
         ng-cloak>
        <div class="main-sidebar" ng-show="mainVisible">
            <div ng-if="loggedin === true" class="delivery_location slset ">
                <span class="pseudo_input no-update select-no-handler" ng-click="clickMenu($event)"></span>
                <input type="text" placeholder="<?=T::l('LOCATION')?>">
                <ul>
                    <li ng-repeat="address in userAddresses" ng-click="selectAddress($event,address)">{{address.name}}</li>
                    <li class="new_address" ng-click="onAddNewAddress($event)"><?=T::l('ADD NEW ADDRESS')?></li>
                </ul>
            </div>
            <?php if(!$disableDeliveryType){?>
                <delivery-type
                    ng-init="setAddressTitle()"
                    class="delivery_asap"
                    element-id=""
                    open-class="'delivery_asap_opened'"
                    type-list="<?= htmlspecialchars(json_encode($deliveryTypeFiler['types'])) ?>"
                    date-list="<?= htmlspecialchars(json_encode($deliveryTypeFiler['dates'])) ?>"
                    type="delivery_type"
                    delivery-date="delivery_date"
                    delivery-time="delivery_time"
                    close="closeDelivTypeMethod"
                    on-select="setDelivery(data)"
                    on-open="openTypeHandler()"
                    ></delivery-type>
            <?php }?>
            <input ng-hide="loggedin"
                   type="text" class="post_code"
                   ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>"
                   ng-model="postcode"
                   ng-change="postcodeChanged($event)"
                   placeholder="<?=T::l('POST CODE')?>">
        </div>
        <div class="add_new_adress" ng-class="{visible:!mainVisible}">
            <validation-summary
                form-name="newAddressForm"
                form-id="new_address_form"
                custom-error="saveNewAddressError"
                ></validation-summary>

            <form name="newAddressForm" id="new_address_form">
                <h4><?=T::l('Enter New Address')?></h4>
                <input name="location_name" ng-model="name" type="text" placeholder="<?=T::l('LOCATION NAME*')?>" required err-required="<?= T::l('Address name is missing') ?>" remove-html>
                <?= $this->render('../common/_title-select')?>
                <input name="first_name" ng-model="firstName" type="text" placeholder="<?=T::l('FIRST NAME*')?>" required err-required="<?= T::l('First name is missing') ?>" remove-html>
                <input name="last_name" ng-model="lastName" type="text" placeholder="<?=T::l('LAST NAME*')?>" required err-required="<?= T::l('Last Name is missing') ?>" remove-html>
                <input name="address1" ng-model="address1" type="text" placeholder="<?=T::l('1ST LINE OF ADDRESS*')?>" required maxlength="50" err-required="<?= T::l('Address1 is missing') ?>" remove-html>
                <input name="address2" ng-model="address2" type="text" placeholder="<?=T::l('2ST LINE OF ADDRESS')?>" maxlength="50" remove-html>
                <input name="city" ng-model="city" class="half city" type="text" placeholder="<?=T::l('CITY*')?>" required err-required="<?= T::l('City is missing') ?>" remove-html>
                <input name="addrPostcode" ng-model="postcode" class="half" type="text" placeholder="<?=T::l('POSTCODE*')?>" required err-required="<?= T::l('Postcode is missing') ?>" ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>">
                <input name="phone" ng-model="phone" class="phone" type="text" placeholder="<?=T::l('MOBILE NUMBER*')?>" required err-required="<?= T::l('Phone is missing') ?>" remove-html>
                <input type="email" placeholder="<?= T::l('EMAIL*') ?>" ng-model="email" required="" maxlength="50" name="Email" err-required="<?= T::l('Email is missing') ?>" remove-html>
                <input type="text" placeholder="<?= T::l('INSTRUCTIONS FOR DRIVER') ?>" maxlength="250" ng-model="instructions" remove-html>
                <input type="submit"
                       class="half save"
                       ng-disabled="newAddressForm.$invalid"
                       ng-click="saveNewAddress($event)"
                       value="<?=T::l('Save Location');?>">
                <button class="half cancel" ng-click="cancel($event)"><?=T::l('Cancel')?></button>
            </form>
        </div>
    </div>


    <div class="time_tracker">
        <div class="time_tracker_content">
            <h4><?= T::l('DELIVERY TRACKER')?></h4>
			<div ng-if="eta ==''" class="counter_wrap"><?=T::l('eta')?></div>
            <div ng-if="eta != null && eta != ''" class="counter_wrap"><?=T::l('eta');?> <span>{{eta-<?=Yii::$app->params['etaDiff']?>}}<span class="eta_delimeter">-</span>{{eta+<?=Yii::$app->params['etaDiff']?>}}</span> <?= T::l('min')?></div>
            <div ng-if="eta == null" class="counter_wrap"><?=T::l('eta');?> <span><?=T::l('N/A');?></span></div>
        </div>
    </div>
    <div class="delivery_items">
        <h3 class="cart_plate"><?= T::l('YOUR PLATE') ?></h3>
        <ul>
            <li ng-repeat="item in cart.items | filter:filterDeleted" repeat-end="cartItemsRendered()">
                <a <?= $allowModifying ? 'class="cart_hover" href="#cart-modal" ng-click="selectMenuItem(item)"' : '' ?> >
                    <div>{{item.quantity}}&nbsp;&nbsp;x </div>
                    <div>{{item.name}}</div>
                    <div class="delivery_sum">
                        {{currency_symbol}}{{getItemPrice(item)}}
                        <span ng-show="item.discount > 0">({{item.discount | number:2}})</span>
                    </div>
                </a>
            </li>
            <li>
                <a>
                    <?= T::l('Delivery Charge')?>
                    <div class="delivery_sum">
                        {{currency_symbol}}<span>{{cart.delivery_charge | number:2}}</span>
                        <span ng-show="cart.discount_delivery_charge > 0">({{cart.discount_delivery_charge | number:2}})</span>
                    </div>
                </a>
            </li>
            <li ng-show="cart.discount_total > 0">
                <a>
                    <?= T::l('Discount')?>
                    <div class="delivery_sum">{{currency_symbol}}<span>{{cart.discount_total | number:2}}</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <div class="form_devider"></div>
    <form action="" class="items_form">
        <input type="text" placeholder="<?= T::l('PROMO')?>" class="promo" ng-model="cart.voucher_code" ng-enter="setVoucher()">
        <div class="sidebar_select driver_charge">
            <span class="pseudo_input"><?= T::l('TIP') ?>{{currency_symbol}}{{driver_charge || 1}}</span>
            <input type="hidden" placeholder="<?= T::l('TIP') ?> {{currency_symbol}}{{driver_charge || 1}}">
            <ul ng-model="driver_charge" style="width: 88px;">
                <li ng-repeat="i in [] | range: 5"
                    ng-click="setDriverCharge(i)"
                    ng-class="{default:driver_charge == i}" repeat-end="initTipSelect()">
                    <?= T::l('TIP') ?> {{currency_symbol}}{{i}}
                </li>
            </ul>
        </div>
        <div class="cart invalid_details">
        {{voucher_error}}
        {{cart.validate_error}}
        </div>
        <?php if ($allow_checkout): ?>
            <button type="button" ng-click="toCheckout()" ng-if="is_available_for_time" ng-disabled="cart.items.length === 0 || !cart.is_valid || cart.is_processing" class="sidebar_submit">
                <span ng-show="min_order_value > 0 && min_order_value > cart.total">
                    <?= T::l('Add')?> {{currency_symbol}}<span>{{(min_order_value - cart.total) | number:2}}</span> <?= T::l('More')?>
                </span>
                <span ng-show="min_order_value == 0 || min_order_value <= cart.total">{{currency_symbol}}<span>{{cart.total | number:2}}</span> <?= T::l('Checkout')?></span>
            </button>
        <?php endif; ?>
        <?php if (isset($inject_html)) { echo $inject_html; } ?>
    </form>
</div>

