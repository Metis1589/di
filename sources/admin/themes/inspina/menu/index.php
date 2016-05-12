<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Menus');
$this->params['breadcrumbs'][] = $this->title;

$this->registerActionButton(['create'], 'Create Menu');
?>
<div class="menu-index">

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
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'id'),
            ],
            [
                'attribute' => 'name_key',
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) {
                    $name = substr($model->name_key, 0, 40);
                    $name = (strlen($name)>= 40) ? $name . ' ...' : $name;
                    return  $name;
                },
            ],
            [
                'attribute' => 'reference_name',
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) {
                    $reference_name = substr($model->reference_name, 0, 40);
                    $reference_name = (strlen($reference_name)>= 40) ? $reference_name . ' ...' : $reference_name;
                    return  $reference_name;
                },
            ],
            [
                'attribute' => 'from',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'from'),
            ],
            [
                'attribute' => 'to',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'to'),
            ],
            [
                'attribute' => 'record_type',
                'label' => Yii::t('label', 'record_type'),
                'filter' => FilterHelper::recordTypeValues(),
                'value' => function($model) {
                    return FilterHelper::recordTypeValues()[$model->record_type];
                },
                'options' => ['class' => 'col-xs-2']
            ],
            [
             'class' => 'admin\common\MenuActionColumn',
             'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]); ?>

</div>
