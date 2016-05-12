<?php

use yii\helpers\Html;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = Yii::t('label', 'Update Menu: ') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');

\admin\assets\AngularAsset::register($this, ['translation']);

?>
<div class="menu-update" ng-app="dineinApp">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

