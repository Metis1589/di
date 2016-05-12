<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\forms\RestaurantContactForm */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create Restaurant Contact') : Yii::t('label', 'Update Restaurant Contact');
if (isset($restaurant)) {
    $this->title .= ' ' .  $restaurant->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurants'), 'url' => ['/restaurant/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant'). ' ' .$restaurant->name, 'url' => ['/restaurant/update', 'id' => $restaurant->id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Contacts'), 'url' => ['index',  'restaurant_id' => $restaurant->id]];
} else {
    $this->title = ($model->isNewRecord ? Yii::t('label', 'Create Restaurant Contact') : Yii::t('label', 'Update Restaurant Contact'));
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Contacts'), 'url' => ['index']];
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

