<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderRule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'custom_field_id')->dropDownList(\common\models\CustomField::getForDropDown(\common\enums\CustomFieldType::MenuItem), ['prompt' => '', 'class' => 'form-control']) ?>

    <?= $form->field($model, 'delivery_type')->dropDownList([ 'Delivery' => 'Delivery', 'Collection' => 'Collection', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'message_key')->textInput(['maxlength' => 255]) ?>

<?php  if ($model->isNewRecord): ?>

        <?= $form->field($model, 'record_type')->radioList([ 'Active' => Yii::t('label','Active'), 'Inactive' => Yii::t('label','Inactive'), ], ['prompt' => Yii::t('label','Select ...')]) ?>

<?php  endif; ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
