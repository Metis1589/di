<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = Yii::t('label', 'Update {modelClass}: ', [
    'modelClass' => 'Order',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
