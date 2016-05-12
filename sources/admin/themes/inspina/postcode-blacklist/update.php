<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PostcodeBlacklist */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Postcode Blacklist',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Postcode Blacklists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="postcode-blacklist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
