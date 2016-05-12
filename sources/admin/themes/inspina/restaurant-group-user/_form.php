<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantGroupUser */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="restaurant-group-user-form">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->field($model, 'user_id')->dropDownList(common\models\User::getGroupAdminForSelect(),['prompt' => Yii::t('label','Select...')]) ?>

    <?= $form->field($model, 'restaurant_group_id')->dropDownList(common\models\RestaurantGroup::getRestaurantGroupsForSelect(),['prompt' => Yii::t('label','Select...')]) ?>


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
