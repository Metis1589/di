<?php
use frontend\components\language\T;
?>

<div id="filter-modal" class="white-popup-block mfp-hide login_popup items_form inner_page_modal filter_restaurants">
    <a class="popup-modal-dismiss" href="#">X</a>
    <h6><?= T::l('SELECT FILTER OPTIONS') ?></h6>
    <div class="select_box">
        <div class="select_filter multiple" ng-class="{selected:(temp_filter.cuisines | toArray | filter:{selected: true}).length > 0}">
            <span class="pseudo_input static"></span>
            <input type="hidden" placeholder="<?=T::l('CUISINE')?>">
            <ul ng-init="filter.cuisines = <?= htmlspecialchars(json_encode($cuisines)) ?>; temp_filter.cuisines = <?= htmlspecialchars(json_encode($cuisines)) ?>">
                <li ng-repeat="cuisine in temp_filter.cuisines">
                    <input
                        type="checkbox"
                        name="cuisines[]"
                        value="{{cuisine.id}}"
                        id="cuisine_{{$index}}"
                        ng-model="cuisine.selected"
                        >
                    <label for="cuisine_{{$index}}">{{cuisine.name}}</label>
                </li>
            </ul>
        </div>
        <div ng-repeat="(id, cuisine) in temp_filter.cuisines | toArray | filter:{selected: true}" class="checked_block" ng-class="{even:($index+1) % 2 == 0}">
            {{cuisine.name}}
            <i class="checked_block_carret" ng-click="cuisine.selected = false;"></i>
        </div>

<!--        ETA-->


        <div class="select_filter multiple" ng-class="{selected:(temp_filter.etas | toArray | filter:{selected: true}).length > 0}">
            <span class="pseudo_input static"></span>
            <input type="hidden" placeholder="ETA">

            <ul ng-init="filter.etas = <?= htmlspecialchars(json_encode($filters['etas'])) ?>; temp_filter.etas = <?= htmlspecialchars(json_encode($filters['etas'])) ?>">
                <li ng-repeat="eta in temp_filter.etas">
                    <input
                        type="checkbox"
                        name="etas[]"
                        value="{{eta}}"
                        id="eta_{{$index}}"
                        ng-model="eta.selected"
                        >
                    <label for="eta_{{$index}}">{{eta.from}} - {{eta.to}} mins</label>
                </li>
            </ul>
        </div>
        <div ng-repeat="eta in temp_filter.etas | toArray | filter:{selected: true}" class="checked_block" ng-class="{even:($index+1) % 2 == 0}">
            {{eta.from}} - {{eta.to}}
            <i class="checked_block_carret" ng-click="eta.selected = false;"></i>
        </div>
        <!--        price range-->

        <div class="select_filter multiple" ng-class="{selected:(temp_filter.price_ranges | toArray | filter:{selected: true}).length > 0}">
            <span class="pseudo_input static"></span>
            <input type="hidden" placeholder="PRICE">
            <ul ng-init="filter.price_ranges = <?= htmlspecialchars(json_encode($filters['price_ranges'])) ?>; temp_filter.price_ranges = <?= htmlspecialchars(json_encode($filters['price_ranges'])) ?>">
                <li ng-repeat="range in temp_filter.price_ranges">
                    <input
                        type="checkbox"
                        name="ranges[]"
                        value="{{range}}"
                        id="range_{{$index}}"
                        ng-model="range.selected"
                        >
                    <label for="range_{{$index}}">{{range.name}}</label>
                </li>
            </ul>
        </div>

        <div ng-repeat="range in temp_filter.price_ranges | toArray | filter:{selected: true}" class="checked_block" ng-class="{even:($index+1) % 2 == 0}">
            {{range.name}}
            <i class="checked_block_carret" ng-click="range.selected = false;"></i>
        </div>

