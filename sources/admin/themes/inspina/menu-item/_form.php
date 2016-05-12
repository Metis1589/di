<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\components\ImageHelper;
use \common\components\IOHelper;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'menu_category_id')->dropDownList(\common\models\MenuCategory::getMenuCategoriesByClientId(Yii::$app->request->getImpersonatedClientId()),['prompt' => Yii::t('label', 'Please select'), 'class' => 'form-control chosen-select'])->label(Yii::t('label', 'Category Reference Name')) ?>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'name_key']) ?>
    </div>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'description_key']) ?>
    </div>

    <?= $form->field($model, 'restaurant_price')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'web_price')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'cook_time')->textInput(['class' => 'time-picker', 'data-show24hours' => 'true', 'data-showseconds'=>'true', 'maxlength' => 10]) ?>

    <?= $form->field($model, 'nutritional')->textInput(['maxlength' => 500]) ?>
    
    <?= $form->field($model, 'vat_id')->dropDownList(\common\models\Vat::getVATForSelect(),['prompt' => Yii::t('label', 'Please select')]) ?>
    


    <?= $form->field($model, 'is_imported')->checkbox(['class' => 'i-checks']) ?>
    
    <?php  if ($model->isNewRecord || empty($model->image_file_name)) : ?>

        <?= $form->field($model, 'image_file_name')->widget(FileInput::classname(), [
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'png', 'jpeg'],  'showUpload' => false, 'showRemove' => false]]) ?>
    
    <?php else: ?>
    
        <?= $form->field($model, 'image_file_name')->widget(FileInput::classname(), [
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png'],  'showUpload' => false, 'showRemove' => false,
        'initialPreview'=>[
            Html::img(Yii::$app->params['images_base_url'] . ImageHelper::getThumbFilename(IOHelper::getMenuItemImagesPath().$model->image_file_name), ['height' => 200]),
        ], 
        'overwriteInitial'=>false]]) ?>
    <?php  endif; ?>
    
    <div class="form-group field-menuitem-file has-error">
        <?php echo Html::error($model, 'file', ['class' => 'help-block']) ?>
    </div>
    
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
