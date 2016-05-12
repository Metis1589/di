<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 4/5/2015
 * Time: 2:05 PM
 */
use frontend\components\language\T;
?>
<div ng-show="tab == 'membership'">
    <validation-summary form-name="frmProfile" form-id="profile-form" custom-error="saveProfileError"></validation-summary>
    <form id="profile-form" class="last_form" name="frmProfile" ng-submit="saveProfile()">
<!--        <p>Are you a corporate user?</p>-->
<!--        <dinein-select-->
<!--            class="registration_select user_question slset"-->
<!--            ng-model="isCorporate"-->
<!--            placeholder="'--><?//= T::l('NO') ?><!--'"-->
<!--            items="{-->
<!--            '--><?//=T::l('YES');?><!--':'--><?//=T::l('YES');?><!--',-->
<!--            '--><?//=T::l('NO');?><!--':'--><?//=T::l('NO');?><!--'-->
<!--        }"-->
<!--            name="title1"-->
<!--            ></dinein-select>-->
        <h3 class="only_mobile form_devider">MEMBERSHIP</h3>
        <input type="text" placeholder="<?= T::l('FIRST NAME*') ?>" ng-model="profile.first_name" name="FirstName" errormsg="Test" required="" err-required="<?= T::l('First name is missing') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('LAST NAME*') ?>" ng-model="profile.last_name" name="LastName" required="" err-required="<?= T::l('Last name is missing') ?>" remove-html>
        <input type="password" placeholder="<?= T::l('PASSWORD*') ?>" ng-model="profile.password" name="Password" remove-html>
        <input type="password" placeholder="<?= T::l('CONFIRM PASSWORD*') ?>" ng-model="profile.repassword" compare-to="profile.password" name="RePassword" err-compareTo="<?= T::l('Passsword is invalid') ?>" remove-html>
        <input type="text" placeholder="<?= T::l('EMAIL*') ?>" ng-model="profile.email" required="" name="Email" remove-html>
        <input type="text" placeholder="<?= T::l('CONFIRM EMAIL*') ?>" ng-model="profile.email" required="" name="Email" ng-change="" remove-html>
        <div class="bottom_buttons_set bottom_block">
            <input type="submit" id="submit" ng-disabled="frmProfile.$invalid" value="<?=T::l('APPLY CHANGES');?>" />
            <br>
        </div>
    </form>
</div>