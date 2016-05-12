<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomField */

$this->title = 'Update Custom Field: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Custom Fields', 'url' => ['index', 'type' => $type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="custom-field-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
