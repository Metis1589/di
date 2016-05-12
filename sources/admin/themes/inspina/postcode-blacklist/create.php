<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PostcodeBlacklist */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Postcode Blacklist',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Postcode Blacklists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="postcode-blacklist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
