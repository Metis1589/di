<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CustomField */

$this->title = 'Create Custom Field';
$this->params['breadcrumbs'][] = ['label' => 'Custom Fields', 'url' => ['index', 'type' => $type]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-field-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
