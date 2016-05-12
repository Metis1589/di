<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->title = Yii::t('label', 'Create Voucher');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Vouchers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voucher-create">

    <?= $this->render('_form', [
        'model' => $model,
        'menu_category_selected' => $menu_category_selected,
        'menu_items_selected' => $menu_items_selected,
        'user_selected' => $user_selected,
    ]) ?>

</div>
