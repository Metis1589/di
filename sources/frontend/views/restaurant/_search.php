<?php
use frontend\components\language\T;
use yii\helpers\Url;
?>
<div ng-app="dineinApp" ng-controller="searchFromSinglePageRestaurantsController" ng-init="searchUrl='<?=Url::toRoute('restaurant/search')?>'">
    <form name="form">
        <ul class="main_page_menu">
            <li class="empty">
                <delivery-type
                    class="delivery_asap delivery_asap_mobile_id delivery_asap_id"
                    open-class="'delivery_asap_opened'"
                    type-list="<?= htmlspecialchars(json_encode($delivery_types)) ?>"
                    date-list="<?= htmlspecialchars(json_encode($delivery_dates)) ?>"

                    type="delivery_type"
                    delivery-date="delivery_date"
                    delivery-time="delivery_time"
                    on-select="selectedType(data)"></delivery-type>
            </li>
            <li class="postcode" ng-class="{error: form.postcode.$invalid && form.postcode.$dirty}" style="position:relative;">
                <input type="text"
                       name="postcode"
                       ng-model="postcode"
                       placeholder="POSTCODE"
                       maxlength="10"
                       ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>"
                       ng-keypress="<?php if (isset($page) && $page == 'restaurant'): ?>angular.noop()<?php else: ?>enterHandler($event)<?php endif; ?>"
                       required remove-html>

                <span><?=T::e('INVALID POSTCODE!');?></span>
                <div class="search-by-code" ng-click="<?php if (isset($page) && $page == 'restaurant'): ?>recalculateCharge()<?php else: ?>findRestaurants()<?php endif; ?>"></div>
            </li>
            <li class="find_meal" ng-click="<?php if (isset($page) && $page == 'restaurant'): ?>recalculateCharge()<?php else: ?>findRestaurants()<?php endif; ?>">
                <a href="" ng-click="<?php if (isset($page) && $page == 'restaurant'): ?>recalculateCharge()<?php else: ?>findRestaurants()<?php endif; ?>"><?=T::l('FIND YOUR MEAL');?></a>
            </li>
        </ul>
    </form>
    <span class="or">or</span>
    <a href="#" class="let_us" ng-click="findLocation()" ng-init="findErrorMessage = '<?=T::l('Sorry, we can’t find you! Please enter your postcode.')?>'"><?=T::l('LET US FIND YOU');?></a>
    <div ng-cloak class="find-location-error" style="margin: 0 auto;color: #F58426; font-size: 16px; font-weight: bold; text-transform: uppercase" ng-show="findLocationError">{{findLocationError}}</div>
</div>