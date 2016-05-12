<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Label */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="label-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 190, 'disabled' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 250]) ?>

    <?=  $this->render('../common/_record_type', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <div class="hr-line-dashed"></div>

    <h3><?= Yii::t('label', 'Translations') ?></h3>

    <?php
    foreach ($languages as $i => $language) {
        if (count($language->labelLanguages) > 0) {
            echo $form->field($language->labelLanguages[0], "[$i]value")->textarea()->label($language->name);
        }
    } ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

<?php ActiveForm::end(); ?>

</div>
