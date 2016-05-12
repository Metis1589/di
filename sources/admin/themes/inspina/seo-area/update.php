<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SeoArea */

$this->title = Yii::t('label', 'Update {modelClass}: ', [
    'modelClass' => 'Seo Area',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Seo Areas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');
?>
<div class="seo-area-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
