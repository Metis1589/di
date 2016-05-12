<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\RestaurantBestForItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Restaurant Best For Items');
if (isset($restaurant)) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurants'), 'url' => ['/restaurant/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant'). ' ' .$restaurant->name, 'url' => ['/restaurant/update', 'id' => $restaurant->id]];
}
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['create', 'restaurant_id' => isset($restaurant) ? $restaurant->id : null], 'Assign Best For');
?>
<div class="restaurant-best-for-item-index">

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
                'attribute' => 'restaurant_id',
                'options' => ['class' => 'col-xs-1'],
                'filter' => common\models\Restaurant::getRestaurantsForSelect(),
                'label' => Yii::t('label', 'restaurant_id'),
                'value' => 'restaurant.name'
            ],
            [
                'attribute' => 'best_for_item_id',
                'filter' => common\models\BestForItem::getBestForItemsForSelect(),
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'best_for_item_id'),
                'value' => function($model) {
                    return Yii::$app->globalCache->getLabel($model->bestForItem->name_key);
                },
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
             'class' => 'admin\common\CustomActionColumn',
             'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]); ?>

</div>
