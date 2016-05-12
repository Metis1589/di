<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */

$this->title = Yii::t('label', 'Create Menu Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Create Menu Item');
?>
<div class="menu-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
