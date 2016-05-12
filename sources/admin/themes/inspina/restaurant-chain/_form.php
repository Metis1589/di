<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Client;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantChain */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="restaurant-chain-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'name_key']) ?>
    </div>

    <?=  $this->render('../common/_record_type', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
