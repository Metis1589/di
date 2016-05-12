<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="loyalty-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'loyalty_points_per_currency')->textInput(['maxlength' => 5]) ?>

    <?= $form->field($model, 'loyalty_points_per_voucher')->textInput(['maxlength' => 5]) ?>

    <?= $form->field($model, 'voucher_id')->dropDownList(\common\models\Voucher::getVouchersByClientForSelect()) ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('label', 'Create') : Yii::t('label', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
