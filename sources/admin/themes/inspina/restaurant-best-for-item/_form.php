<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBestForItem */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="restaurant-best-for-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_id')->dropDownList(common\models\Restaurant::getRestaurantsForSelect(),['prompt' => Yii::t('label','Select...')]) ?>

    <?= $form->field($model, 'best_for_item_id')->dropDownList(common\models\BestForItem::getBestForItemsForSelect(),['prompt' => Yii::t('label','Select...')]) ?>

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
