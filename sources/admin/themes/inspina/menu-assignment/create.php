<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MenuAssignment */

$this->title = Yii::t('label', 'Create {modelClass}', [
    'modelClass' => 'Menu Assignment',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menu Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-assignment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
