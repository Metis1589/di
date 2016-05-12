<?php
use frontend\components\language\T;
?>
<div ng-show="tab == 'reviews'">
    <h3 class="only_mobile"><?=T::l('MY REVIEWS')?></h3>
    <div class="form_devider"></div>
    <div ng-repeat="review in reviews">
        <div class="some_review user_section_some_review form_devider">
            <h5>{{review.restaurant.name}}</h5>
            <div class="rating user_section_rating rating_{{review.rating}}"></div>
            <span class="review_title">{{review.title}}</span>
            <p>“{{review.text}}”</p>
        </div>
    </div>
</div>