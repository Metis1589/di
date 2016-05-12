<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = Yii::t('label', 'Create Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
