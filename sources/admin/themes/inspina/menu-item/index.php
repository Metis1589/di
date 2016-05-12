<?php

use common\models\Menu;
use common\models\MenuCategory;
use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Menu Items');
$this->params['breadcrumbs'][] = $this->title;

$this->registerActionButton(['create'], 'Create Menu Items');

$queryParams = Yii::$app->getRequest()->getQueryParam('MenuItemSearch',['menu_id' => null]);

if (isset($queryParams) && array_key_exists('menu_id', $queryParams)) {
    $menu_id = $queryParams['menu_id'];
}
else {
    $menu_id = null;
}

?>
<div class="menu-item-index">
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
            [
                'attribute' => 'menu_id',
                'filter' => Menu::getMenuForSelect(),
                'value' => 'menu.reference_name',
                'options' => ['class' => 'col-xs-2'],
                'label' => Yii::t('label', 'Menu Reference Name'),
            ],
            [
                'options' => ['class' => 'col-xs-2'],
                'attribute' => 'menu_category_id',
                'filter' => MenuCategory::getMenuCategoriesByClientId(Yii::$app->request->getImpersonatedClientId(), $menu_id),
                'value' => 'menuCategory.reference_name',
                'label' => Yii::t('label', 'Category Reference Name'),
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
                'attribute' => 'restaurant_price',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'restaurant_price'),
            ],
            [
                'attribute' => 'web_price',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'web_price'),
            ],
            [
                'attribute' => 'cook_time',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'cook_time'),
            ],
            [
                'attribute' => 'vat_id',
                'filter' => \common\models\Vat::getVATForSelect(),
                'value' => 'vat.type',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'vat_id'),
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
                 'class' => '\kartik\grid\ActionColumn',
                 'template' => '{up} {down}',
                 'header' => '',
                 'options' => ['class' => 'col-xs-1'],
                 'buttons' => [
                    'up' => function ($url, $model) use ($min_sort_order) {
                        return $model['sort_order'] > $min_sort_order ? Html::a('<span class="fa fa-arrow-up"></span>', $url, ['title' => Yii::t('label', 'Up')]): '';
                    },
                    'down' => function ($url, $model) use ($max_sort_order) {
                        return $model['sort_order'] < $max_sort_order ? Html::a('<span class="fa fa-arrow-down"></span>', $url, ['title' => Yii::t('label', 'Down')]): '';
                    },]
            ],
            [
             'class' => 'admin\common\CustomActionColumn',
             'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]); ?>

</div>
