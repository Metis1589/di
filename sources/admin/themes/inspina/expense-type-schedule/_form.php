<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ExpenseTypeSchedule */
/* @var $form yii\widgets\ActiveForm */
$this->registerModelActionButtons($model);
?>

<div class="expense-type-schedule-form">

    <?php $form = ActiveForm::begin(); ?>
 
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'day')->dropDownList(common\enums\Day::getLabels(), ['prompt' => Yii::t('label','Select...')]) ?>
        </div>
    </div>    
    
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'schedule_id')->dropDownList(common\models\Schedule::getScheduleFromToForSelect(), ['prompt' => Yii::t('label','Select...')]) ?>
        </div>
    </div>
            
    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'expense_type_id')->dropDownList(common\models\ExpenseType::getExpenseTypesForSelect(), ['prompt' => Yii::t('label','Select...')]) ?>
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
