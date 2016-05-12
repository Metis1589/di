<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\CuisineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Cuisines');
$this->params['breadcrumbs'][] = $this->title;

$this->registerActionButton(['create'], 'Create Cuisine');
?>
<div class="cuisine-index">

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
                'attribute' => 'name_key',
                'options' => ['class' => 'col-xs-2'],
                'value' => function($model) {
                    return Yii::$app->globalCache->getLabel($model->name_key);
                },
                'label' => Yii::t('label', 'name'),
            ],
            [
                'attribute' => 'seo_name',
                'options' => ['class' => 'col-xs-2'],
                'value' => function($model) {
                    $descr = substr($model->seo_name,0,30);
                    $descr = (strlen($descr)>= 30) ? $descr . ' ...' : $descr;
                    return  $descr;
                },
            ],
            [
                'filter' => '',
                'attribute' => 'description_key',
                'options' => ['class' => 'col-xs-4'],
                'value' => function($model) {
                    $descr = substr(Yii::$app->globalCache->getLabel($model->description_key),0,40);
                    $descr = (strlen($descr)>= 40) ? $descr . ' ...' : $descr; 
                    return  $descr;
                },
                'label' => Yii::t('label', 'Description'),
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
