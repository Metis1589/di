<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Cuisine */

$this->title = $model->isNewRecord ?  Yii::t('label', 'Create Cuisine')  :Yii::t('label', 'Update') . ' ' . Yii::$app->globalCache->getLabel($model->name_key);

$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Cuisines'), 'url' => ['index']];
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
