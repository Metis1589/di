<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use common\models\Language;
use common\components\language\T;
use common\enums\RecordType;
use yii\helpers\ArrayHelper;
use common\enums\EmailType;

/* @var $this yii\web\View */
/* @var $model \common\models\Page */
/* @var $sourceModel \common\models\Page */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'language_id')->dropDownList(ArrayHelper::map(Yii::$app->globalCache->getLanguageList(true), 'id', 'name'), ['prompt' => T::l('Select ...')]) ?>
    
    <?= $form->field($model, 'email_type')->dropDownList(EmailType::getLabels(), ['prompt' => T::l('Select ...')]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'from_email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'from_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'cc')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'bcc')->textInput(['maxlength' => 255]) ?>
    
    <?php $model->content = \yii\helpers\Html::decode($model->content); ?>
    <?= $form->field($model, 'content',[
        'template'=>'<div class="row"><div class="col-sm-12">{label}</div><div class="col-sm-12">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
        ])->widget(\mihaildev\ckeditor\CKEditor::className(),[
                'model' => $model,
                'attribute' => 'content',
                'editorOptions' => ['allowedContent' => true] + \mihaildev\elfinder\ElFinder::ckeditorOptions('manager', [
                ])
        ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('label', 'Create') : Yii::t('label', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>    
    
    <?php ActiveForm::end(); ?>

</div>
