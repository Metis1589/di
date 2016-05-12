<?php

use common\enums\UserType;
use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Users');
$this->params['breadcrumbs'][] = $this->title;
$this->registerActionButton( ['create'], 'Create User');
?>
<div class="user-index">

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
                'attribute' => 'username',
                'options' => ['class' => 'col-xs-1'],
                'label' => Yii::t('label', 'username'),
            ],
            [
                'attribute' => 'user_type',
                'options' => ['class' => 'col-xs-1'],
                'filter' => UserType::getLabels(),
                'value' => function($model) {
                    return UserType::getLabels()[$model->user_type];
                },
                'label' => Yii::t('label', 'user_type'),
            ],
            [
                'attribute' => 'create_on',
                'label' => Yii::t('label','Register Date'),
                'format' => 'date',
                'value' => 'create_on',
                'filter' => Html::input('text', 'UserSearch[create_on]', $searchModel->create_on, ['class' => 'form-control date-jui-picker']),
                'options' => ['class' => 'col-xs-1']
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
