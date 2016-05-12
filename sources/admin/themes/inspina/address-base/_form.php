<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Client;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AddressBase */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="address-base-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'delivery_delay_time')->
        textInput(['class' => 'time-picker',
                   'data-show24Hours' => 'true'
    ]) ?>

    <?= $form->field($model, 'postcode')->textInput() ?>

    <?= $form->field($model, 'latitude')->textInput() ?>

    <?= $form->field($model, 'longitude')->textInput() ?>

    <?= $form->field($model, 'max_delivery_distance')->textInput() ?>

    <?=  $this->render('../common/_record_type', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
