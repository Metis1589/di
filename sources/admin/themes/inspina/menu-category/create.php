<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MenuCategory */

$this->title = Yii::t('label', 'Create Menu Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menu Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
