<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CustomField */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="custom-field-form">

    <?php $form = ActiveForm::begin(['action' => \yii\helpers\Url::toRoute(['custom-field/update-multiple', 'id' => $id])]); ?>

    <?= Html::hiddenInput('modelClass', $model);?>

    <?php foreach ($fields as $key => $field) {

        switch ($field->value_type) {
            case \common\enums\CustomFieldValueType::String:
                echo '<label>' . Html::encode($key) . '</label>';
                echo $form->field($field->customFieldValue, "[$key]value")->textInput()->label(false);
                break;
            case \common\enums\CustomFieldValueType::Bool:
                echo $form->field($field->customFieldValue, "[$key]value")->checkbox([], null)->label(Html::encode($key));
                break;
        }
    } ?>

    <?= Html::submitButton(Yii::t('label', 'Update'), ['class' => 'btn btn-primary col-xs-12']) ?>

    <?php ActiveForm::end(); ?>

</div>
