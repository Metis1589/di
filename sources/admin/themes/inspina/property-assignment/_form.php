<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PropertyAssignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-assignment-form">

    <?php $form = ActiveForm::begin(['action' => \yii\helpers\Url::toRoute(['property-assignment/update', 'id' => $id])]); ?>

    <?php echo Html::hiddenInput('modelClass', $modelClass);?>
    <?= $form->field($model, 'max_delivery_order_value')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'min_delivery_order_value')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'max_delivery_order_amount')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'min_delivery_order_amount')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'max_collection_order_value')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'min_collection_order_value')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'max_collection_order_amount')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'min_collection_order_amount')->textInput(['maxlength' => 10]) ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
