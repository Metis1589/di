<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 4/5/2015
 * Time: 2:05 PM
 */
use frontend\components\language\T;

?>

<div ng-show="tab == 'addresses'" >
    <div class="my_location_wrapper only_mobile" ng-if="isMobile" ng-show="address.id == null">
        <h3 class="only_mobile form_devider"><?=T::l('MY LOCATIONS')?></h3>
        <div class="my_location_set form_devider" ng-repeat="addr in addresses">
            <span class="my_location_title">{{addr.name}}</span>
            <a href="#" ng-click="address_selected(addr.id)" class="my_location_edit"><?=T::l('EDIT')?></a>
            <span class="my_location_string">{{addr.address1}}</span>
            <span class="my_location_string">{{addr.address2}}</span>
            <span class="my_location_string">{{addr.city}}</span>
            <span class="my_location_string">{{addr.postcode}}</span>
            <span class="my_location_string">{{addr.phone}}</span>
            <span class="my_location_string">{{addr.email}}</span>
        </div>
        <a href="#" class="add_new_location only_mobile" ng-click="address_selected(0)"><?=T::l('ADD NEW LOCATION &gt;')?></a>
    </div>
    <div class="thank_you" ng-show="is_saved && !isMobile">
        <span><?= T::l('Address saved!') ?></span>
<!--        <p>--><?//= T::l('You can login with your credentials.') ?><!--</p>-->
    </div>
    <form id="address-form" ng-show="address.id != null || addNew == true" name="frmAddress" ng-submit="saveAddress()">
        <h3 class="form_devider only_mobile"><?=T::l('NEW LOCATION');?></h3>
        <validation-summary ng-show="address.id != null" form-name="frmAddress" form-id="address-form" custom-error="addressSaveError"></validation-summary>
        <input type="hidden" name="id" ng-model="address.id">
        <input type="text" placeholder="<?= T::l('ADDRESS NAME*') ?>" ng-model="address.name" required="" maxlength="255" name="AddressName" err-required="<?= T::l('Address name is missing') ?>" remove-html>
        <?= $this->render('../common/_title-select',['ngModel'=>'address.title','name'=>'Title'])?>
        <input type="text" placeholder="<?= T::l('FIRST NAME*') ?>" ng-model="address.first_name" required="" maxlength="255" name="FirstName" err-required="<?= T::l('First name is missing') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('LAST NAME*') ?>" ng-model="address.last_name" required="" maxlength="255" name="LastName" err-required="<?= T::l('Last Name is missing') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('1ST LINE OF ADDRESS*') ?>" ng-model="address.address1" required="" maxlength="50" name="Address1" err-required="<?= T::l('Address1 is missing') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('2ND LINE OF ADDRESS') ?>" ng-model="address.address2" maxlength="50" name="Address2" remove-html>
        <input type="text" class="london" placeholder="<?= T::l('CITY*') ?>" ng-model="address.city" required="" maxlength="255" name="City" err-required="<?= T::l('City is missing') ?>" remove-html>
        <input type="text" class="n13ly" placeholder="<?= T::l('POSTCODE*') ?>" ng-model="address.postcode" required="" maxlength="45" name="Postcode" err-required="<?= T::l('Postcode is missing') ?>" ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>" remove-html>
        <input type="text" placeholder="<?= T::l('PHONE*') ?>" ng-model="address.phone" required="" maxlength="50" name="Phone" err-required="<?= T::l('Phone is missing') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('EMAIL*') ?>" ng-model="address.email" required="" maxlength="50" name="Email" err-required="<?= T::l('Email is missing') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('INSTRUCTIONS FOR DRIVER') ?>" maxlength="250" ng-model="address.instructions" remove-html>
        <input type="submit" value="<?= T::l('SAVE LOCATION') ?>" id="submit" ng-disabled="frmAddress.$invalid"/>
    </form>

</div>

