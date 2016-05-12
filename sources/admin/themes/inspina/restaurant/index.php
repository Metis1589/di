<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\RestaurantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Restaurants');
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->request->isImpersonated()) {
    $this->registerActionButton(['create'], 'Create Restaurant');
}

?>
<div class="restaurant-index">
    <div class="row">
        <div class="col-xs-3">
            <?=  $this->action('restaurant-group/get-tree-view') ?>
        </div>

        <div class="col-xs-9">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'pjax' => true,
                'pjaxSettings' => [
                    'neverTimeout' => true,
                    'options' => ['id' => 'restaurant-table']
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
                        'attribute' => 'name',
                        'options' => ['class' => 'col-xs-2'],
                        'label' => Yii::t('label', 'name'),
                    ],
                    [
                        'attribute' => 'address_base_id',
                        'value' => 'addressBase.name',
                        'filter' => \common\models\AddressBase::getAddressBaseForSelect(),
                        'options' => ['class' => 'col-xs-1'],
                        'label' => Yii::t('label', 'address_base_id'),
                    ],
                    [
                        'attribute' => 'city',
                        'value' => 'physicalAddress.city',
                        'options' => ['class' => 'col-xs-1'],
                        'label' => Yii::t('label', 'City'),
                    ],
                    [
                        'attribute' => 'postcode',
                        'value' => 'physicalAddress.postcode',
                        'options' => ['class' => 'col-xs-1'],
                        'label' => Yii::t('label', 'Postcode'),
                    ],
                    [
                        'attribute' => 'phone',
                        'value' => 'contact.number',
                        'options' => ['class' => 'col-xs-1'],
                        'label' => Yii::t('label', 'Phone'),
                    ],
                    [
                        'value' => function($model) {
                            return 'TODO';
                        },
                        'options' => ['class' => 'col-xs-1'],
                        'label' => Yii::t('label', 'Heartbeat of App'),
                    ],
                    [
                        'attribute' => 'email',
                        'value' => function($model) {
                            return $model->contact->emails[0]->email;
                        },
                        'options' => ['class' => 'col-xs-1'],
                        'label' => Yii::t('label', 'Email'),
                    ],
                    [
                        'value' => function($model) {
                            return 'TODO';
                        },
                        'options' => ['class' => 'col-xs-1'],
                        'label' => Yii::t('label', 'Last Order Time'),
                    ],
        //            [
        //                'attribute' => 'restaurant_group_id',
        //                'value' => function($model) {
        //                    return Yii::$app->globalCache->getLabel($model->restaurantGroup->name_key);
        //                },
        //                'filter' => common\models\RestaurantGroup::getRestaurantGroupsForSelect(),
        //                'options' => ['class' => 'col-xs-1'],
        //                'label' => Yii::t('label', 'restaurant_group_id'),
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
    </div>
</div>
