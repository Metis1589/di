<?php

use common\components\language\T;
use common\enums\RecordType;
use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\AddressBaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Address Bases');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['create'], 'Create Address Base');
?>
<div class="address-base-index">
    <div class="row">
        <div class="btn-group col-xs-1 col-xs-offset-11">
            <button class="btn btn-primary dropdown-toggle " data-toggle="dropdown" aria-expanded="false"><?=T::l('Action') ?> <span class="caret"></span></button>
            <ul class="dropdown-menu grid-multiple-action">
                <li><a href="#" class="btn btn-primary" data-prop="record_type" data-value="<?= RecordType::Active ?>"><?=T::l('Activate') ?></a></li>
                <li><a href="#" class="btn btn-primary" data-prop="record_type" data-value="<?= RecordType::InActive ?>"><?=T::l('Deactivate') ?></a></li>
                <li class="divider"></li>
                <li><a href="#" class="btn btn-primary" data-prop="max_delivery_distance"><?=T::l('Max distance is') ?> <input type="number"/></a></li>
            </ul>
        </div>
    </div>
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
                'options' => ['class' => 'col-xs-4'],
                'value' => function($model) {
                    $descr = substr(Yii::$app->globalCache->getLabel($model->name),0,30);
                    $descr = (strlen($descr)>= 30) ? $descr . ' ...' : $descr; 
                    return  $descr;
                },
            ],
            [
                'attribute' => 'delivery_delay_time',
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'attribute' => 'postcode',
                'options' => ['class' => 'col-xs-1']
            ],
            [
                'attribute' => 'max_delivery_distance',
                'options' => ['class' => 'col-xs-1']
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
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]); ?>

</div>

