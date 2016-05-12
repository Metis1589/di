<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\ExpenseTypeScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Expense Type Schedules');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['/expense-type-schedule/create'], 'Create Expense Type Schedule');
?>
<div class="expense-type-schedule-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'rowOptions' => function ($model, $index, $widget, $grid){
            if ($model->record_type == 'Inactive') {
                return ['class' => 'danger'];
            } else {
                return [];
            }
        },
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'attribute' => 'day',
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'attribute' => 'schedule_id',
                'options' => ['class' => 'col-xs-1'],
                'filter' => common\models\Schedule::getScheduleFromToForSelect(),               
                'value' => function($model) {
                    return $model->getSchedule()->one()->from . ' - ' .
                           $model->getSchedule()->one()->to;
                },
                'label' => Yii::t('label', 'Schedule'),
            ],
            [
                'attribute' => 'expense_type_id',
                'options' => ['class' => 'col-xs-1'],
                'filter' => common\models\ExpenseType::getExpenseTypesForSelect(),               
                'value' => function($model) {
                    return $model->getExpenseType()->one()->name;
                },
            ],
            [
                'attribute' => 'record_type',
                'filter' => FilterHelper::recordTypeValues(),
                'value' => function($model) {
                    return FilterHelper::recordTypeValues()[$model->record_type];
                },
                'options' => ['class' => 'col-xs-2']
            ],
            [
                'class' => 'admin\common\CustomActionColumn',
                'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]); ?>

</div>
