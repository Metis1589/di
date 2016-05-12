<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
use frontend\components\language\T;
use yii\helpers\Url;
$controller = Yii::$app->controller->id;
$action     = Yii::$app->controller->action->id;
$paramstr   = Yii::$app->params['displayTitleOn'];
$hideTitleInHeader = !isset($paramstr[$controller]) || (isset($paramstr[$controller]) && !in_array($action, $paramstr[$controller]));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="pushmenu-push" ng-app="dineinApp" resize-notifier push-menu-main>
        <script type="text/ng-template" id="validation-summary">
            <?= $this->render('../common/_validation-summary') ?>
        </script>
        <div id="mask_modal" class="white-popup-block mfp-hide"></div>
        <?php $this->beginBody() ?>

        <nav class="pushmenu pushmenu-left" push-menu-left ng-controller="userMenuController" style="left:-1100px;" ng-cloak>
            <div class="pushmenu-push-inner" style="right: 1100px; left: -1100px;">
                <a ng-hide="loggedin" class="popup-modal" href="#test-modal" href="#"><?=T::l('LOG IN')?></a>
                <a ng-hide="!loggedin" ng-click="logoutAction()" href="#"><?=T::l('SIGN OUT')?></a>
                <a ng-hide="loggedin" href="<?= Url::toRoute('site/register') ?>"><?=T::l('SIGN UP');?></a>
                <a ng-hide="loggedin" href="<?= Url::toRoute('restaurant/search') ?>"><?=T::l('SEE ALL RESTAURANTS');?></a>
                <a ng-show="loggedin" href="<?= Url::toRoute([ 'user/user', '#' => 'membership' ]) ?>"><?= T::l('MEMBERSHIP') ?></a>
                <a ng-show="loggedin" href="<?= Url::toRoute([ 'user/user', '#' => 'addresses' ]) ?>"><?= T::l('MY LOCATIONS') ?></a>
                <a ng-show="loggedin" href="<?= Url::toRoute([ 'user/user', '#' => 'loyality-points' ]) ?>"><?= T::l('MY DINEIN POINTS') ?></a>
                <a ng-show="loggedin" href="<?= Url::toRoute([ 'user/user', '#' => 'my-orders' ]) ?>"><?= T::l('MY ORDERS') ?></a>
                <a ng-show="loggedin" href="<?= Url::toRoute([ 'user/user', '#' => 'my-reviews' ]) ?>"><?= T::l('MY REVIEWS') ?></a>
                <a ng-show="loggedin" href="<?= Url::toRoute('restaurant/search') ?>"><?=T::l('SEE ALL RESTAURANTS');?></a>
                <a href="<?= Url::toRoute('order/tracker') ?>"><?= T::l('DELIVERYTRACKER') ?><span>TM</span></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'about-us'])?>"><?= T::l('ABOUT US') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'contact-us'])?>"><?= T::l('CONTACT US') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'alergy-information' ])?>"><?= T::l('ALLERGY & LIFESTYLE KEY') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'faq' ]) ?>"><?= T::l('FAQ') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'suggest-a-restaurant' ]) ?>"><?= T::l('SUGGEST A RESTAURANT') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'restaurant-sign-up' ]) ?>"><?= T::l('RESTAURANT SIGN UP') ?></a>
                <a href="<?= Url::toRoute([ 'site/site-map' ]) ?>"><?= T::l('SITE MAP') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'jobs' ]) ?>"><?= T::l('JOBS') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'privacy-policy' ]) ?>"><?= T::l('PRIVACY POLICY') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'terms-and-conditions' ]) ?>"><?= T::l('TERMS AND CONDITIONS') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'cookies-policy' ]) ?>"><?= T::l('COOKIES POLICY') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'terms-of-website-use' ]) ?>"><?= T::l('TERMS OF WEBSITE USE') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'acceptable-use-policy' ]) ?>"><?= T::l('ACCEPTABLE USE POLICY') ?></a>
                <a href="<?= Url::toRoute([ 'page/page', 'url' => 'alcohol-policy' ]) ?>"><?= T::l('ALCOHOL POLICY') ?></a>
            </div>
        </nav>

        <nav ng-controller="userMenuController" ng-cloak class="top-menu">
            <?= $this->render('../layouts/_login', []) ?>
            <div class="wrapper">
                <button type="button" id="nav_list" class="left_side_mobile_menu" push-menu-button ng-click="menuClick($event)"></button>
                <a class="header_logo_set" href="/">
                    <img src="/img/main_logo_co.png" alt="Dinein">
                </a>
                <ul>
                    <li class="tracker"><a class="<?=(Url::toRoute('order/tracker') == Url::to()?'active':'')?>"href="<?=Url::toRoute('order/tracker')?>"><?=T::l('Delivery Tracker')?></a></li>
                    <li ng-hide="loggedin" class="login"><a class="popup-modal" href="#test-modal"><?=T::l('Login')?></a></li>
                    <li ng-hide="loggedin" class="sign_up"><a href="<?= Url::toRoute('/site/register');?>"><?=T::l('Sign up')?></a></li>
                    <li ng-hide="!loggedin" class="login"><a href="<?=Url::toRoute('user/user')?>">{{username | cut:true:20}}</a></li>
                    <li ng-hide="!loggedin" class="sign_up"><a ng-click="logoutAction()" href="#"><?=T::l('Sign out')?></a></li>
                </ul>
                <button type="button" class="right_side_mobile_menu" ng-class="{hidden :!restaurantUrl}" ng-click="clickRightButton($event)"></button>
                <span class="title"><?=!empty($this->title) && !$hideTitleInHeader ? $this->title : '' ;?></span>
            </div>
        </nav>

        <input type="hidden" id="_client_key" value="<?= Yii::$app->params['client_key']; ?>">
        <input type="hidden" id="_gateway_url" value="<?= Yii::$app->params['gateway_url']; ?>">

        <?= $content ?>

        <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
