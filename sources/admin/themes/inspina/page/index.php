<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Language;
use common\models\Page;
use common\models\Navigation;
use common\behaviors\PublishedStatusBehavior;
use common\components\DateHelper;
use yii\helpers\ArrayHelper;
use admin\common\FilterHelper;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $searchModel cms\controllers\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label','Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('label','Create page'), ['create'], ['class' => 'btn btn-success right']) ?>
    </h1>

    <?= GridView::widget([
        'id' => 'table-grid',
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
               'width'=>'30px'
            ],
            [
                'attribute' => 'language_id',
                'value' => function ($model) {
                    $languages = ArrayHelper::map(Yii::$app->globalCache->getLanguageList(true), 'id', 'name');
                    /** @var $model \common\models\Page */
                    return array_key_exists($model->language_id,$languages) ? $languages[$model->language_id] : T::e('Language was deleted');
                },
                'format' => 'raw',
                'filter' => ArrayHelper::map(Yii::$app->globalCache->getLanguageList(true), 'id', 'name'),
                'options' => ['class' => 'col-xs-3'],
            ],
            [
                'attribute' => 'title',
                'options' => ['class' => 'col-xs-3'],
            ],            
            [
                'attribute' => 'slug',
                'options' => ['class' => 'col-xs-2'],
            ],
            [
                'label' => Yii::t('label', 'record_type'),
                'attribute' => 'record_type',
                'filter' => FilterHelper::recordTypeValues(),
                'value' => function($model) {
                    return FilterHelper::recordTypeValues()[$model->record_type];
                },
                'options' => ['class' => 'col-xs-2'],
            ],  
            [
                'class' => 'admin\common\CustomActionColumn',
                'options' => ['class' => 'col-xs-1'],
            ],
        ],
    ]) ?>
</div>
