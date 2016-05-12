<?php

use common\components\language\T;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\enums\UserType;

/* @var $this yii\web\View */
/* @var $model common\models\Client */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'contact_email')->textInput(['maxlength' => 100]) ?>

    <h2><?= T::l('MailChimp Configuration') ?></h2>

    <?= $form->field($model, 'mc_host')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'mc_api_key')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'mc_default_city_list_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'mc_default_restaurant_list_name')->textInput(['maxlength' => 255]) ?>

    <h2><?= T::l('Adyen Configuration') ?></h2>

    <?= $form->field($model, 'payment_merchant_account')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'payment_skin_code')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'payment_hmac_key')->textInput(['maxlength' => 255]) ?>

    <h2><?=T::l('Eagle Eye Configuration')?></h2>

    <?= $form->field($model, 'eagle_eye_username')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'eagle_eye_password')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'eagle_eye_endpoint')->textInput(['maxlength' => 100]) ?>

    <h2><?=T::l('InnTouch Configuration')?></h2>

    <?= $form->field($model, 'has_inntouch')->checkbox(['class' => 'i-checks']) ?>

    <?php if (in_array(Yii::$app->user->identity->user_type, [UserType::Admin])): ?>
        <h2><?=T::l('Corporate Accounts Configuration')?></h2>
        <?= $form->field($model, 'is_corporate_accounts_enabled')->checkbox(['class' => 'i-checks']) ?>
    <?php endif; ?>

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
