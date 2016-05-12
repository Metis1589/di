<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\RestaurantContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Restaurant Contacts');
if (isset($restaurant)) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurants'), 'url' => ['/restaurant/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant'). ' ' .$restaurant->name, 'url' => ['/restaurant/update', 'id' => $restaurant->id]];
}
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton( ['create', 'restaurant_id' => isset($restaurant) ? $restaurant->id : null ], 'Add Contact');
?>
<div class="restaurant-contact-index">

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
                'attribute' => 'restaurant_id',
                'options' => ['class' => 'col-xs-5'],
                'filter' => common\models\Restaurant::getRestaurantsForSelect(),
                'value' => 'restaurant.name'
            ],
//            [
//                'attribute' => 'contact_id',
//                'options' => ['class' => 'col-xs-1']
//            ],
            [
                'attribute' => 'role',
                'options' => ['class' => 'col-xs-4'],
                'filter' => common\enums\RestaurantContactRole::getLabels(),
                'value' => function($model) {
                    return common\enums\RestaurantContactRole::getLabels()[$model->role];
                }

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
            ]
        ],
    ]); ?>

</div>
