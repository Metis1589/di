<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\Controllers\Search\VoucherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Vouchers');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton( ['create'], 'Create Voucher');
?>
<div class="voucher-index">
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
                'attribute' => 'category',
                'filter' => common\enums\VoucherCategory::getLabels(),
                'options' => ['class' => 'col-xs-2'],
                'label' => Yii::t('label', 'category'),
            ],
            [
                'attribute' => 'code',
                'options' => ['class' => 'col-xs-2'],
                'label' => Yii::t('label', 'code'),
            ],
            [
                'attribute' => 'discount_value',
                'options' => ['class' => 'col-xs-2'],
                'label' => Yii::t('label', 'discount_value'),
            ],
            [
                'attribute' => 'value_type',
                'filter' => \common\enums\VoucherValueType::getLabels(),
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'value_type'),
            ],
            [
                'attribute' => 'start_date',
                'filter' => Html::input('text', 'VoucherSearch[start_date]', $searchModel->start_date, ['class' => 'form-control date-jui-picker']),
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'start_date'),
            ],
            [
                'attribute' => 'end_date',
                'filter' => Html::input('text', 'VoucherSearch[end_date]', $searchModel->end_date, ['class' => 'form-control date-jui-picker']),
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'end_date'),
            ],
            [
                'attribute' => 'max_times_per_user',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'max_times_per_user'),
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
