<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\RestaurantGroupUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Restaurant Group Users');
if (isset($restaurantGroup)) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Groups'), 'url' => ['/restaurant-group/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Group'). ' ' .Yii::$app->globalCache->getLabel($restaurantGroup->name_key), 'url' => ['/restaurant-group/update', 'id' => $restaurantGroup->id]];
}

$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton( ['create', 'restaurant_group_id' => isset($restaurantGroup) ? $restaurantGroup->id : null ], 'Assign Restaurant Group User');
?>
<div class="restaurant-group-user-index">

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
                'attribute' => 'user_id',
                'filter' => common\models\User::getGroupAdminForSelect(),
                'options' => ['class' => 'col-xs-1'],
                'value' =>  function($model) {
                    return $model->getUser()->one()->username;
                }
            ],
            [
                'attribute' => 'restaurant_group_id',
                'filter' => common\models\RestaurantGroup::getRestaurantGroupsForSelect(),
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) {
                    return Yii::$app->globalCache->getLabel( $model->getRestaurantGroup()->one()->name_key );
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
