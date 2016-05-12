<?php

use admin\common\FilterHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\SeoAreaController */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Seo Areas');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['create'], 'Create Seo Area');
?>
<div class="seo-area-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
        'options' => ['id' => 'bases-pjax'],
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'attribute' => 'name',
                'options' => ['class' => 'col-xs-3'],
                'value' => function($model) {
                    $descr = substr(Yii::$app->globalCache->getLabel($model->name),0,30);
                    $descr = (strlen($descr)>= 30) ? $descr . ' ...' : $descr;
                    return  $descr;
                },
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
                'attribute' => 'description',
                'options' => ['class' => 'col-xs-4'],
                'value' => function($model) {
                    $descr = substr($model->description,0,30);
                    $descr = (strlen($model->description)>= 30) ? $descr . ' ...' : $descr;
                    return  $descr;
                },
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
