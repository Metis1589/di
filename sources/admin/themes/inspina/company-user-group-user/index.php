<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\controllers\search\CompanyUserGroupUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'User Group Assignment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'User Group'), 'url' => ['/company-user-group/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerActionButton(['create'], 'Assign User');
?>
<div class="company-user-group-user-index">

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
                'attribute' => 'user_username',
                'options' => ['class' => 'col-xs-4'],
                'filter' => \common\models\User::getUsersForSelect(),
            ],
            [
                'attribute' => 'company_user_group_id',
                'options' => ['class' => 'col-xs-4'],
                'filter' => \common\models\CompanyUserGroup::getGroupsForSelect(),
                'value' => 'company_user_group_name',
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
