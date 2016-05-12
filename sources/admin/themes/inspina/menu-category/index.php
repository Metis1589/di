<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\Controllers\Search\MenuCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Menu Categories');
$this->params['breadcrumbs'][] = $this->title;

$this->registerActionButton(['create'], 'Create Menu Category');
?>
<div class="menu-category-index">
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
                'attribute' => 'menu_id',
                'filter' => \common\models\Menu::getMenuForSelect(),
                'value' => 'menu.reference_name',
                'options' => ['class' => 'col-xs-2'],
                'label' => Yii::t('label', 'Menu Reference Name'),
            ],
            [
                'attribute' => 'name_key',
                'options' => ['class' => 'col-xs-2'],
                'value' => function($model) {
                    $name = substr($model->name_key, 0, 40);
                    $name = (strlen($name)>= 40) ? $name . ' ...' : $name;
                    return  $name;
                },
            ],
            [
                'attribute' => 'reference_name',
                'options' => ['class' => 'col-xs-2'],
                'value' => function($model) {
                    $reference_name = substr($model->reference_name, 0, 40);
                    $reference_name = (strlen($reference_name)>= 40) ? $reference_name . ' ...' : $reference_name;
                    return  $reference_name;
                },
            ],
            [
                'attribute' => 'description_key',
                'options' => ['class' => 'col-xs-3'],
                'value' => function($model) {
                    $name_key = substr($model->description_key, 0, 40);
                    $name_key = (strlen($name_key)>= 40) ? $name_key . ' ...' : $name_key; 
                    return  $name_key;
                },
            ],
//            [
//                'format' => 'raw',
//                'value' => function ($model) {
//                    return  is_null($model->image_file_name) ? '' : Html::img(Yii::$app->params['images_base_url'] . \common\components\ImageHelper::getThumbFilename(\common\components\IOHelper::getMenuCategoryImagesPath().$model->image_file_name), ['height' => '100']);
//                },
//                'options' => ['class' => 'col-xs-1'],
//                'label' => Yii::t('label', 'image'),
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
                 'class'    => '\kartik\grid\ActionColumn',
                 'template' => '{up} {down}',
                 'header'   => '',
                 'options'  => ['class' => 'col-xs-1'],
                 'buttons'  => [
                    'up' => function ($url, $model) use ($min_sort_order) {
                        return $model['sort_order'] > $min_sort_order ? Html::a('<span class="fa fa-arrow-up"></span>', $url, ['title' => Yii::t('label', 'Up')]): '';
                    },
                    'down' => function ($url, $model) use ($max_sort_order) {
                        return $model['sort_order'] < $max_sort_order ? Html::a('<span class="fa fa-arrow-down"></span>', $url, ['title' => Yii::t('label', 'Down')]): '';
                    },]
            ],
            [
             'class' => 'admin\common\MenuCategoryActionColumn',
             'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]); ?>

</div>
