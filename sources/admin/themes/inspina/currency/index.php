<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Currencies');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['/currency/create'], 'Create Currency');

?>
<div class="currency-index">

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
                'options' => ['class' => 'col-xs-2'],
                'value' => function($model) { return admin\common\StringHelper::SubstrForTable($model->name, 40);},
            ],
            [
                'attribute' => 'code',
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) { return admin\common\StringHelper::SubstrForTable($model->code, 20);},
            ],
//            [
//                'attribute' => 'is_default',
//                'options' => ['class' => 'col-xs-1']
//            ],
            [
                'attribute' => 'is_default',
                'filter' => false,
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) {return $model->is_default ? 'yes':'no';},
            ],
            [
                'attribute' => 'record_type',
                'filter' => FilterHelper::recordTypeValues(),
                'value' => function($model) {
                    return FilterHelper::recordTypeValues()[$model->record_type];
                },
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'class' => 'admin\common\CustomActionColumn',
                'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]); ?>

</div>
