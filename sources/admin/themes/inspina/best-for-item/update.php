<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BestForItem */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create best for item') : Yii::t('label', 'Update') . ' ' .  Yii::$app->globalCache->getLabel($model->name_key);

$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Best For Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
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
