<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CustomField */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="custom-field-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'default_value')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'value_type')->dropDownList(\common\enums\CustomFieldValueType::getLabels()) ?>

    <?php  if ($model->isNewRecord): ?>

        <?= $form->field($model, 'record_type')->radioList([ 'Active' => Yii::t('label','Active'), 'Inactive' => Yii::t('label','Inactive'), ], ['prompt' => Yii::t('label','Select ...')]) ?>

<?php  endif; ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
