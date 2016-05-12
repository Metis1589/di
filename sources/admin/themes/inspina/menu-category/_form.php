<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\components\ImageHelper;
use \common\components\IOHelper;

/* @var $this yii\web\View */
/* @var $model common\models\MenuCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'menu_id')->dropDownList(\common\models\Menu::getMenuForSelect(),['prompt' => Yii::t('label', 'Please select'), 'class' => 'form-control chosen-select'])->label(Yii::t('label', 'Menu Reference Name')) ?>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'name_key']) ?>
    </div>

    <?= $form->field($model, 'reference_name')->textInput(['maxlength' => 250]) ?>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'description_key']) ?>
    </div>

    <?= $form->field($model, 'sort_order')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'image_file_name')->fileInput(['maxlength' => 255]) ?>
    
    <?= is_null($model->image_file_name) ? '' : Html::img(Yii::$app->params['images_base_url'] . ImageHelper::getThumbFilename(IOHelper::getMenuCategoryImagesPath().$model->image_file_name), ['height' => '100']) ?>

    <br/>
    <br/>

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
