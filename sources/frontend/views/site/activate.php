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
        <div class="content_set" ng-app="dineinApp" ng-controller="registrationController" ng-cloak>
            <h3 class="form_devider"><?= T::l('Account Activation ') ?></h3>
            <form class="items_form">
                <div class="thank_you" ng-show="is_activated">
                    <span><?= T::l('THANK YOU') ?></span>
                    <p><?= T::l('You can login with your credentials.') ?></p>
                </div>
                <div class="thank_you" ng-show="has_activation_error">
                    <p><?= T::l('Account can\'t be activated') ?></p>
                </div>
            </form>
        </div>
    </div>
</section>