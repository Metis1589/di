<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuCategory */

$this->title = Yii::t('label', 'Update Menu Category') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menu Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');

\admin\assets\AngularAsset::register($this, ['translation']);
?>
<div class="menu-category-update" ng-app="dineinApp">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
