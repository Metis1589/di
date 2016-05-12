<?php
use frontend\components\language\T;
use \yii\helpers\Url;

$this->title = T::l('Sign Up');

?>
<script type="text/ng-template" id="title-select">
    <?= $this->render('../common/_title-select')?>
</script>
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
            <h3 class="form_devider h18"><?= T::l('REGISTRATION') ?></h3>
            <form action="" class="items_form" name="registration_form" id="registration_form">
                <div ng-show="!is_registered">
                    <validation-summary class="standalone_error" form-name="registration_form" form-id="registration_form" custom-error="registrationError"></validation-summary>
                    <div class="personal_details form_devider">
                        <h6><?= T::l('Personal Details') ?></h6>
                        <?= $this->render('../common/_title-select')?>
                        <input type="text" placeholder="<?= T::l('FIRST NAME') ?>*"                ng-model="first_name"       required="" err-required="<?= T::e('First name is missing') ?>" maxlength="255" name="first_name" remove-html>
                        <input type="text" placeholder="<?= T::l('LAST NAME') ?>*"                 ng-model="last_name"        required="" err-required="<?= T::e('Last name is missing') ?>" maxlength="255" name="last_name" remove-html>
                        <input type="text" placeholder="<?= T::l('1ST LINE OF ADDRESS') ?>*"       ng-model="address1"         required="" err-required="<?= T::e('First line of address is missing') ?>" maxlength="50" name="address1" remove-html>
                        <input type="text" placeholder="<?= T::l('2ND LINE OF ADDRESS') ?>"        ng-model="address2" maxlength="50">
                        <input type="text" placeholder="<?= T::l('CITY') ?>*" class="city"         ng-model="city"             required="" err-required="<?= T::e('City is missing') ?>" maxlength="255" name="city" remove-html>
                        <input type="text" placeholder="<?= T::l('POSTCODE') ?>*" class="postcode" ng-model="postcode"         required="" err-required="<?= T::e('Postal code is missing') ?>" maxlength="45" name="postcode" ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>" err-pattern="<?= T::e("Incorrect postal code"); ?>">
                        <input type="text" placeholder="<?= T::l('PHONE NUMBER') ?>*"              ng-model="phone"            required="" err-required="<?= T::e('Phone number is missing') ?>" maxlength="50" name="phone" remove-html>
                        <input type="text" placeholder="<?= T::l('EMAIL') ?>*"                     ng-model="username"         required="" err-required="<?= T::e('Email is missing') ?>" maxlength="255" name="username" ng-pattern="/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i" err-pattern="<?= T::e('Please enter correct email') ?>">
                        <input type="text" placeholder="<?= T::l('CONFIRM EMAIL') ?>*"             ng-model="confirm_email"    required="" err-required="<?= T::e('Confirm email is missing') ?>" compare-to="username" err-compareTo="<?= T::e('Email and confirmation email doest not match') ?>" maxlength="255" name="confirm_username" ng-pattern="/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i" err-pattern="<?= T::e('Please enter correct email') ?>">
                        <input type="password" placeholder="<?= T::l('PASSWORD') ?>*"              ng-model="password"         required="" err-required="<?= T::e('Password is missing') ?>" maxlength="255" name="password">
                        <input type="password" placeholder="<?= T::l('CONFIRM PASSWORD') ?>*"      ng-model="confirm_password" required="" err-required="<?= T::e('Confirm password is missing') ?>" compare-to="password" err-compareTo="<?= T::e('Passsword and confirmation password does not match') ?>" maxlength="255" name="confirm_password">
                    </div>
                    <div class="please_check">
                        <h6><?= T::l('Please review below and check the appropriate box') ?></h6>
                        <input type="checkbox" id="check_1" name="accepted_terms" ng-model="accepted_terms" required="" err-required="You must accept terms before form submission">
                        <label for="check_1">
                            <?=T::l('Please check this box to indicate that you have read and agree to our')?>
                            <a target="_blank" href="<?=Url::toRoute(['page/page','url'=>'terms-and-conditions'])?>"><?=T::l('Terms and Conditions')?></a>,
                            <a target="_blank" href="<?=Url::toRoute(['page/page','url'=>'terms-of-website-use'])?>"><?=T::l('Terms of Web Site Use')?></a>,
                            <a target="_blank" href="<?=Url::toRoute(['page/page','url'=>'cookies-policy'])?>"><?=T::l('Cookie Policy');?></a> and <a href="<?=Url::toRoute(['page/page','url'=>'acceptable-use-policy'])?>"><?=T::l('Acceptable Use Policy')?></a>, and
                            <a target="_blank" href="<?=Url::toRoute(['page/page','url'=>'alcohol-policy'])?>"><?=T::l('Alcohol Policy')?></a>.
                        </label>
                        <!--input type="checkbox" id="check_2">
                        <label for="check_2"><?= T::l('We would like to contact you with offers relating to products/services of ours that we think you might be interested in. Click here if you object to receiving such offers.') ?></label>
                        <input type="checkbox" id="check_3">
                        <label for="check_3"><?= T::l('We would like to pass your details on to other businesses so that they can e-mail you with offers of goods/services that you might be interested in. Click here if you don\'t want your details to be passed on.') ?></label-->
                    </div>
                    <div class="form_submit">
                        <input ng-disabled="!registration_form.$valid" type="button" ng-click="register()" value="<?= T::l('SUBMIT') ?>">
                    </div>
                </div>
                <div class="thank_you" ng-show="is_registered">
                    <span><?= T::l('THANK YOU') ?></span>
                    <p><?= T::l('Thank you for registering with Dine In. A confirmation email has been sent to {{username}}. Please check your spam folder. Click on the link in the email to verify your identity and activate your account.') ?></p>
                </div>
            </form>
        </div>
    </div>
</section>