<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'vat_number')->textInput(['maxlength' => 25]) ?>

    <div class="form-group clearfix">
        <legend><?= T::l('Physical Address') ?></legend>
        <?= $form->field($model->physicalAddress, "[physical]name")->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model->physicalAddress, "[physical]phone")->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model->physicalAddress, "[physical]address1")->textInput(['maxlength' => 50])->label(T::l('Address 1')) ?>

        <?= $form->field($model->physicalAddress, "[physical]address2")->textInput(['maxlength' => 50])->label(T::l('Address 2')) ?>

        <?= $form->field($model->physicalAddress, "[physical]country_id")->dropDownList(yii\helpers\ArrayHelper::map(\admin\common\ArrayHelper::translateList(common\models\Country::find()->all()),'id','name_key'),['prompt'=>T::l('Choose country')]) ?>

        <?= $form->field($model->physicalAddress, "[physical]city")->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model->physicalAddress, "[physical]postcode")->textInput(['maxlength' => 45]) ?>
    </div>

    <div class="form-group clearfix">
        <legend><?= T::l('Billing Address') ?></legend>
        <?= $form->field($model->billingAddress, "[billing]name")->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model->billingAddress, "[billing]phone")->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model->billingAddress, "[billing]address1")->textInput(['maxlength' => 50])->label(T::l('Address 1')) ?>

        <?= $form->field($model->billingAddress, "[billing]address2")->textInput(['maxlength' => 50])->label(T::l('Address 2')) ?>

        <?= $form->field($model->billingAddress, "[billing]country_id")->dropDownList(yii\helpers\ArrayHelper::map(\admin\common\ArrayHelper::translateList(common\models\Country::find()->all()),'id','name_key'),['prompt'=>T::l('Choose country')]) ?>

        <?= $form->field($model->billingAddress, "[billing]city")->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model->billingAddress, "[billing]postcode")->textInput(['maxlength' => 45]) ?>
    </div>

    <?= $form->field($model, 'payment_frequency')->dropDownList(common\enums\PaymentFrequencyType::getLabels(), ['prompt' => '']) ?>

    <?= $form->field($model, 'payment_frequency_amount')->textInput() ?>

    <?= $form->field($model, 'sales_fee')->textInput() ?>

    <?= $form->field($model, 'is_vat_exclusive')->checkbox(['class' => 'i-checks']) ?>

    <div class="form-group clearfix">
        <legend><?= T::l('Minimum Order Details') ?></legend>
        <?= $form->field($model, 'min_order_morning_time_from')->textInput(['maxlength' => 50, 'class' => 'timepicker', 'data-show24hours' => 'true', 'data-showSeconds' => 'true']) ?>

        <?= $form->field($model, "min_order_morning_time_to")->textInput(['maxlength' => 50, 'class' => 'timepicker', 'data-show24hours' => 'true', 'data-showSeconds' => 'true']) ?>

        <?= $form->field($model, 'min_order_morning_amount')->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model, "min_order_evening_time_from")->textInput(['maxlength' => 50, 'class' => 'timepicker', 'data-show24hours' => 'true', 'data-showSeconds' => 'true']) ?>

        <?= $form->field($model, "min_order_evening_time_to")->textInput(['maxlength' => 50, 'class' => 'timepicker', 'data-show24hours' => 'true', 'data-showSeconds' => 'true']) ?>

        <?= $form->field($model, 'min_order_evening_amount')->textInput(['maxlength' => 50]) ?>
    </div>

    <div class="form-group clearfix">
        <legend><?= T::l('Company-wide Spending Limits for this Client') ?></legend>
        <?= $form->field($model, 'daily_limit')->textInput() ?>

        <?= $form->field($model, 'weekly_limit')->textInput() ?>

        <?= $form->field($model, 'monthly_limit')->textInput() ?>

        <?= $form->field($model, 'limit_type')->dropDownList([ 'Soft' => 'Soft', 'Hard' => 'Hard', ], ['prompt' => '']) ?>

        <?= $this->render('../common/_record_type', [
            'model' => $model,
            'form'  => $form
        ]) ?>

        <?= $this->render('../common/_record_info', [
            'model' => $model,
        ]) ?>
    </div>

    <div class="form-group clearfix">
        <legend><?= T::l('Delivery allowed to specific addressed only') ?></legend>
        <?= Html::dropDownList('specific_delivery', $model->noAddress ? 'No' : 'Yes', [ 'No' => 'No', 'Yes' => 'Yes' ], [ 'class' => 'form-control', 'id' => 'specific_delivery' ]) ?>
    </div>

    <div class="row specific_delivery hidden">
        <div class="col-xs-12 add-delivery-address-btn">
            <div class="form-group">
                <?= Html::button(Yii::t('label', 'Add delivery address'), ['class' => 'btn btn-primary col-xs-12 add-address-group']) ?>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><?= T::l('Name') ?></th>
                    <th><?= T::l('Address 1') ?></th>
                    <th><?= T::l('Address 2') ?></th>
                    <th><?= T::l('Country') ?></th>
                    <th><?= T::l('City') ?></th>
                    <th><?= T::l('Postcode') ?></th>
                    <th><?= T::l('Instructions') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->deliveryAddress as $i => $address) : ?>
                    <tr class="address-group">
                        <td>
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]name")->textInput()->label(false) ?>
                        </td>

                        <td>
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]address1")->textInput()->label(false) ?>
                        </td>

                        <td>
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]address2")->textInput()->label(false) ?>
                        </td>

                        <td>
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]country_id")->dropDownList(yii\helpers\ArrayHelper::map(\admin\common\ArrayHelper::translateList(common\models\Country::find()->all()), 'id', 'name_key'), ['prompt' => T::l('Choose country')])->label(false) ?>
                        </td>

                        <td>
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]city")->textInput()->label(false) ?>
                        </td>

                        <td>
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]postcode")->textInput()->label(false) ?>
                        </td>

                        <td>
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]instructions")->textInput()->label(false) ?>
                        </td>

                        <td>
                            <input type="button" value="<?= T::l('Remove') ?>" class="btn btn-success remove_row_btn" onclick="removeRow(this);">
                            <?= $form->field($model->deliveryAddress[$i], "[delivery][$i]id")->hiddenInput()->label(false) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?= $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJs("
    $(document).ready(function() {
        updateRemoveBtnVisiblity = function() {
            if ($('.address-group').length === 1) {
                $('.remove_row_btn').hide();
            } else {
                $('.remove_row_btn').show();
            }
        }

        removeRow = function(item) {
            if (jQuery('.specific_delivery table tbody tr').length > 1) {
                $(item).parents('tr').remove();
            }
            updateRemoveBtnVisiblity();
            return false;
        };

        checkSpecificAddressVisiblity = function() {
            jQuery('.row.specific_delivery').toggleClass('hidden', $('select[name=\"specific_delivery\"] option:selected').val() === 'No');
        };

        jQuery('select[name=\"specific_delivery\"]').on('change', function() {
            checkSpecificAddressVisiblity();
        });

        jQuery('.add-address-group').on('click', function() {
            var sample = jQuery('.specific_delivery table tbody').children('.address-group:first').clone()
              , count  = jQuery('.address-group').length;

            sample.find('input').each(function() {
                if ($(this).attr('type') !== 'button') {
                    var name = $(this).attr('name')
                      , id   = $(this).attr('id')
                      , pcls = $(this).parent().attr('class');
                    if (name) name = name.replace(/\[([0-9]+)\]/ig, '[' + count + ']');
                    if (id)   id   = id.replace(/\-([0-9]+)\-/ig, '-' + count + '-');
                    if (pcls) pcls = pcls.replace(/\-([0-9]+)\-/ig, '-' + count + '-');
                    $(this).attr('id', id).attr('name', name).val('');
                    $(this).parent().attr('class', pcls);
                }
            });

            sample.find('select').each(function() {
                var name = $(this).attr('name')
                  , id   = $(this).attr('id')
                  , pcls = $(this).parent().attr('class');
                name = name.replace(/\[([0-9]+)\]/ig, '[' + count + ']');
                id   = id.replace(/\-([0-9]+)\-/ig, '-' + count + '-');
                pcls = pcls.replace(/\-([0-9]+)\-/ig, '-' + count + '-');
                $(this).attr('id', id).attr('name', name).val('');
                $(this).parent().attr('class', pcls);
            });

            jQuery('.specific_delivery table tbody').append(sample);
            $('.remove_row_btn').show();

            $('#w0').yiiActiveForm('add', {
                'id'            : 'address-delivery-' + count + '-address1',
                'name'          : 'Address[delivery][' + count + '][address1]',
                'container'     : '.field-address-delivery-' + count + '-address1',
                'input'         : '#address-delivery-' + count + '-address1',
                'message'       : '.field-address-delivery-' + count + '-address1 .help-block',
                'validateOnType': true,
                'validate'      : function (attribute, value, messages, deferred) {
                    yii.validation.required(value, messages, { 'message':'REQUIRED'});
                }
            });

            $('#w0').yiiActiveForm('add', {
                'id'            : 'address-delivery-' + count + '-country_id',
                'name'          : 'Address[delivery][' + count + '][country_id]',
                'container'     : '.field-address-delivery-' + count + '-country_id',
                'input'         : '#address-delivery-' + count + '-country_id',
                'message'       : '.field-address-delivery-' + count + '-country_id .help-block',
                'validateOnType': true,
                'validate'      : function (attribute, value, messages, deferred) {
                    yii.validation.required(value, messages, { 'message':'REQUIRED'});
                }
            });

            $('#w0').yiiActiveForm('add', {
                'id'            : 'address-delivery-' + count + '-city',
                'name'          : 'Address[delivery][' + count + '][city]',
                'container'     : '.field-address-delivery-' + count + '-city',
                'input'         : '#address-delivery-' + count + '-city',
                'message'       : '.field-address-delivery-' + count + '-city .help-block',
                'validateOnType': true,
                'validate'      : function (attribute, value, messages, deferred) {
                    yii.validation.required(value, messages, { 'message':'REQUIRED'});
                }
            });

            $('#w0').yiiActiveForm('add', {
                'id'            : 'address-delivery-' + count + '-postcode',
                'name'          : 'Address[delivery][' + count + '][postcode]',
                'container'     : '.field-address-delivery-' + count + '-postcode',
                'input'         : '#address-delivery-' + count + '-postcode',
                'message'       : '.field-address-delivery-' + count + '-postcode .help-block',
                'validateOnType': true,
                'validate'      : function (attribute, value, messages, deferred) {
                    yii.validation.required(value, messages, { 'message':'REQUIRED'});
                }
            });
        });

        checkSpecificAddressVisiblity();
        updateRemoveBtnVisiblity();
        $('.timepicker').timepicker();
    });
"); ?>