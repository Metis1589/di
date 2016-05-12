<?php
use frontend\components\language\T;
?>

<div>
    <validation-summary class="standalone_error" form-name="tableform_<?= $prefix ?>" form-id="tableform_<?= $prefix ?>" custom-error="orderCheckoutError"></validation-summary>
    <div class="registration_select checkout_info_hide slset js-delivery-menu address-menu"
         ng-show="is_logged_in && saved_addresses.length > 0"
         ng-model="<?= $prefix ?>.selected"
         ng-change="<?= $prefix ?>_selected()">
        <i class="select_set_carret" ng-click="openAddressMenu($event)"></i>
        <span class="pseudo_input select-no-handler no-update " ng-click="openAddressMenu($event)">{{<?= $prefix ?>.selected.name ? <?= $prefix ?>.selected.name : '<?= T::l('SELECT ADDRESS') ?>*'}}</span>
        <input type="text" placeholder="<?= T::l('SELECT ADDRESS') ?>*">
        <ul style="display: none">
            <li ng-repeat="address in saved_addresses" ng-value="{{address}}" ng-click="<?= $prefix ?>.selected = address">{{address.name}}</li>
        </ul>
    </div>

    <?=$this->render('../common/_title-select',['class'=>$prefix.'_title','ngModel'=>$prefix.'.title','name'=>'Title','disabled'=>$prefix.'.selected.id != null'])?>
    <input class="force_margin" type="text" placeholder="<?= T::l('FIRST NAME') ?>*"                maxlength="255" ng-model="<?= $prefix ?>.first_name" ng-disabled="<?= $prefix ?>.selected.id != null" required="" name="First name"  err-required="<?= T::e('First name is missing') ?>" remove-html>
    <input class="force_margin" type="text" placeholder="<?= T::l('LAST NAME') ?>*"                 maxlength="255" ng-model="<?= $prefix ?>.last_name"  ng-disabled="<?= $prefix ?>.selected.id != null" required="" name="Last name"   err-required="<?= T::e('Last name is missing') ?>" remove-html>
    <input class="force_margin" type="text" placeholder="<?= T::l('1ST LINE OF ADDRESS') ?>*"       maxlength="50"  ng-model="<?= $prefix ?>.address1"   ng-disabled="<?= $prefix ?>.selected.id != null" required="" name="1st line of address" err-required="<?= T::e('First line of address is missing') ?>" remove-html>
    <input class="force_margin" type="text" placeholder="<?= T::l('2ND LINE OF ADDRESS') ?>"        maxlength="50"  ng-model="<?= $prefix ?>.address2"   ng-disabled="<?= $prefix ?>.selected.id != null" remove-html>
    <div class="force_margin citycode_wrapper">
        <input type="text" placeholder="<?= T::l('CITY') ?>*"     class="city"     maxlength="255" ng-model="<?= $prefix ?>.city"       ng-disabled="<?= $prefix ?>.selected.id != null" required="" name="City"        err-required="<?= T::e('City is missing') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('POSTCODE') ?>*" class="postcode" maxlength="45" ng-model="<?= $prefix ?>.postcode"   ng-disabled="<?= $prefix ?>.selected.id != null" required="" name="Postal code" err-required="<?= T::e('Postal code is missing') ?>" ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>" err-pattern="<?= T::e("Incorrect postal ccode"); ?>" remove-html>
    </div>
    <input class="force_margin" type="text" placeholder="<?= T::l('MOBILE NUMBER') ?>*"             maxlength="50" ng-model="<?= $prefix ?>.phone"        ng-disabled="<?= $prefix ?>.selected.id != null" required="" name="Mobile number" err-required="<?= T::e('Mobile number is missing') ?>" remove-html>
    <input class="force_margin" type="text" placeholder="<?= T::l('EMAIL') ?>*"                     maxlength="250" ng-model="<?= $prefix ?>.email"        ng-disabled="<?= $prefix ?>.selected.id != null" required="" name="Email" ng-pattern="/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i" err-required="<?= T::e('Email is missing') ?>" err-pattern="<?= T::e('Please enter correct email') ?>" remove-html>
    <?php if ($prefix != 'billing_address'): ?>
        <input class="force_margin" type="text" placeholder="<?= T::l('INSTRUCTIONS FOR DRIVER') ?>"    maxlength="250" ng-model="<?= $prefix ?>.instructions" ng-disabled="<?= $prefix ?>.selected.id != null" remove-html>
    <?php endif; ?>
</div>