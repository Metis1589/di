<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Allergy */

$this->title = Yii::t('label', 'Update Allergy ') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Allergies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');

\admin\assets\AngularAsset::register($this, ['translation']);

?>
<div class="allergy-update" ng-app="dineinApp">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
