<?php
/* @var $this yii\web\View */
use common\components\ImageHelper;
use common\components\IOHelper;
use yii\helpers\Html;
use \yii\helpers\Url;
use frontend\components\language\T;
$this->title = T::l('dinein.co.uk | Fine Dining at Home. We Deliver. | Order Food Online');
?>

<section class="main change_delivery">
    <div class="wrapper" ng-controller="homeController">
        <a href="/" class="logoset"></a>
        <h1><?=T::l('Better food. Better delivery.')?></h1>
        <?= $this->render('//restaurant/_search', ['delivery_types' => $delivery_types, 'delivery_dates' => $delivery_dates]) ?>
        <div>
            <div class="already">
                <div ng-show="!loggedin">
                    <span><?=T::l('ALREADY A MEMBER?');?></span>
                    <a class="popup-modal" href="#test-modal" ><?=T::l('LOGIN NOW');?></a>
                    <span><?=T::l('OR');?></span>
                    <a href="<?=Url::toRoute('site/register');?>"><?=T::l('REGISTER')?></a>
                </div>
                <div ng-show="loggedin">
                    <span class="or"><?= T::l('Hi') ?>, {{username | cut:true:20}}!</span>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="eat_ordering">
    <div class="eat_for_free">
        <h2><?=T::l('EAT FOR FREE')?></h2>
        <div class="mask">
            <h3><?=T::l('LOYALTY PAYS')?></h3>
            <p><?=T::l('From the moment you sign up and place an order, you start to earn points towards discounts and free meals.')?></p>
            <a href="<?= Url::toRoute('/site/register');?>"><?=T::l('Sign up now &#62;')?></a>
        </div>
    </div>
    <div class="ordering">
        <h2><?=T::l('ONE CLICK ORDERING')?></h2>
        <div class="mask">
            <h3><?=T::l('ONE CLICK ORDERING')?></h3>
            <p><?=T::l('We remember every order you place so now you can order your favourite meals in seconds.')?></p>
            <a href="<?= Url::toRoute('/site/register');?>"><?=T::l('Sign up now &#62;');?></a>
        </div>
    </div>
</section>
<section class="partners">
    <div class="wrapper">
        <p><?=T::l('You want the best. We deliver the best.')?></p>
        <ul>
            <?php
            foreach ($restaurants as $restaurant) {
                if ($restaurant['logo_file_name']) {
                    echo Html::tag(
                        'li',
                        Html::img(Yii::$app->params['images_base_url'] .'restaurant/logo/'.$restaurant['logo_file_name']),
                        ['alt'=>$restaurant['name']]
                    );
                }
            }
            ?>
        </ul>
        <p><?= T::l('Track your order in real time.')?></p>
    </div>
</section>
<section class="from_a_moment">
    <div class="wrapper">
        <div>
            <img src="img/main_dinein_logo_co.png" alt="<?=T::l('DELIVERY TRACKER')?>">
            <p><?=T::l('From the moment you order, to the moment your food is in your hands,  you can track your meal at every point along the way.')?></p>
        </div>
    </div>
</section>
<sticky-string></sticky-string>

<footer>
    <div class="wrapper">
        <!--<div class="hide_footer"><a href="#">^</a></div> -->
        <div class="first_nav">
            <ul>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'about-us'])?>"><?=T::l('about us')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'faq'])?>"><?=T::l('faq')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'contact-us'])?>"><?=T::l('contact us');?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'suggest-a-restaurant'])?>"><?=T::l('suggest a restaurant');?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'restaurant-sign-up'])?>"><?=T::l('restaurant sign up');?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'site-map'])?>"><?=T::l('site map')?></a></li>
                <li><a hrrestef="<?=Url::toRoute(['page/page','url'=>'jobs'])?>"><?=T::l('jobs');?></a></li>
                <li>
                    <a target="_blank" href="https://twitter.com/DineInNow"><img src="img/twitter_icon.png" alt="twitter"></a>
                    <a target="_blank" href="https://www.facebook.com/pages/Dine-In-Fine-Dining-Delivered/164735113578217"><img src="img/fb_icon.png" alt="facebook"></a>
                    <a target="_blank" href="https://instagram.com/dineinnow/"><img src="img/instagram_icon.png" alt="instagram"></a>
                </li>
            </ul>
        </div>
        <div class="second_nav">
            <ul>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'privacy-policy'])?>"><?=T::l('privacy policy')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'terms-and-conditions'])?>"><?=T::l('terms and conditions')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'alergy-information'])?>"><?=T::l('allergy information')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'cookies-policy'])?>"><?=T::l('cookies policy')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'terms-of-website-use'])?>"><?=T::l('terms of website use')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'acceptable-use-policy'])?>"><?=T::l('acceptable use policy')?></a></li>
                <li><a href="<?=Url::toRoute(['page/page','url'=>'alcohol-policy'])?>"><?=T::l('alcohol policy')?></a></li>
            </ul>
        </div>
        <div class="company">
            <p><?=T::l('Dine In Ltd, 10 Southgate Rd, Unit 701, London, N1 3LY
                Company #: 7458196, VAT #: GB174 0756 03')?></p>
            <div class="payment">
                <img src="img/footer_visa.png" alt="visa" width="31" height="20">
                <img src="img/footer_mcard.png" alt="master card" width="31" height="20">
                <img src="img/footer_discover.png" alt="discover network" width="31" height="20">
                <img src="img/footer_american.png" alt="american express" width="31" height="20">
            </div>
        </div>
        <div class="delivery">

            <div class="delivery_item item_1">
                <ul>
                    <?php for ($i = 0; $i < count($footer_links) / 5 * 1; $i++): ?>

                        <li><a href="<?= $footer_links[$i]['url'] ?>"><?= $footer_links[$i]['name'] ?></a></li>

                    <?php endfor; ?>
                </ul>
            </div>
            <div class="delivery_item item_2">
                <ul>
                    <?php for ($i = count($footer_links) / 5 * 1+1; $i < count($footer_links) / 5 * 2 + 1; $i++): ?>

                        <li><a href="<?= $footer_links[$i]['url'] ?>"><?= $footer_links[$i]['name'] ?></a></li>

                    <?php endfor; ?>
                </ul>
            </div>
            <div class="delivery_item item_3">
                <ul>
                    <?php for ($i = count($footer_links) / 5 * 2+1; $i < count($footer_links) / 5 * 3+1; $i++): ?>

                        <li><a href="<?= $footer_links[$i]['url'] ?>"><?= $footer_links[$i]['name'] ?></a></li>

                    <?php endfor; ?>
                </ul>
            </div>
            <div class="delivery_item item_4">
                <ul>
                    <?php for ($i = count($footer_links) / 5 * 3+1; $i < count($footer_links) / 5 * 4+1; $i++): ?>

                        <li><a href="<?= $footer_links[$i]['url'] ?>"><?= $footer_links[$i]['name'] ?></a></li>

                    <?php endfor; ?>
                </ul>
            </div>
            <div class="delivery_item item_5">
                <ul>
                    <?php for ($i = count($footer_links) / 5 * 4+1; $i < count($footer_links) / 5 * 5; $i++): ?>

                        <li><a href="<?= $footer_links[$i]['url'] ?>"><?= $footer_links[$i]['name'] ?></a></li>

                    <?php endfor; ?>
                </ul>
            </div>
        </div>
    </div>
</footer>
