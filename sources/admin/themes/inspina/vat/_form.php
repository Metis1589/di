<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vat */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="vat-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-10">
             <?= $form->field($model, 'type')->dropDownList(common\enums\VatType::getLabels(), ['prompt' => '']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-10">
             <?= $form->field($model, 'value')->textInput(['maxlength' => 50]) ?>
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
