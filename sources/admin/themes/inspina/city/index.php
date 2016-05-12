<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;
use common\models\City;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Cities');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['/city/create'], 'Create City');
?>
<div class="city-index">


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
                    return Yii::$app->globalCache->getLabel($model->name_key);
                },
                'label' => Yii::t('label', 'name'),
            ],
            [
                'attribute' => 'native_name',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'native_name'),
            ],
            [
                'attribute' => 'country_name',
                'filter' =>  City::getCountriesForSelect(),
                'value' => function($model) {
                    return Yii::$app->globalCache->getLabel($model->country_name);
                },
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
