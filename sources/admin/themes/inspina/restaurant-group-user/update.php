<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantGroupUser */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create Restaurant Group User') : Yii::t('label', 'Update Restaurant Group User');

if (isset($restaurantGroup)) {
    $this->title .= ' ' . Yii::$app->globalCache->getLabel($restaurantGroup->name_key);
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Groups'), 'url' => ['/restaurant-group/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Group'). ' ' . Yii::$app->globalCache->getLabel($restaurantGroup->name_key), 'url' => ['/restaurant-group/update', 'id' => $restaurantGroup->id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Group Users'), 'url' => ['index',  'restaurant_group_id' => $restaurantGroup->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Group Users'), 'url' => ['index']];
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
