<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Language;
use common\models\Page;
use common\behaviors\PublishedStatusBehavior;
use common\components\DateHelper;
use yii\helpers\ArrayHelper;
use admin\common\FilterHelper;
use common\enums\EmailType;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $searchModel cms\controllers\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label','Emails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emailtemplate-index">
    
    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('label','Create email template'), ['create'], ['class' => 'btn btn-success right']) ?>
    </h1>

    <?= GridView::widget([
        'id' => 'table-grid',
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
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
                'options' => ['class' => 'col-xs-1'],
            ],
            [
                'attribute' => 'title',
                'options' => ['class' => 'col-xs-4'],
            ],
            [
                'label' => Yii::t('label', 'email_type'),
                'attribute' => 'email_type',
                'filter' => EmailType::getLabels(),
                'value' => function($model) {
                    return $model->email_type && array_key_exists($model->email_type,EmailType::getLabels()) ? EmailType::getLabels()[$model->email_type] : '';
                },
                'options' => ['class' => 'col-xs-4'],
            ],
            [
                'label' => Yii::t('label', 'record_type'),
                'attribute' => 'record_type',
                'filter' => FilterHelper::recordTypeValues(),
                'value' => function($model) {
                    return FilterHelper::recordTypeValues()[$model->record_type];
                },
                'options' => ['class' => 'col-xs-3'],
            ],  
            [   
                'class' => 'kartik\grid\ActionColumn', 
                'template' => '{update}', 
                'header' => '<i class="fa fa-eraser grid-eraser" title="'.Yii::t('label','Clear Filters').'"></i>',
            ],
        ],
    ]) ?>
</div>
