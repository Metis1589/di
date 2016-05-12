<?php

use yii\helpers\Html;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = Yii::t('label', 'Loyalty Points ');
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');
?>
<div class="loyalty-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

