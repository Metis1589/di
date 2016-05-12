<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['create'], 'Create Language');
?>
<div class="language-index">

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
                'label' => Yii::t('label', 'Id'),
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('label', 'Name'),
                'options' => ['class' => 'col-xs-7']
            ],
            [
                'attribute' => 'iso_code',
                'label' => Yii::t('label', 'Iso Code'),
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'attribute' => 'record_type',
                'label' => Yii::t('label', 'Record Type'),
                'filter' => FilterHelper::recordTypeValues(),
                'value' => function($model) {
                    return FilterHelper::recordTypeValues()[$model->record_type];
                },
                'options' => ['class' => 'col-xs-2']
            ],
            [
                'class' => 'admin\common\CustomActionColumn',
                'options' => ['class' => 'col-xs-1'],
            ]
        ],
    ]); ?>

</div>
