<?php
use frontend\components\language\T;

$this->title = T::l('Suggest a Restaurant');

?>

<section class="suggest">
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
        <div class="content_set" ng-app="dineinApp" ng-controller="suggestController">
            <h3 class="form_devider h18"><?= T::l('Suggest a Restaurant') ?></h3>
                <form action="" class="items_form" name="suggestion_form" id="suggestion_form">
                    <div ng-show="!is_mail_sent">
                        <validation-summary class="standalone_error" form-name="suggestion_form" form-id="suggestion_form" custom-error="suggestError"></validation-summary>
                        <div class="please_check">
                            <table>
                                <tbody>
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <br>
                                        <?= T::l("If your favourite restaurant is not on the site please help us find them so we can get you your favourite food delivered straight to your door. We want to make sure that we offer our customers a good range of quality restaurants. 
                                            If your favourite restaurant isn't listed yet let us know where, and we'll ask them to join.") ?><br><br>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="personal_details form_devider">
                            <input type="text" placeholder="<?= T::l("RESTAURANT'S NAME") ?>*" ng-model="name" required="" err-required="<?= T::e("Restaurant's name is missing") ?>" maxlength="255" name="name" remove-html>

                            <h6><?= T::l("Restaurant's cuisine") ?></h6>
                            <div class="select_filter cuisines_select">
                                <span class="pseudo_input"></span>
                                <input ng-model="cuisines" required="" err-required="<?= T::e("Restaurant's cuisine is missing") ?>" name="cuisine" type="hidden" placeholder="">
                                <ul>
                                    <?php if ($cuisines): ?>
                                        <?php foreach ($cuisines as $cuisine): ?>
                                            <li ng-click="setCuisine(<?= $cuisine['id'] ?>)"><?= $cuisine['name'] ?></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <h6><?= T::l("Area of London") ?></h6>
                            <div class="select_filter seo_select">
                                <span class="pseudo_input"></span>
                                <input ng-model="area" required="" name="area" type="hidden" err-required="<?= T::e("Restaurant's area of London is missing") ?>" placeholder="">
                                <ul>
                                    <?php if ($seo_areas): ?>
                                        <?php foreach ($seo_areas as $area): ?>
                                            <li ng-click="setArea(<?= $area['id'] ?>)"><?= $area['name'] ?></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <input type="text" placeholder="<?= T::l("RESTAURANT'S PHONE NUMBER") ?>*" ng-model="phone"    maxlength="255" name="phone" remove-html>
                            <input type="text" placeholder="<?= T::l("RESTAURANT'S POST CODE") ?>*"    ng-model="postcode" maxlength="255" name="postcode" ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>" remove-html>
                            <input type="text" placeholder="<?= T::l('YOUR EMAIL ADDRESS') ?>*"        ng-model="email"    maxlength="255" required="" err-required="<?= T::e('Email is missing') ?>" maxlength="255" name="email" ng-pattern="/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i" err-pattern="<?= T::e('Please enter correct email') ?>" remove-html>
                        </div>
                        <div class="form_submit">
                            <input ng-disabled="!suggestion_form.$valid" type="button" ng-click="suggest()" value="<?= T::l('SUGGEST') ?>">
                        </div>
                    </div>
                    <div class="thank_you" ng-show="is_mail_sent">
                        <span><?= T::l('THANK YOU') ?></span>
                        <p><?= T::l('Thank you for restaurant suggestion. Email was sent to Dine In administrators. You request will be reviewed soon.') ?></p>
                    </div>
                </form>
            </div>
        </div>
</section>