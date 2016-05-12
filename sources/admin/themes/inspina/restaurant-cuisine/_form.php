<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Restaurant;
use common\models\Cuisine;


/* @var $this yii\web\View */
/* @var $model common\models\RestaurantCuisine */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="restaurant-cuisine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_id')->dropDownList(Restaurant::getRestaurantsForSelect(),['prompt' => Yii::t('label','Select...')]) ?>

    <?= $form->field($model, 'cuisine_id')->dropDownList(Cuisine::getCuisinesForSelect(),['prompt' => Yii::t('label','Select...')]) ?>

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
