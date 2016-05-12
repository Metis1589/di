<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);
?>

<div class="currency-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 150]) ?>
        </div>
    </div>        

    <?= $form->field($model, 'code')->textInput(['maxlength' => 150]) ?>
    
    <?= $form->field($model, 'symbol')->textInput(['maxlength' => 1]) ?>

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
