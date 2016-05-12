<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\forms\RestaurantContactForm */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="restaurant-contact-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_id')->dropDownList(common\models\Restaurant::getRestaurantsForSelect(), ['prompt' => Yii::t('label', 'Select...')]) ?>
    
    <?= $form->field($model, 'role')->dropDownList(common\enums\RestaurantContactRole::getLabels(), ['prompt' => Yii::t('label', 'Select...')]) ?>
    
    <?= $form->field($model, 'title')->dropDownList(common\enums\PersonTitle::getLabels(), ['prompt' => Yii::t('label', 'Select...')]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => 50]) ?>
    
    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => 50]) ?>
    
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => 50]) ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => 150]) ?>
    
    <?= $form->field($model, 'phone')->textInput(['maxlength' => 50]) ?>
    
    <?= $form->field($model, 'phone_type')->dropDownList(common\enums\PhoneType::getLabels(), ['prompt' => Yii::t('label', 'Select...')]) ?>
    
    <?= $form->field($model, 'is_opt_in')->checkbox(['class' => 'i-checks']) ?>

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
