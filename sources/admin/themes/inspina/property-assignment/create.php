<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PropertyAssignment */

$this->title = Yii::t('label', 'Create {modelClass}', [
    'modelClass' => 'Property Assignment',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Property Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-assignment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
