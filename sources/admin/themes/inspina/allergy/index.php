<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\AllergySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Allergies');
$this->params['breadcrumbs'][] = $this->title;

$this->registerActionButton(['create'], 'Create Allergy');
?>
<div class="allergy-index">
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
                    $name_key = substr($model->name_key, 0, 40);
                    $name_key = (strlen($name_key)>= 40) ? $name_key . ' ...' : $name_key; 
                    return  $name_key;
                },
            ],
            [
                'attribute' => 'description_key',
                'filter' => '',
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) {
                    $name_key = substr($model->description_key, 0, 40);
                    $name_key = (strlen($name_key)>= 40) ? $name_key . ' ...' : $name_key; 
                    return  $name_key;
                },
            ],
            [
                'attribute' => 'symbol_key',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'symbol_key'),
            ],
            [
                'attribute' => 'image_file_name',
                'format' => 'raw',
                'value' => function ($model) {
                    return  is_null($model->image_file_name) ? '' : Html::img(Yii::$app->params['images_base_url'] . \common\components\ImageHelper::getThumbFilename(\common\components\IOHelper::getAllergyImagesPath().$model->image_file_name), ['height' => '100']);
                },
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'image_file_name'),
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
