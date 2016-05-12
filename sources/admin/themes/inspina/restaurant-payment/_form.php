<?php

use admin\common\AHtml;
use common\components\language\T;
use common\enums\RestaurantPaymentChargeType;
use common\enums\RestaurantPaymentFeeType;
use common\enums\RestaurantPaymentType;

/* @var $this yii\web\View */
/* @var $model admin\forms\RestaurantPaymentForm */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="restaurant-payment-form" ng-controller="paymentController">
    <form name="tableform">
        <?= AHtml::waitSpinner(['ng-show' => 'paymentFormIsSubmitting']) ?>

        <div class="row">
            <div class="col-xs-2">
                <?= AHtml::input('Bank Details',
                    ['type'=>'radiolist', 'items'=>RestaurantPaymentType::getLabels(), 'id'=>'rate_type', 'options' => ['itemOptions' => ['ng-model' => 'payment.type', 'ng-required' => 'true']]],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
            <div class="col-xs-2">
                <?= AHtml::input('Account Holder Name',
                    ['type'=>'text', 'maxlength'=>'150', 'id'=>'account_holder_name', 'required'=>'', 'ng-model'=>'payment.account_holder_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']],
                    ['ng-if' => 'payment.type == "'.RestaurantPaymentType::Bank .'"']
                ) ?>
            </div>
            <div class="col-xs-2">
                <?= AHtml::input('Bank Name',
                    ['type'=>'text', 'maxlength'=>'150', 'id'=>'bank_name', 'required'=>'', 'ng-model'=>'payment.bank_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']],
                    ['ng-if' => 'payment.type == "'.RestaurantPaymentType::Bank .'"']
                ) ?>
            </div>
            <div class="col-xs-2">
                <?= AHtml::input('Sort Code',
                    ['type'=>'text', 'maxlength'=>'50', 'id'=>'sort_code', 'required'=>'', 'ng-model'=>'payment.sort_code'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']],
                    ['ng-if' => 'payment.type == "'.RestaurantPaymentType::Bank .'"']
                ) ?>
            </div>
            <div class="col-xs-2">
                <?= AHtml::input('Account Number',
                    ['type'=>'text', 'maxlength'=>'50', 'id'=>'account_number', 'required'=>'', 'ng-model'=>'payment.account_number'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']],
                    ['ng-if' => 'payment.type == "'.RestaurantPaymentType::Bank .'"']
                ) ?>
            </div>

        </div>

        <hr/>

        <h3><?= T::l('Sales Commission') ?></h3>
        <div class="row">
            <div class="col-xs-4">
                <?= AHtml::input('Fee',
                    ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'sales_fee_value', 'min'=>'0', 'max' => '100', 'ng-model'=>'payment.sales_fee_value', 'required' => ''],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']]
                ) ?>
            </div>
            <div class="col-xs-4">
                <?= AHtml::input('Fee Type',
                    ['type'=>'radiolist', 'items'=>RestaurantPaymentFeeType::getLabels(), 'id'=>'sales_fee_type', 'options' => ['itemOptions' => ['ng-model' => 'payment.sales_fee_type', 'ng-required' => 'true']]],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
            <div class="col-xs-4">
                <?= AHtml::input('Charge Type',
                    ['type'=>'radiolist', 'items'=>RestaurantPaymentChargeType::getLabels(), 'id'=>'sales_charge_type', 'options' => ['itemOptions' => ['ng-model' => 'payment.sales_charge_type', 'ng-required' => 'true']]],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
        </div>

        <hr/>

        <h3><?= T::l('Collection Sales Commission') ?></h3>

        <div class="row">
            <div class="col-xs-4">
                <?= AHtml::input('Fee',
                    ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'collection_fee_value', 'min'=>'0', 'max' => '100', 'ng-model'=>'payment.collection_fee_value', 'required' => ''],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']]
                ) ?>
            </div>
            <div class="col-xs-4">

                <?= AHtml::input('Fee Type',
                    ['type'=>'radiolist', 'items'=>RestaurantPaymentFeeType::getLabels(), 'id'=>'collection_fee_type', 'options' => ['itemOptions' => ['ng-model' => 'payment.collection_fee_type', 'ng-required' => 'true']]],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
            <div class="col-xs-4">

                <?= AHtml::input('Charge Type',
                    ['type'=>'radiolist', 'items'=>RestaurantPaymentChargeType::getLabels(), 'id'=>'collection_charge_type', 'options' => ['itemOptions' => ['ng-model' => 'payment.collection_charge_type', 'ng-required' => 'true']]],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
        </div>

        <?= AHtml::errorNotification('{{paymentSubmitError}}', ['ng-show' => 'hasPaymentSubmitError()']) ?>

        <?= AHtml::saveButton(['ng-click' => 'save()','ng-disabled' => 'tableform.$invalid']) ?>

    </form>

</div>
