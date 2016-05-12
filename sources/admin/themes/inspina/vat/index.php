<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\VatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Vats');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['/vat/create'], 'Create VAT');

?>
<div class="vat-index">

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
                'attribute' => 'type',
                'options' => ['class' => 'col-xs-1'],
                'filter' => common\enums\VatType::getLabels(),
            ],
            [
                'attribute' => 'value',
                'options' => ['class' => 'col-xs-1']
            ],
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
