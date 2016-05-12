<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use admin\common\StringHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton(['/project/create'], 'Create Project');
?>
<div class="project-index">

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
                'attribute' => 'code',
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) {return StringHelper::SubstrForTable($model->code, 30);},
            ],
            [
                'attribute' => 'name',
                'options' => ['class' => 'col-xs-1'],
                'value' => function($model) {return StringHelper::SubstrForTable($model->name, 30);},
            ],
//            [
//                'attribute' => 'daily_limit',
//                'options' => ['class' => 'col-xs-1']
//            ],
//            [
//                'attribute' => 'weekly_limit',
//                'options' => ['class' => 'col-xs-1']
//            ],
//            [
//                'attribute' => 'monthly_limit',
//                'options' => ['class' => 'col-xs-1']
//            ],
            [
                'attribute' => 'limit_type',
                'options' => ['class' => 'col-xs-1'],
                'filter' => common\enums\ProjectLimitType::getLabels(),
            ],
            [
                'attribute' => 'company_id',
                'options' => ['class' => 'col-xs-1'],
                'filter' => common\models\Company::getCompaniesForSelect(),               
                'value' => function($model) {
                    return $model->getCompany()->one()->name;
                },
                'label' => Yii::t('label', 'Company'),
            ],
            [
                'attribute' => 'user_id',
                'options' => ['class' => 'col-xs-1'],
               // 'filter' => common\models\User::getUsersForSelect(),               
                'value' => function($model) {
                    return $model->getUser()->one()->username;
                },
                'label' => Yii::t('label', 'User'),
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
