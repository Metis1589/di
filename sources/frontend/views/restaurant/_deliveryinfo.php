<?php
use frontend\components\language\T;
$disableDeliveryType = empty($disableDeliveryType) ? false : true;
?>
<div id="delivery_info_mobile"
     class="white-popup-block mfp-hide change_delivery"
     ng-controller="deliveryInfoController"
     ng-cloak>
    <h3 ng-hide="loggedin === true && addNewAddressOpened"><?= T::l('DELIVERY INFO') ?></h3>
    <div class="main_page_menu items_form" ng-hide="loggedin === true && addNewAddressOpened">
        <div ng-if="loggedin === true" class="delivery_location slset ">
            <span class="pseudo_input no-update select-no-handler" ng-click="clickMenu($event)"></span>
            <input type="text" placeholder="<?=T::l('LOCATION')?>">
            <ul>
                <li ng-repeat="address in userAddresses" ng-click="selectAddressWOUpdate($event,address)" ng-bind="address.name"></li>
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
                on-open="openTypeHandler()"
                ></delivery-type>
        <?php }?>
        <input ng-hide="loggedin"
               type="text"
               class="post_code"
               ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>"
               ng-model="postcode"
               placeholder="<?=T::l('POST CODE')?>" remove-html>

    </div>
    <div class="button_set bottom_buttons_set bottom_block" ng-hide="loggedin === true && addNewAddressOpened">
        <div class="bottom_buttons_set_wrapper">
            <a href="#" class="popup-modal-dismiss">
                <button type="button" ng-click="cancelPopup()"><?= T::l('CANCEL') ?></button>
            </a>
            <a href="#">
                <button type="button" ng-click="saveNewData()" class="apply"><?= T::l('APPLY') ?></button>
            </a>
        </div>
    </div>
    <div ng-show="loggedin === true && addNewAddressOpened" class="new_address_form">
        <form id="newAddressForm" name="newAddressForm" ng-submit="saveNewAddress()">
            <h3 class="form_devider only_mobile"><?=T::l('NEW LOCATION');?></h3>
            <validation-summary ng-show="address.id != null" form-name="newAddressForm" form-id="newAddressForm" custom-error="saveNewAddressError"></validation-summary>
            <input type="hidden" name="id" ng-model="address.id">
            <input type="text" placeholder="<?= T::l('ADDRESS NAME*') ?>" ng-model="name" required="" maxlength="255" name="AddressName" err-required="<?= T::l('Address name is missing') ?>" remove-html>
            <?= $this->render('../common/_title-select',['ngModel'=>'title','name'=>'Title'])?>
            <input type="text" placeholder="<?= T::l('FIRST NAME*') ?>" ng-model="first_name" required="" maxlength="255" name="FirstName" err-required="<?= T::l('First name is missing') ?>" remove-html>
            <input type="text" placeholder="<?= T::l('LAST NAME*') ?>" ng-model="last_name" required="" maxlength="255" name="LastName" err-required="<?= T::l('Last Name is missing') ?>" remove-html>
            <input type="text" placeholder="<?= T::l('1ST LINE OF ADDRESS*') ?>" ng-model="address1" required="" maxlength="50" name="Address1" err-required="<?= T::l('Address1 is missing') ?>" remove-html>
            <input type="text" placeholder="<?= T::l('2ND LINE OF ADDRESS') ?>" ng-model="address2" maxlength="50" name="Address2" remove-html>
            <input type="text" class="london" placeholder="<?= T::l('CITY*') ?>" ng-model="city" required="" maxlength="255" name="City" err-required="<?= T::l('City is missing') ?>" remove-html>
            <input type="text" class="n13ly" placeholder="<?= T::l('POSTCODE*') ?>" ng-model="postcode" required="" maxlength="45" name="Postcode" err-required="<?= T::l('Postcode is missing') ?>" ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>" remove-html>
            <input type="text" placeholder="<?= T::l('PHONE*') ?>" ng-model="phone" required="" maxlength="50" name="Phone" err-required="<?= T::l('Phone is missing') ?>" remove-html>
            <input type="text" placeholder="<?= T::l('EMAIL*') ?>" ng-model="email" required="" maxlength="50" name="Email" err-required="<?= T::l('Email is missing') ?>" remove-html>
            <input type="text" placeholder="<?= T::l('INSTRUCTIONS FOR DRIVER') ?>" maxlength="250" ng-model="instructions" remove-html>
            <button class="close_new_address" ng-click="closeNewAddress()"><?=T::l('CANCEL')?></button>
            <input type="submit" value="<?= T::l('SAVE LOCATION') ?>" id="submit" ng-disabled="newAddressForm.$invalid"/>
        </form>
    </div>
</div>