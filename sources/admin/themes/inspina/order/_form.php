<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'order_number')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'postcode')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'delivery_type')->dropDownList([ 'DeliveryAsap' => 'DeliveryAsap', 'DeliveryLater' => 'DeliveryLater', 'CollectionAsap' => 'CollectionAsap', 'CollectionLater' => 'CollectionLater', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'later_date')->textInput() ?>

    <?= $form->field($model, 'member_comment')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'reataurant_comment')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'delivery_address_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'billing_address_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_utensils')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'InProgress' => 'InProgress', 'FoodRecieved' => 'FoodRecieved', 'FoodPrepare' => 'FoodPrepare', 'Cancel' => 'Cancel', 'EstDelivery' => 'EstDelivery', 'FoodReady' => 'FoodReady', 'TransToReset' => 'TransToReset', 'DeliveryAccept' => 'DeliveryAccept', 'ReadyBy' => 'ReadyBy', 'ArrivedAtCustomer' => 'ArrivedAtCustomer', 'DriverAssigned' => 'DriverAssigned', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'is_amend')->textInput() ?>

    <?= $form->field($model, 'is_term_cond')->textInput() ?>

    <?= $form->field($model, 'is_term_cond_acc_pol')->textInput() ?>

    <?= $form->field($model, 'is_subsribe_own')->textInput() ?>

    <?= $form->field($model, 'is_subsribe_other')->textInput() ?>

    <?= $form->field($model, 'is_in_dispatch')->textInput() ?>

    <?= $form->field($model, 'voucher_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'currency_code')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'delivery_charge')->textInput() ?>

    <?= $form->field($model, 'driver_charge')->textInput() ?>

    <?= $form->field($model, 'subtotal')->textInput() ?>

    <?= $form->field($model, 'discount_value')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'refund_amount')->textInput() ?>

    <?= $form->field($model, 'restaurant_subtotal')->textInput() ?>

    <?= $form->field($model, 'restaurant_discount_value')->textInput() ?>

    <?= $form->field($model, 'restaurant_total')->textInput() ?>

    <?= $form->field($model, 'restaurant_refund_amount')->textInput() ?>

    <?= $form->field($model, 'payment_charge')->textInput() ?>

    <?= $form->field($model, 'estimated_time')->textInput() ?>

<?php  if ($model->isNewRecord): ?>

        <?= $form->field($model, 'record_type')->radioList([ 'Active' => Yii::t('label','Active'), 'Inactive' => Yii::t('label','Inactive'), ], ['prompt' => Yii::t('label','Select ...')]) ?>

<?php  endif; ?>

    <?=  $this->render('../common/_recordInfo', [
        'model' => $model,
    ]) ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('label', 'Create') : Yii::t('label', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= $model->isNewRecord ? '' : Html::a($model->record_type == 'Active' ? Yii::t('label','Deactivate') : Yii::t('label','Activate'), [$model->record_type == 'Active' ? 'deactivate' : 'activate', 'id' => $model->id], [
            'class' => $model->record_type == 'Active'? 'btn btn-danger ' : 'btn btn-success ',
        ]) ?>
    <?= $model->isNewRecord ? '' : Html::a(Yii::t('label','Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger right ',
            'data' => [
                'confirm' => Yii::t('label','Are you sure you want to delete?'),
                'method' => 'post',
            ],
        ]) ?>    
    </div>

    <?php ActiveForm::end(); ?>

</div>
