<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExpenseTypeSchedule */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create Expense Type Schedule') : Yii::t('label', 'Update Expense Type Schedule') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Expense Type Schedules'), 'url' => ['index']];
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
