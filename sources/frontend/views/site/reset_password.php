<?php
use frontend\components\language\T;
use \yii\helpers\Url;

$this->title = T::l('Set Password');

?>

<section class="registration">
    <div class="wrapper">
        <div class="sidebar_set_outer">
            <div class="wrapper">
                <div class="sidebar_set">
                    <div class="loyalty_pays">
                        <span><?= T::l('LOYALTY PAYS') ?></span>
                        <p><?= T::l('From the moment you sign up and place an order, you start to earn points towards discounts and free meals.') ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content_set" ng-app="dineinApp" ng-controller="registrationController">
            <h3 class="form_devider"><?= T::l('RESET PASSWORD') ?></h3>
            <form action="" class="items_form" name="set_password_form" id="set_password_form">
                <div ng-show="!is_password_reset">
                    <validation-summary form-name="set_password_form" form-id="set_password_form" custom-error="setPasswordError"></validation-summary>
                    <div class="personal_details">
                        <input type="password" placeholder="<?= T::l('ENTER NEW PASSWORD') ?>*" ng-model="password" required="" err-required="<?= T::e('Password is missing') ?>" maxlength="255" name="password" remove-html>
                        <input type="password" placeholder="<?= T::l('CONFIRM PASSWORD') ?>*" ng-model="repassword" compare-to="password" err-compareTo="<?= T::e('Passsword and confirmation password does not match') ?>"required="" err-required="<?= T::e('Password is missing') ?>" maxlength="255" name="repassword" remove-html>
                    </div>
                    <div class="bottom_buttons_set bottom_block">
                        <input ng-disabled="!set_password_form.$valid" type="button" ng-click="resetPassword()" value="<?= T::l('SUBMIT') ?>">
                    </div>
                </div>
                <div class="thank_you" ng-show="is_password_reset">
                    <span><?= T::l('THANK YOU') ?></span>
                    <p><?= T::l('You can login with your new password.') ?></p>
                </div>
            </form>
        </div>
    </div>
</section>