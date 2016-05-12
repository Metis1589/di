<?php
use frontend\components\language\T;

$this->title = T::l('Restaurant Sign Up');

?>

<section class="join-dinein items_form">
    <h1 class="only_desctop"><?= T::l('JOIN THE <img src="/img/main_logo_co.png" style="height: 70px; vertical-align: -15px;"> COMMUNITY') ?></h1>
    <div class="wrapper" ng-app="dineinApp" ng-controller="signUpRestaurantController">
        <div class="intro"><?= T::l("Get in with London's best restaurant delivery service.") ?></div>
        <table class="only_desctop">
            <tr>
                <td>
                    <img src="/img/resto-join/chart.png">
                </td>
                <td class="text-left join-dinein-table">
                    <div class="body">
                        <span class="text-title"><?= T::l('LET US HELP GROW YOUR BUSINESS') ?></span>
                        <?= T::l('
                            At dinein.co.uk exists one of the largest networks<br>
                            of delivery drivers in the UK. We can help your<br>
                            restaurant gain access to a whole new league of<br>
                            clients by providing you with delivery service.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/gb.png">
                </td>
                <td class="text-left join-dinein-table">
                    <div class="body">
                        <span class="text-title"><?= T::l('NOW DELIVERING TO MORE PLACES') ?></span>
                        <?= T::l('
                            We already provide service to restaurants all<br>
                            across London, and are delivering to new places<br>
                            every day. We can help you reach customers<br>
                            previously impossible to serve.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/target.png" class="target">
                </td>
                <td class="text-left join-dinein-table">
                    <div class="body">
                        <span class="text-title"><?= T::l('TARGET NEW CUSTOMERS IN MORE AREAS') ?></span>
                        <?= T::l('
                            Customers are always looking for a great meal.<br>
                            Target new areas by providing delivery service.<br>
                            We currently focus on a couple of defferent areas,<br>
                            which will be listed here.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/phone.png">
                </td>
                <td class="text-left join-dinein-table">
                    <div class="body">
                        <span class="text-title"><?= T::l("TECH SAVY SO YOU DON'T HAVE TO BE") ?></span>
                        <?= T::l("
                            We've built the dinein.co.uk software from the<br>
                            ground up, and have a team of programmers and<br>
                            dispatchers who work to ensure that your orders<br>
                            always end up where they need to be.
                        ") ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/money.png">
                </td>
                <td class="text-left join-dinein-table">
                    <div class="body">
                        <span class="text-title"><?= T::l('THE BEST DEAL IN RESTAURANT DELIVERY') ?></span>
                        <?= T::l('
                            Save money with dinein.co.uk because we offer<br>
                            great deals on commission and other stuff like<br>
                            that so definitely sign up with us.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr ng-show="!interested">
                <td colspan="2">
                    <input type="button" class="toggle-button" ng-click="interested = !interested;" value="<?= T::l('INTERESTED? SIGN UP NOW!') ?>">
                </td>
            </tr>
        </table>
        <table class="only_mobile">
            <tr>
                <td>
                    <img src="/img/resto-join/chart.png">
                </td>
                <td class="text-left join-dinein-table title_cell">
                    <div class="body">
                        <span class="text-title"><?= T::l('LET US HELP GROW YOUR BUSINESS') ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-left join-dinein-table">
                    <div class="description">
                        <?= T::l('
                            At dinein.co.uk exists one of the largest networks
                            of delivery drivers in the UK. We can help your
                            restaurant gain access to a whole new league of
                            clients by providing you with delivery service.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/gb.png">
                </td>
                <td class="text-left join-dinein-table title_cell">
                    <div class="body">
                        <span class="text-title"><?= T::l('NOW DELIVERING TO MORE PLACES') ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-left join-dinein-table">
                    <div class="description">
                        <?= T::l('
                            We already provide service to restaurants all
                            across London, and are delivering to new places
                            every day. We can help you reach customers
                            previously impossible to serve.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/target.png" class="target">
                </td>
                <td class="text-left join-dinein-table title_cell">
                    <div class="body">
                        <span class="text-title"><?= T::l('TARGET NEW CUSTOMERS IN MORE AREAS') ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-left join-dinein-table">
                    <div class="description">
                        <?= T::l('
                            Customers are always looking for a great meal.
                            Target new areas by providing delivery service.
                            We currently focus on a couple of defferent areas,
                            which will be listed here.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/phone.png">
                </td>
                <td class="text-left join-dinein-table title_cell">
                    <div class="body">
                        <span class="text-title"><?= T::l("TECH SAVY SO YOU DON'T HAVE TO BE") ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-left join-dinein-table">
                    <div class="description">
                        <?= T::l("
                            We've built the dinein.co.uk software from the
                            ground up, and have a team of programmers and
                            dispatchers who work to ensure that your orders
                            always end up where they need to be.
                        ") ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="/img/resto-join/money.png">
                </td>
                <td class="text-left join-dinein-table title_cell">
                    <div class="body">
                        <span class="text-title"><?= T::l('THE BEST DEAL IN RESTAURANT DELIVERY') ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-left join-dinein-table">
                    <div class="description end">
                        <?= T::l('
                            Save money with dinein.co.uk because we offer
                            great deals on commission and other stuff like
                            that so definitely sign up with us.
                        ') ?>
                    </div>
                </td>
            </tr>
            <tr ng-show="!interested">
                <td colspan="2">
                    <input type="button" class="toggle-button" ng-click="interested = !interested;" value="<?= T::l('INTERESTED? SIGN UP NOW!') ?>">
                </td>
            </tr>
        </table>

        <div class="content_set send-request-form" ng-show="interested && !is_mail_sent">
            <h3 class="form_devider"><?= T::l('RESTAURANT SIGN UP') ?></h3>
            <form action="" class="items_form" name="restaurant_signup_form" id="restaurant_signup_form">
                <div ng-show="!is_mail_sent">
                    <validation-summary form-name="restaurant_signup_form" form-id="restaurant_signup_form" custom-error="signUpRestoError"></validation-summary>
                    <div class="personal_details">
                        <br>
                        <h3 class="no-padding-bottom"><?= T::l('RESTAURANT INFORMATION') ?></h3>
                        <input type="text" placeholder="<?= T::l('RESTAURANT NAME') ?>*"           ng-model="name"     required="" err-required="<?= T::e('Restaurant name is missing') ?>" maxlength="45" name="title" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                        <input type="text" placeholder="<?= T::l('ADDRESS 1') ?>*"                 ng-model="address1" required="" err-required="<?= T::e('Address 1 name is missing') ?>" maxlength="255" name="address1" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                        <input type="text" placeholder="<?= T::l('ADDRESS 2') ?>"                  ng-model="address2" maxlength="255" name="address2" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                        <input type="text" placeholder="<?= T::l('CITY') ?>*" class="city"         ng-model="city"     required="" err-required="<?= T::e('City is missing') ?>" maxlength="255" name="city" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                        <input type="text" placeholder="<?= T::l('POSTCODE') ?>*" class="postcode" ng-model="postcode" required="" err-required="<?= T::e('Postal code is missing') ?>" maxlength="45" name="postcode" ng-pattern="<?=Yii::$app->params['postcodeRegexp']?>" err-pattern="<?= T::e("Incorrect postal ccode"); ?>" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                        <input type="text" placeholder="<?= T::l('PHONE') ?>*"                     ng-model="phone"    required="" err-required="<?= T::e('Phone number is missing') ?>" maxlength="50" name="phone" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">

                        <div class="select_filter">
                            <span class="pseudo_input"></span>
                            <input ng-model="cuisine_1" name="cuisine_1" type="hidden" placeholder="<?= T::l('CUISINE') ?>">
                            <ul>
                                <?php if (isset($cuisines) && sizeof($cuisines)): ?>
                                    <?php foreach ($cuisines as $cuisine): ?>
                                    <li ng-click="setCuisine1(<?= $cuisine['id'] ?>)"><?= mb_strtoupper($cuisine['name']) ?></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="select_filter">
                            <span class="pseudo_input"></span>
                            <input ng-model="cuisine_2" name="cuisine_2" type="hidden" placeholder="<?= T::l('CUISINE') ?>">
                            <ul>
                                <?php if (isset($cuisines) && sizeof($cuisines)): ?>
                                    <?php foreach ($cuisines as $cuisine): ?>
                                    <li ng-click="setCuisine2(<?= $cuisine['id'] ?>)"><?= mb_strtoupper($cuisine['name']) ?></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="select_filter">
                            <span class="pseudo_input"></span>
                            <input ng-model="cuisine_3" name="cuisine_3" type="hidden" placeholder="<?= T::l('CUISINE') ?>">
                            <ul>
                                <?php if (isset($cuisines) && sizeof($cuisines)): ?>
                                    <?php foreach ($cuisines as $cuisine): ?>
                                    <li ng-click="setCuisine3(<?= $cuisine['id'] ?>)"><?= mb_strtoupper($cuisine['name']) ?></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="select_filter">
                            <span class="pseudo_input"></span>
                            <input ng-model="offer_delivery" name="cuisine" type="hidden" placeholder="<?= T::l('OFFER DELIVERY') ?>">
                            <ul>
                                <li ng-click="setOfferDelivery(0)"><?= T::l('YES') ?></li>
                                <li ng-click="setOfferDelivery(1)"><?= T::l('NO') ?></li>
                            </ul>
                        </div>

                        <div class="select_filter">
                            <span class="pseudo_input"></span>
                            <input ng-model="takeaway_service" name="cuisine" type="hidden" placeholder="<?= T::l('TAKEAWAY SERVICE') ?>">
                            <ul>
                                <li ng-click="setTakeawayService(0)"><?= T::l('YES') ?></li>
                                <li ng-click="setTakeawayService(1)"><?= T::l('NO') ?></li>
                            </ul>
                        </div>
                        <input type="text" placeholder="<?= T::l('TAKEAWAYS PER WEEK COUNT') ?>" ng-model="takeaways_count" maxlength="50" name="takeaways_count" remove-html>
                    </div>

                    <div class="personal_details">
                        <div class="divider-line"></div>
                        <br>
                        <h3 class="no-padding-bottom"><?= T::l('CONTACT INFORMATION') ?></h3>
                        <input type="text" placeholder="<?= T::l('FIRST NAME') ?>*" ng-model="first_name" required="" err-required="<?= T::e('First name is missing') ?>" maxlength="45" name="first_name" remove-html>
                        <input type="text" placeholder="<?= T::l('LAST NAME') ?>"   ng-model="last_name" maxlength="255" name="last_name" remove-html>
                        <input type="text" placeholder="<?= T::l('ROLE AT RESTAURANT') ?>" ng-model="role" maxlength="255" name="role" remove-html>
                        <input type="text" placeholder="<?= T::l('EMAIL') ?>*" ng-model="email" required="" err-required="<?= T::e('Email is missing') ?>" maxlength="255" name="email" ng-pattern="/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i" err-pattern="<?= T::e('Please enter correct email') ?>" remove-html>
                        <input type="text" placeholder="<?= T::l('CONFIRM EMAIL') ?>*" ng-model="confirm_email" required="" compare-to="email" err-compareTo="<?= T::e('Email and confirmation email doest not match') ?>" err-required="<?= T::e('Confirmation email is missing') ?>" maxlength="255" name="confirm_e,ao;" remove-html>
                        <input type="text" placeholder="<?= T::l('PHONE') ?>" ng-model="contact_phone" maxlength="255" name="contact_phone" remove-html> 
                    </div>
                    <div class="form_submit">
                        <input ng-disabled="!restaurant_signup_form.$valid" type="button" ng-click="register()" value="<?= T::l('SUBMIT') ?>">
                    </div>
                </div>
            </form>
        </div>
        <div class="thank_you" ng-show="is_mail_sent">
            <span><?= T::l('THANK YOU') ?></span>
            <p><?= T::l('Email has been sent to Dine In administrators. You request will be reviewed soon.') ?></p>
        </div>
    </div>
</section>