<?php
use frontend\components\language\T;
?>
<div id="<?= $popupId ?>" class="menu-modal white-popup-block mfp-hide">
    <h4><span class="option-name">{{selectedItem.name_key}}</span> <span class="total_price">{{currency_symbol}}{{selectedItemWebPrice()}}</span></h4>
    <div class="menu-desc-wrapper">
        <input class="quantity" type="text" placeholder="QTY" val="0" ng-model="selectedItem.quantity" numeric-only-positive="" on-enter="isMenuItemValid(selectedItem) ? setMenuItem(selectedItem) : null" remove-html>
        <p class="menu-desc">{{selectedItem.description_key}} </p>
    </div>
    <div class="select_box">

        <menu-option options="selectedItem.options" item="selectedItem" price-symbol="{{currency_symbol}}"></menu-option>

        <div class="menu_item_instructions">
            <i class="select_set_carret"></i>
            <span class="pseudo_input"><?= T::l('SPECIAL INSTRUCTIONS') ?></span>
            <textarea maxlength="500" ng-model="selectedItem.special_instructions" placeholder="Please Note: Any price altering instructions entered below will be charged to your credit card after your order is processed (extra cheese, side of sour cream, etc)"></textarea>
        </div>
    </div>
    <div class="bottom_buttons_set bottom_block">
        <button class="add-item" ng-click="setMenuItem(selectedItem)" ng-disabled="!isMenuItemValid(selectedItem)"><?= T::l('Add Item To Plate') ?></button>
    </div>
</div>