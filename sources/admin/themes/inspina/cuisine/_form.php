<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Client;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Cuisine */
/* @var $form yii\widgets\ActiveForm <?= $form->field($model, 'id')->textInput(['maxlength' => 11]) ?> */

$this->registerModelActionButtons($model);

?>

<div class="cuisine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'seo_name')->textInput(['maxlength' => 255]) ?>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'name_key']) ?>
    </div>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'description_key']) ?>
    </div>

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
