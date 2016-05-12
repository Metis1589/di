<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\PostcodeBlacklistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Postcode Blacklists');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="postcode-blacklist-index">

    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Postcode Blacklist',
]), ['create'], ['class' => 'btn btn-success right']) ?>
	</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


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

//            [
//                'attribute' => 'id',
//                'options' => ['class' => 'col-xs-1'],
//                'label' => Yii::t('label', 'id'),
//            ],
//            [
//                'attribute' => 'client_id',
//                'options' => ['class' => 'col-xs-1'],
//                'label' => Yii::t('label', 'client_id'),
//            ],
            [
                'attribute' => 'postcode.postcode',
                'options' => ['class' => 'col-xs-4'],
                'label' => Yii::t('label', 'postcode'),
            ],
            [
                'attribute' => 'postcode.latitude',
                'options' => ['class' => 'col-xs-3'],
                'label' => Yii::t('label', 'latitude'),
            ],
            [
                'attribute' => 'postcode.longitude',
                'options' => ['class' => 'col-xs-3'],
                'label' => Yii::t('label', 'longitude'),
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
