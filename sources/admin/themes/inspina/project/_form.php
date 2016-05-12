<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $form yii\widgets\ActiveForm */
$this->registerModelActionButtons($model);
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
            
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'daily_limit')->textInput(['maxlength' => 10]) ?>
        </div>
    </div>            
    
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'weekly_limit')->textInput(['maxlength' => 10]) ?>
        </div>
    </div>
            
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'monthly_limit')->textInput(['maxlength' => 10]) ?>
        </div>
    </div>
            
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'limit_type')->dropDownList(common\enums\ProjectLimitType::getLabels(), ['prompt' => Yii::t('label','Select...')]) ?>
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'company_id')->dropDownList(common\models\Company::getCompaniesForSelect(), ['prompt' => Yii::t('label','Select...')]) ?>
        </div>
    </div>  
            
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'user_id')->dropDownList(common\models\User::getUsersForSelect(), ['prompt' => Yii::t('label','Select...')]) ?>
        </div>
    </div>
    
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
