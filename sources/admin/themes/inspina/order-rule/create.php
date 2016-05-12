<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderRule */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Order Rule',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-rule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
