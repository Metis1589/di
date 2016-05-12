<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\AreaAddress */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Area Addresses');
$this->params['breadcrumbs'][] = $this->title;

$this->registerActionButton(['create'], 'Create Area Address');

?>
<div class="area-address-index">

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
                'attribute' => 'native_name',
                'options' => ['class' => 'col-xs-3']
            ],
            [
                'attribute' => 'country_id',
                'filter' => \common\models\Country::getCountriesForSelect(),
                'options' => ['class' => 'col-xs-3'],
                'label' => Yii::t('label','Country Native Name'),
                'value' => 'country_name'
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
