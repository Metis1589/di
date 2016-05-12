<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use admin\common\StringHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['create'], 'Create Company');
?>
<div class="company-index">

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
                'attribute' => 'name',
                'options' => ['class' => 'col-xs-3'],
                'value' => function($model) {return StringHelper::SubstrForTable($model->name, 30);},
            ],
            [
                'attribute' => 'code',
                'options' => ['class' => 'col-xs-2']
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
                'options' => ['class' => 'col-xs-2'],
            ]
        ],
    ]); ?>

</div>
