<?php
use frontend\components\language\T;

$languageIsoCode = substr(Yii::$app->language, 0, 2);
?>

<div ng-controller="menuController">
    <?= $this->render('_menu_options', ['popupId' => 'menu-modal']) ?>
    <?= $this->render('_menu_options', ['popupId' => 'menu-modal-mobile']) ?>

    <div class="nomenus" ng-class="hasMenus?'hidden':''">
        <?= T::l('There are no available menus in this restaurant') ?>
    </div>
    <!--    {{menus}}-->
    <div ng-repeat="menu in menus" class="menu">
        <div ng-repeat="category in menu.menuCategories" class="category-container">
            <a name="cat-{{category.id}}" id="cat-{{category.id}}" style="top:-95px;display:inline-block;background:transparent;width:100%;height:1px;position:absolute;"></a>
            <div class="menu_course_set">
                <h3 class="only_desctop" ng-class="category.description_key.length ? 'no-bottom-padding' : ''">{{category.name_key}}</h3>
                <h5 class="only_desctop" ng-show="category.description_key.length">{{category.description_key}}</h5>
                <h3 class="only_mobile" ng-class="category.description_key.length ? 'no-bottom-padding' : ''">{{category.name_key}}</h3>
                <h5 class="only_mobile" ng-show="category.description_key.length">{{category.description_key}}</h5>
                <div class="menu_course_item" ng-repeat="item in category.menuItems" menu-item-hover="" menu-item="item" repeat-end="endItemRender()">
                    <a href="#menu-modal" class="menu_hover_text menu_hover" ng-click="selectMenuItem(item)" ng-if="item.options.length > 0"></a>
                    <a href="" class="menu_hover_text" ng-click="setMenuItem(item, false)" ng-if="item.options.length === 0"></a>
                    <span class="menu_item">{{item.name_key}}</span>
                    <a href="#menu-modal-mobile" class="plus only_mobile" ng-click="selectMenuItem(item)" ng-if="item.options.length > 0"></a>
                    <a href="#menu-modal-mobile" class="plus only_mobile" style="background:transparent;position:absolute;width:100%;height:100%;top:0;left:0;display:block;margin-left:0;" ng-click="item.options.length === 0 ? setMenuItem(item, true) : selectMenuItem(item)"></a>
                    <a href="" class="plus only_mobile" ng-click="setMenuItem(item, true)" ng-if="item.options.length === 0"></a>
                    <span class="prise">{{currency_symbol}}{{item.web_price | number:2}}</span>
                    <p class="description">{{item.description_key}}</p>
                    <ul ng-if="item.allergies.length" class="ingredients">
                        <li ng-repeat="allergy in item.allergies"><img ng-src="<?= Yii::$app->params['images_base_url'] ?>allergy/{{allergy.image_file_name}}" alt="{{allergy.name}}">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <a href="#menu-modal" class="menu_hover_text"></a>
    <div class="la_key only_desctop">
        <h3><?= T::l('LIFESTYLE + ALLERGEN KEY') ?></h3>
        <ul class="l_key">
        </ul>
        <ul class="a_key">
            <?php if (isset($allergies) && sizeof($allergies)): ?>
                <?php foreach ($allergies as $allergy): ?>
                <li>
                    <img src="<?= Yii::$app->params['images_base_url'] ?>allergy/<?= $allergy['image_file_name']; ?>" alt="<?= $allergy['symbol_key'] ?>">
                    <span><?= $allergy['name'][$languageIsoCode] ?></span>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div ng-controller="cartController" class="bottom_buttons_set bottom_block only_mobile" ng-show="cart != undefined && cart != null" ng-init="init()" ng-cloak>
        <a ng-show="cart.items.length > 0" ng-click="showCartData()"><?= T::l('VIEW YOUR PLATE') ?></a>
        <button type="button" ng-click="toCheckout()" ng-disabled="cart.items.length === 0" class="" disabled="disabled">{{currency_symbol}}<span>{{cart.total | number:2}}</span> <?= T::l('Checkout')?></button>
    </div>
</div>


<style>
    .hidden {
        display: none;
    }
</style>