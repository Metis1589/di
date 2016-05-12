<?php
use frontend\components\language\T;

$this->title = T::l('Restaurants');
?>

<input type="hidden" id="seo_area_id" value="<?= $seo_area_id ?>">
<input type="hidden" id="cuisine_id" value="<?= $cuisine_id ?>">

<section class="restaurants change_delivery">
    <div class="wrapper" ng-app="dineinApp" ng-controller="searchRestaurantsController" ng-click="controllerClicked($event)" ng-cloak>

        <?= $this->render('_filter', ['cuisines' => $cuisines, 'filters' => $filters]) ?>
        <?= $this->render('_deliveryinfo', ['cuisines' => $cuisines, 'filters' => $filters,'deliveryTypeFiler'=>$deliveryTypeFiler]) ?>

        <div class="buttons_set main_page_menu">
            <div class="close" ng-class="{invalid:postcodeForm.$invalid}">
                <form name="postcodeForm">
                    <input type="text"
                           ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>"
                           ng-model="postcode" required ng-enter="changePostcode()" ng-change="changePostcode()">
                </form>
            </div>
            <delivery-type
                class="delivery_asap asap delivery_asap_id"
                open-class="'delivery_asap_opened'"
                external-classes=""
                type-list="<?= htmlspecialchars(json_encode($deliveryTypeFiler['types'])) ?>"
                date-list="<?= htmlspecialchars(json_encode($deliveryTypeFiler['dates'])) ?>"
                type="delivery_type"
                delivery-date="delivery_date"
                delivery-time="delivery_time"
                on-select="setType(data)"></delivery-type>
            <a class="popup-modal" href="#filter-modal"><button type="button" class="button_filter"><?= T::l('FILTER') ?></button></a>
        </div>
        <div class="links_set">
            <a href="#delivery_info_mobile" class="link_asap">{{postcode}} / {{delivery_type}} &#62;</a>
            <a href="#filter-modal" class="link_filter"><?= T::l('FILTER') ?></a>
        </div>
        <span ng-bind="error"></span>
        <table class="restaurants-list" ng-hide="diOpened">
            <thead>
            <tr data-text="RESTAURANT text 1" data-href="href_1">
                <td></td>
                <td></td>
                <td><a href="" ng-click="orderByField='eta';             reverseSort = !reverseSort;"><?= T::l('ETA') ?></a></td>
                <td><a href="" ng-click="orderByField='price_range';     reverseSort = !reverseSort;"><?= T::l('Price') ?></a></td>
                <td><a href="" ng-click="orderByField='first_cuisine.name'; reverseSort = !reverseSort;"><?= T::l('Cuisine') ?></a></td>
                <td><a href="" ng-click="orderByField='delivery_charge'; reverseSort = !reverseSort;"><?= T::l('Charge') ?></a></td>
                <td><a href="" ng-click="orderByField='rating';          reverseSort = !reverseSort;"><?= T::l('Rating') ?></a></td>
            </tr>
            </thead>
            <tbody>
                <tr
                    ng-repeat="restaurant in restaurants | filter:filterRestaurants | filter:filterCharges | filter:filterETA | filter:filterDeliveryType | orderBy:orderByField:reverseSort"
                    valign="top"
                    data-text="{{!restaurant.is_available_for_time ? '<?= T::l('RESTAURANT CURRENTLY CLOSED') ?>' : (restaurant.name + ' (' + (restaurant.has_delivery && restaurant.has_collection ? '<?= mb_strtoupper(T::l('Delivery And Collection')) ?>' : (restaurant.has_delivery ? '<?= mb_strtoupper(T::l('Delivery Only')) ?>' : (restaurant.has_collection ? '<?= mb_strtoupper(T::l('Collection Only')) ?>' : '' ))) + ')') }}"
                    data-href="/{{restaurant.id}}/restaurant/{{formatPath(restaurant.seo_area)}}/{{formatPath(restaurant.first_cuisine.name)}}/{{formatPath(restaurant.slug)}}.html"
                    repeat-end="bindRestaurantsTable()"
                    ng-class="restaurant.is_available_for_time ? '' : 'inactive'">
                <td class="restaurant-name">
                    {{restaurant.name}}
                </td>
                <td ng-class='{"new" : restaurant.is_newest}'></td>
                <td>{{restaurant.eta == 55555 ? '<?=T::l('N/A')?>' : (restaurant.eta-<?=Yii::$app->params['etaDiff']?>)+'-'+(restaurant.eta+<?=Yii::$app->params['etaDiff']?>) + ' ' +'<?= T::l('MINS')?>' }}</td>
                <td class="price-level">
                    <span class="highlighted">{{restaurant.currency_sign.repeat(restaurant.price_range)}}</span><span>{{restaurant.currency_sign.repeat(5 - restaurant.price_range)}}</span>
                </td>
                <td>
                    {{restaurant.first_cuisine.name}}
<!--                    <div ng-repeat="cuisine in restaurant.cuisines">-->
<!--                        {{cuisine.name}}-->
<!--                    </div-->
                </td>
                <td ng-if="restaurant.delivery_charge != null">
                    {{restaurant.currency_sign}}{{restaurant.delivery_charge | number:2}}
                </td>
                <td ng-if="restaurant.delivery_charge == null">
                    <?= T::l('Enter Postcode') ?>
                </td>
                <td class="rating rating_{{restaurant.rating}}">
                    ({{restaurant.reviews_count}})
                </td>
            </tr>

            </tbody>
        </table>
        <a class="hover_text" onClick="" href="#"></a>
<!--        <h3>AMERICAN RESTAURANT DELIVERY</h3>-->
<!--        <p>Straight from the states, American cuisine offers a vibrant array of diverse ingredients and meals, stemming from the rich history of cultural immigration into the United States. From a juicy sirloin steak to the cultural icon of apple pie, American cuisine is as diverse as the states that make up the country. Taste a piece of the East coast with New York style pizza or head to the heartland for a Chicago-style hotdog. The best part? You don't actually have to travel. All of this comes directly to your door as takeaway . Get excited for the best American restaurants in London. Takeaway is right at your fingertips with the use of our highly advanced food order system, allowing you to browse the online menus of fine dining American restaurants across London. Easily choose what you would like to order and then track the status of your meal using our unparalleled order tracking page. Dine in has never been easier, as dinein.co.uk only partners with the finest American restaurants in London, while bringing it directly to your location. Does your favourite restaurant deliver? Don't worry about that with dinein.co.uk. We provide fine dining at home or your office, delivering your order quickly, while maintaining the freshness you would expect while dining in at the restaurant. Not to mention we only offer restaurants with the highest ratings and freshest food. Our staff of highly trained professional drivers pick up your order from the designated restaurant and assure your meal stays fresh with our advanced temperature control storage system. You will never have to worry about your food getting cold or losing its taste, as we promise fast delivery with a dedication to maintaining restaurant style freshness. During the drop off, our drivers will greet you with courteous conversation and smiling faces. We pride ourselves in offering the exact same service you would receive while dining in at the restaurant of your choice. The days of ordering food over the phone are long gone, as our online menu allows for you to order food online with only a few easy steps. Are you curious whether your favourite restaurant delivers? Browse our wide selection of the best restaurants in London and we might deliver for them! Low quality takeaway food has become the standard and our primary goal is to reverse this trend. Dine in quality food at home is now a reality through our online menu, giving you quality food, delivered fast. A combination of takeaway service with dine-in quality takeaway food makes us the best website for ordering and receiving your fine dining in London. dinein.co.uk. Fine dining at home. We deliver.</p>-->
    </div>
</section>
