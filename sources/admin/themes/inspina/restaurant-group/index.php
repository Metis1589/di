<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;
use common\models\RestaurantChain;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\RestaurantGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Restaurant Groups');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton( ['create'], 'Create Restaurant Group');
?>
<div class="restaurant-group-index">

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
                'attribute' => 'restaurant_chain_id',
                'filter' =>  RestaurantChain::getChainsForSelect(), 
                'options' => ['class' => 'col-xs-3'],
                'label' => Yii::t('label','Restaurant Chain Name'),
                'value' => 'chain_name'
            ],
            [
                'attribute' => 'currency_id',
                'filter' => yii\helpers\ArrayHelper::map(\common\models\Currency::findAll(['record_type' => 'Active']), 'id', 'code'),
                'value' => function($model) {
                    return $model->getCurrency()->one()->code;
                },
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
