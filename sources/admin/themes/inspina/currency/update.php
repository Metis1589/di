<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create currency') : Yii::t('label', 'Update currency') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Currencies'), 'url' => ['index']];
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
