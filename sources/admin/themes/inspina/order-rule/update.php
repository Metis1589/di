<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderRule */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Order Rule',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="order-rule-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
