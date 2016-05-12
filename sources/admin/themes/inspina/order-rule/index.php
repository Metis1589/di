<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\OrderRuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Order Rules');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-rule-index">

    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Order Rule',
]), ['create'], ['class' => 'btn btn-success right']) ?>
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
            [
                'attribute' => 'delivery_type',
                'options' => ['class' => 'col-xs-4'],
                'label' => Yii::t('label', 'delivery_type'),
            ],
            [
                'attribute' => 'customField.key',
                'options' => ['class' => 'col-xs-3'],
                'label' => Yii::t('label', 'Custom Field'),
            ],

            [
                'attribute' => 'value',
                'options' => ['class' => 'col-xs-3'],
                'label' => Yii::t('label', 'value'),
            ],
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
