<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Country */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="country-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'name_key']) ?>
    </div>

    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'native_name')->textInput(['maxlength' => 50]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'iso_code')->textInput(['maxlength' => 50]) ?>
        </div>
    </div>

    <?=  $this->render('../common/_record_is_default', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_type', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>