<?php
use frontend\components\language\T;

$this->title = T::l('Contact Us');

?>

<section class="contact">
    <div class="wrapper">
        <div class="sidebar_set_outer">
            <div class="wrapper">
                <div class="sidebar_set">
                    <div class="loyalty_pays">
                        <span><?= T::l('LOYALTY PAYS') ?></span>
                        <p><?= T::l('From the moment<br> you sign up and place an order, you start<br> to earn points towards discounts and free meals.') ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content_set" ng-app="dineinApp" ng-controller="contactUsController">
            <h3 class="form_devider h18"><?= T::l('CONTACT US') ?></h3>
                <form action="" class="items_form" name="contact_form" id="contact_form">
                    <div ng-show="!is_mail_sent">
                        <validation-summary class="standalone_error" form-name="contact_form" form-id="contact_form" custom-error="contactUsError"></validation-summary>
                        <div class="please_check">
                            <table>
                                <tr>
                                    <td colspan="2" class="text-left description">
                                        <br>
                                        <?= T::l('If you have any questions, concerns or comments please contact us and let us know.') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption"><b><?= T::l('Phone') ?></b></td>
                                    <td class="text-left"><a href="skype:020 30 868 867?call">020 30 868 867</a></td>
                                </tr>
                                <tr>
                                    <td class="caption"><b><?= T::l('Email') ?></b></td>
                                    <td class="text-left"><a class="lower" href="mailto:helpme@dinein.co.uk">helpme@dinein.co.uk</a></td>
                                </tr>
                                <tr>
                                    <td class="text-top caption"><b><?= T::l('Snail Mail') ?></b></td>
                                    <td class="text-left contact_info">
                                        Customer Service<br>
                                        dinein.co.uk<br>
                                        Dine In Ltd<br>
                                        10 Southgate Rd<br>
                                        Unit 701<br>
                                        London, N1 3LY<br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="personal_details form_devider">
                            <input type="text" placeholder="<?= T::l('FIRST NAME') ?>*" ng-model="first_name"   required="" err-required="<?= T::e('First name is missing') ?>" maxlength="255" name="first_name" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                            <input type="text" placeholder="<?= T::l('LAST NAME') ?>*"  ng-model="last_name"    required="" err-required="<?= T::e('Last name is missing') ?>" maxlength="255" name="last_name" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                            <input type="text" placeholder="<?= T::l('EMAIL') ?>*"      ng-model="username"     required="" err-required="<?= T::e('Email is missing') ?>" maxlength="255" name="username" ng-pattern="/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i" err-pattern="<?= T::e('Please enter correct email') ?>" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                            <input type="text" placeholder="<?= T::l('PHONE') ?>"       ng-model="phone"        maxlength="255" name="phone" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45">
                            <input type="text" placeholder="<?= T::l('ORDER No') ?>"    ng-model="order_number" maxlength="255" name="order_number" remove-html on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45"   >
                            <textarea ng-model="message" class="contact info_message" name="message" required="" err-required="<?= T::e('Message is missing') ?>" on-focus-scroll-to="on-focus-scroll-to" scroll-to-offset-top="45" remove-html></textarea>
                        </div>
                        <div class="bottom_buttons_set bottom_block">
                            <input ng-disabled="!contact_form.$valid" type="button" ng-click="submit()" value="<?= T::l('SUBMIT') ?>">
                        </div>
                    </div>
                    <div class="thank_you" ng-show="is_mail_sent">
                        <span><?= T::l('THANK YOU') ?></span>
                        <p><?= T::l('Thank you. A contact email has been sent to Dine In. Our managers will be in contact with you soon or Our managers will contact you soon.') ?></p>
                    </div>
                </form>
            </div>
        </div>
</section>