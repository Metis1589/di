<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PostcodeBlacklist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="postcode-blacklist-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'postcode_name')->textInput(['maxlength' => 20]) ?>

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
