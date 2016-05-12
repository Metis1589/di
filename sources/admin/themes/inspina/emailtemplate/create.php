<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Emailtemplate */

$this->title = Yii::t('label','Create email');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label','Emails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>