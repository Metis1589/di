<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Country */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create Country') : Yii::t('label', 'Update Country') . ' ' . Yii::$app->globalCache->getLabel($model->name_key);
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\admin\assets\AngularAsset::register($this, ['translation']);
?>
<div class="row" ng-app="dineinApp">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
