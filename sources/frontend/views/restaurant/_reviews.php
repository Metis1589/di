<?php
use frontend\components\language\T;
?>

<h3 class="form_devider only_desctop"><?= $model['name'] ?></h3>
<div class="custom_review form_devider" ng-show="!add_review">
    <div class="rating rating_{{rating.rating}}"></div>
    <span class="how_reviews"><?= T::l('AVERAGE CUSTOMER REVIEW') ?> ({{rating.count}} <?= T::l('REVIEWS') ?>)</span>
    <a href="#" ng-click="add_review = true;"><?= T::l('LEAVE A REVIEW')?></a>
</div>

<div class="some_review form_devider" ng-repeat="review in reviews" ng-show="!add_review">
    <div class="rating rating_{{review.rating}}"></div>
    <span class="how_reviews">{{review.title}}</span>
    <span class="some_review_text">{{review.text}}</span>
    <span ng-if="review.user.first_name && review.user.last_name" class="name">{{review.user.first_name}} {{review.user.last_name}}</span>
</div>

<div ng-show="add_review" class="add_review">
    <div class="content_set" ng-app="dineinApp" ng-controller="reviewController">
        <form action="" class="items_form" name="review_form" id="review_form">
            <div ng-show="!is_submitted">
                <validation-summary form-name="review_form" form-id="review_form" custom-error="reviewError"></validation-summary>
                <strong class="choice"><?= T::l('Choose a rating') ?></strong>
                <span class="rating-label"><?= T::l('RATING') ?>:</span>
                <span class="star-rating">
                    <input type="radio" name="rating" ng-click="rating = 1;" value="1"><i></i>
                    <input type="radio" name="rating" ng-click="rating = 2;" value="2"><i></i>
                    <input type="radio" name="rating" ng-click="rating = 3;" value="3"><i></i>
                    <input type="radio" name="rating" ng-click="rating = 4;" value="4"><i></i>
                    <input type="radio" name="rating" ng-click="rating = 5;" value="5"><i></i>
                    <input type="hidden" ng-model="rating" required="">
                </span>
                <div class="personal_details form_devider">
                    <input type="text" placeholder="<?= T::l('ORDER NUMBER') ?>*" ng-model="order_number" required="" err-required="<?= T::e('Order number is missing') ?>" maxlength="15" name="order_number" ng-pattern="/^\d+$/" err-pattern="<?= T::e('Only numbers accepted') ?>" remove-html>
                    <input type="text" placeholder="<?= T::l('REVIEW TITLE') ?>*" ng-model="review_title" required="" err-required="<?= T::e('Review title is missing') ?>" maxlength="100" name="review_title" remove-html>
                    <textarea ng-model="text" class="review info_message" name="text" required="" err-required="<?= T::e('Review text is missing') ?>" remove-html></textarea>
                </div>

                <div class="bottom_buttons_set bottom_block">
                    <input ng-disabled="!review_form.$valid || rating.rating == 0" type="button" ng-click="suggest()" value="<?= T::l('SUBMIT REVIEW') ?>">
                </div>
            </div>
            <div class="thank_you" ng-show="is_submitted">
                <span><?= T::l('THANK YOU') ?></span>
                <p><?= T::l('Thank you for submitting review.') ?></p>
            </div>
        </form>
    </div>
</div>