<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuAssignment */

$this->title = Yii::t('label', 'Update {modelClass}: ', [
    'modelClass' => 'Menu Assignment',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menu Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');
?>
<div class="menu-assignment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
