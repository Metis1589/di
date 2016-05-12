<?php
use frontend\components\language\T;
use yii\helpers\Url;
?>
<div ng-controller="loginController" id="test-modal" class="white-popup-block mfp-hide login_popup">
    <div class="form_login" ng-show="loginActive">
        <a class="popup-modal-dismiss only_desctop" ng-click="closePopup($event)" href="#">X</a>
        <h6>LOGIN</h6>
        <form name="form" ng-class="{form_error: error != null}" ng-submit="loginAction()">
            <span><?=T::e('Invalid Login Details!')?></span>
            <input type="text" placeholder="<?=T::l('USERNAME OR EMAIL')?>" name="username" ng-model="username" required>
            <input type="password" placeholder="<?=T::l('PASSWORD')?>" name="password" ng-model="password" required>
            <input type="submit" value=">" class="only_desctop">
            <input type="checkbox" id="rem_me" ng-model="is_remember" ng-init="is_remember = false;">
            <label for="rem_me"><?=T::l('Remember Me');?></label>
            <a href="#" class="forgot only_desctop" ng-click="showPasswordResetFormAction()"><?=T::l('Forgot Password? >')?></a>
            <a href="#" class="forgot only_mobile" ng-click="showPasswordResetFormAction()"><?=T::l('forgot your password?')?></a>
            <div class="links only_mobile">
                <span><?=T::l('NOT A MEMBER?')?></span>
                <a href="<?=Url::toRoute('site/register');?>"><?=T::l('REGISTER NOW')?></a>
<!--                <span>--><?//=T::l('OR')?><!--</span>-->
<!--                <a href="#">--><?//=T::l('continue')?><!--</a>-->
<!--                <span>--><?//=T::l('&amp; sign up at checkout')?><!--</span>-->
            </div>
            <div class="bottom_buttons_set bottom_block login_btn_block">
                <button ng-click="loginAction()" type="button" class="only_mobile"><?=T::l('LOGIN')?></button>
            </div>
        </form>

    </div>
    <div class="form_forgot" ng-show="!loginActive">
        <a class="popup-modal-dismiss" href="#" ng-click="closePopup($event)">X</a>
        <h6><?=T::l('FORGOT PASSWORD')?></h6>
        <form name="frmPasswordReset" ng-submit="requestPasswordResetAction()" ng-class="{form_error:reset_error != null}">
            <p><?=T::l('Please enter your email address and we will send reset password link via email.')?></p>
            <span><?=T::e('Invalid Email Address!')?></span>
            <input type="text" placeholder="<?=T::l('EMAIL ADDRESS')?>" ng-model="reset_username" required>
            <input type="submit" value=">">
        </form>
    </div>

</div>