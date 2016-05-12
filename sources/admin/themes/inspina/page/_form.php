<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use common\models\Language;
use common\components\language\T;
use common\enums\RecordType;
use yii\helpers\ArrayHelper;
use common\enums\UserType;

/* @var $this yii\web\View */
/* @var $model \common\models\Page */
/* @var $sourceModel \common\models\Page */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 150, 'placeholder' => isset($sourceModel) ? $sourceModel->title : null]) ?>

    <?= $form->field($model, 'language_id')->dropDownList(ArrayHelper::map(Yii::$app->globalCache->getLanguageList(true), 'id', 'name'), ['prompt' => T::l('Select ...')]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 100]) ?>
    
    <?= $form->field($model, 'robots')->dropDownList([
        'index, follow' => 'Index, Follow',
        'index, nofollow' => 'Index, No Follow',
        'noindex, follow' => 'No Index, Follow',
        'noindex, nofollow' => 'No Index, No Follow'
    ], [
        'prompt' => Yii::t('label','Select ...')
    ]) ?>
    
    <?php if($model->open_from){ $model->open_from = Yii::$app->formatter->asDate($model->open_from,'php:Y-m-d H:i'); } ?>
    <?= $form->field($model, 'open_from')->textInput(['class' => 'form-control datetime-jui-picker']) ?>  
    
    <?php if($model->open_to){ $model->open_to = Yii::$app->formatter->asDate($model->open_to,'php:Y-m-d H:i'); } ?>
    <?= $form->field($model, 'open_to')->textInput(['class' => 'form-control datetime-jui-picker']) ?>
    
    <?php $model->content = \yii\helpers\Html::decode($model->content); ?>
    <?= $form->field($model, 'content',[
        'template'=>'<div class="row"><div class="col-sm-12">{label}</div><div class="col-sm-12">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
        ])->widget(\mihaildev\ckeditor\CKEditor::className(),[
                'model' => $model,
                'attribute' => 'content',
                'editorOptions' => ['allowedContent' => true] + \mihaildev\elfinder\ElFinder::ckeditorOptions('manager', [
                ])
        ]) ?>

    <?= $form->field($model, 'description')->textArea(['maxlength' => 150]) ?>

    <?php  if ($model->isNewRecord): ?>
        <?= $form->field($model, 'record_type')->radioList([ 'Active' => Yii::t('label','Active'), 'Inactive' => Yii::t('label','Inactive'), ], ['prompt' => Yii::t('label','Select ...')]) ?>
    <?php  endif; ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('label', 'Create') : Yii::t('label', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