<!--        rating-->

        <div class="select_filter multiple" ng-class="{selected:(temp_filter.ratings | toArray | filter:{selected: true}).length > 0}">
            <span class="pseudo_input static"></span>
            <input type="hidden" placeholder="RATING">
            <ul ng-init="filter.ratings = <?= htmlspecialchars(json_encode($filters['ratings'])) ?>; temp_filter.ratings = <?= htmlspecialchars(json_encode($filters['ratings'])) ?>">
                <li ng-repeat="rating in temp_filter.ratings">
                    <input
                        type="checkbox"
                        name="ratings[]"
                        value="{{range}}"
                        id="rating_{{$index}}"
                        ng-model="rating.selected"
                        >
                    <label for="rating_{{$index}}">{{rating.name}}</label>
                </li>
            </ul>
        </div>

        <div ng-repeat="item in temp_filter.ratings | toArray | filter:{selected: true}" class="checked_block" ng-class="{even:($index+1) % 2 == 0}">
            {{item.name}}
            <i class="checked_block_carret" ng-click="item.selected = false;"></i>
        </div>

<!--        charge-->

        <div class="select_filter multiple" ng-class="{selected:(temp_filter.charges | toArray | filter:{selected: true}).length > 0}">
            <span class="pseudo_input static"></span>
            <input type="hidden" placeholder="CHARGE">
            <ul ng-init="filter.charges = <?= htmlspecialchars(json_encode($filters['charges'])) ?>; temp_filter.charges = <?= htmlspecialchars(json_encode($filters['charges'])) ?>">
                <li ng-repeat="charge in temp_filter.charges">
                    <input
                        type="checkbox"
                        name="chargees[]"
                        value="{{charge}}"
                        id="charge_{{$index}}"
                        ng-model="charge.selected"
                        >
                    <label for="charge_{{$index}}"><= {{charge.to}}</label>
                </li>
            </ul>
        </div>

        <div ng-repeat="item in temp_filter.charges | toArray | filter:{selected: true}" class="checked_block" ng-class="{even:($index+1) % 2 == 0}">
            {{item.to}}
            <i class="checked_block_carret" ng-click="item.selected = false;"></i>
        </div>

<!--        delivery/ collection-->

        <div class="select_filter multiple" ng-class="{selected:(temp_filter.delivery_types | toArray | filter:{selected: true}).length > 0}">
            <span class="pseudo_input static"></span>
            <input type="hidden" placeholder="<?= mb_strtoupper(T::l('Delivery')) ?> / <?= mb_strtoupper(T::l('Collection')) ?>">
            <ul ng-init="filter.delivery_types = <?= htmlspecialchars(json_encode($filters['delivery_types'])) ?>; temp_filter.delivery_types = <?= htmlspecialchars(json_encode($filters['delivery_types'])) ?>">
                <li ng-repeat="delivery_type in temp_filter.delivery_types">
                    <input
                        type="checkbox"
                        name="delivery_types[]"
                        value="{{delivery_type}}"
                        id="delivery_type_{{$index}}"
                        ng-model="delivery_type.selected"
                        >
                    <label for="delivery_type_{{$index}}">{{delivery_type.name}}</label>
                </li>
            </ul>
        </div>

        <div ng-repeat="item in temp_filter.delivery_types | toArray | filter:{selected: true}" class="checked_block" ng-class="{even:($index+1) % 2 == 0}">
            {{item.name}}
            <i class="checked_block_carret" ng-click="item.selected = false;"></i>
        </div>

        <div class="checked_block" ng-show="temp_filter.has_delivery">
            <?= T::l('Delivery') ?>
            <i class="checked_block_carret" ng-click="temp_filter.has_delivery = false;"></i>
        </div>
        <div class="checked_block" ng-show="temp_filter.has_collection">
            <?= T::l('Collection') ?>
            <i class="checked_block_carret" ng-click="temp_filter.has_collection = false;"></i>
        </div>

    </div>
    <div class="button_set bottom_buttons_set bottom_block">
        <div class="bottom_buttons_set_wrapper">
            <a href="#" class="popup-modal-dismiss">
                <button type="button" ng-click="clearFilter()"><?= T::l('CLEAR') ?></button>
            </a>
            <a class="popup-modal-dismiss" href="#">
                <button type="button" ng-click="cancelFilter()"><?= T::l('CANCEL') ?></button>
            </a>
            <a href="#" class="popup-modal-dismiss">
                <button type="button" class="apply" ng-click="applyFilter();"><?= T::l('APPLY') ?></button>
            </a>
        </div>
    </div>
</div>