<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CompanyUserGroupUser */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="company-user-group-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList(\common\models\User::getUsersForSelect(),['prompt' => Yii::t('label', 'Select...')]) ?>

    <?= $form->field($model, 'company_user_group_id')->dropDownList(\common\models\CompanyUserGroup::getGroupsForSelect(), ['prompt' => Yii::t('label', 'Select...')]) ?>

    <?=  $this->render('../common/_record_type', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
