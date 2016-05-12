<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\CustomFieldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Custom Fields';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-field-index">

    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Create Custom Field', ['create', 'type' => $type], ['class' => 'btn btn-success right']) ?>
	</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


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

//            [
//                'attribute' => 'id',
//                'options' => ['class' => 'col-xs-1'],
//                'label' => Yii::t('label', 'id'),
//            ],
            [
                'attribute' => 'key',
                'options' => ['class' => 'col-xs-4'],
                'label' => Yii::t('label', 'key'),
            ],
            [
                'attribute' => 'default_value',
                'options' => ['class' => 'col-xs-4'],
                'label' => Yii::t('label', 'default_value'),
            ],
            [
                'attribute' => 'value_type',
                'options' => ['class' => 'col-xs-2'],
                'label' => Yii::t('label', 'value_type'),
            ],
//            [
//                'attribute' => 'value_type',
//                'options' => ['class' => 'col-xs-1'],
//                'label' => Yii::t('label', 'value_type'),
//            ],
//            [
//                'attribute' => 'type',
//                'options' => ['class' => 'col-xs-1'],
//                'label' => Yii::t('label', 'type'),
//            ],
            [
                'attribute' => 'record_type',
                'label' => Yii::t('label', 'record_type'),
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
