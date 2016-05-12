<?php
use frontend\components\language\T;

$this->title = T::l('Checkout');
?>

<div ng-app="dineinApp" ng-controller="checkoutController">

    <script type="text/ng-template" id="delivery_address">
        <?= $this->render('_address', ['prefix' => 'delivery_address']) ?>
    </script>

    <script type="text/ng-template" id="billing_address">
        <?= $this->render('_address', ['prefix' => 'billing_address']) ?>
    </script>

    <script type="text/ng-template" id="payment">
        <?= $this->render('_payment') ?>
    </script>
    <script type="text/ng-template" id="title-select">
        <?= $this->render('../common/_title-select')?>
    </script>

<section class="section_wrap items_form checkout_page">
    <div class="wrapper">
        <div class="sidebar_set_outer">
            <div class="wrapper">
                <?= $this->render('../restaurant/_cart', [
                    'allow_checkout'       => false,
                    'deliveryTypeFiler'    => $deliveryTypeFiler,
                    'allowModifying'       => false,
                    'disableDeliveryType' => true
                ]) ?>
            </div>
        </div>

        <div class="content_set sticky_text">
            <div class="invalid_payment" ng-show="paymentResult">
                {{paymentResult}}
            </div>
            <h3 class="form_devider"><?= T::l('CHECKOUT INFO') ?></h3>
            <div class="additional form_devider slide_section" ng-class="{'is_opened': panel === 'requirements'}">
                <h6 ng-click="showPanel('requirements')"><?= T::l('Additional Requirements') ?> <span>(<?= T::l('Optional') ?>)</span></h6>
                <textarea
                    ng-model="additional_requirements"
                    class="info_message"
                    placeholder="<?= T::l('Please enter any special requests? We will pass them on to the restaurant.') ?>"
                    data-initial-placeholder="<?= T::l('Please enter any special requests? We will pass them on to the restaurant.') ?>"></textarea>
                <h6 class="notopmargin"><?= T::l('Need Utensils?') ?> <span>(<?= T::l('Optional') ?>)</span></h6>
                <input ng-model="include_utensils" type="checkbox" id="check_8">
                <label for="check_8"><?= T::l('Please include disposable cutlery and utensils') ?></label>
            </div>

            <div class="delivery_address form_devider items_form slide_section" ng-class="{'is_opened': panel === 'deliveryAddress'}" ng-show="delivery_type == 'DeliveryAsap' || delivery_type == 'DeliveryLater'">
                <form action="" name="tableform_delivery_address" id="tableform_delivery_address">
                    <div class="personal_details">
                        <h6 ng-click="showPanel('deliveryAddress')"><?= T::l('Delivery Address') ?></h6>
                        <div>
                            <div ng-include="'delivery_address'"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="items_form form_devider slide_section" ng-class="{'is_opened': panel === 'payment'}" ng-if="corporateInfo != null">
                <h6 ng-click="showPanel('payment')"><?= T::l('Payment') ?></h6>
                <div ng-show="panel === 'payment'" class="payment_check">
                    <div>
                        <div ng-include="'payment'"></div>
                    </div>
                </div>
            </div>
            <div class="payment_section items_form form_devider slide_section" ng-class="{'is_opened': panel === 'billingAddress'}">
                <h6 ng-click="showPanel('billingAddress')"><?= T::l('Billing Address') ?></h6>
                <div ng-show="panel === 'billingAddress'" ng-init="billing_address_type = 1" class="payment_check">
                    <form action="" name="tableform_billing_address" id="tableform_billing_address">
                        <div class="billing_address" ng-show="delivery_type == 'DeliveryAsap' || delivery_type == 'DeliveryLater'">
                            <div class="ba_wrap">
                                <input type="radio" ng-model="billing_address_type" value="1" id="check_1">
                                <label for="check_1"><?= T::l('Same as Delivery Address') ?></label>
                                <input type="radio" ng-model="billing_address_type" value="2" id="check_2">
                                <label for="check_2"><?= T::l('Different from Delivery Address') ?></label>
                            </div>
                        </div>
                        <div ng-show="billing_address_type == 2 || delivery_type == 'CollectionAsap' || delivery_type == 'CollectionLater'">
                            <div ng-include="'billing_address'">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <button ng-disabled="!((billing_address_type == 1 || tableform_billing_address.$valid) && (tableform_delivery_address.$valid || delivery_type == 'CollectionAsap' || delivery_type == 'CollectionLater' ) && isTotalAllocationValid()) || !cart.is_valid" ng-click="checkout();" type="button" class="ready_to_pay"><?= T::l("You're Ready to pay!") ?></button>
        </div>
<!--        <div class="checkout_info_descr">-->
<!--            <p>When you place an order we store your basic details. We only do this to help speed up your ordering process the next time you use dinein.co.uk.</p>-->
<!--            <p>We will never share this information with any third party. We are registered with the Information Commissioner Office (Z2541052) and take your privacy very seriously, if you have questions please contact us.</p>-->
<!--        </div>-->
    </div>
</section>