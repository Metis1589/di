<?php

namespace admin\common;

use yii\helpers\Html;
use Yii;

class CustomActionColumn extends \kartik\grid\ActionColumn {
    
    protected $editUrlParams;


    protected function initDefaultButtons() {
        $isImpersonated = Yii::$app->request->isImpersonated();
        $clientId = Yii::$app->request->getQueryParam('client_id');
        $this->editUrlParams = '';
        if ($isImpersonated && $clientId != null) {
            $this->editUrlParams = '&client_id='.$clientId;
        }
        $this->template = '{update} {activate} {deactivate} {delete}';
        $this->header = '<i class="fa fa-eraser grid-eraser" title="'.Yii::t('label','Clear Filters').'"></i>';
        
        $this->buttons = [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="fa fa-pencil"></span>', $url.$this->editUrlParams, ['title' => Yii::t('label', 'Update'),'data-pjax'=>'false']);
                    },
                    'delete' => function ($url, $model) {
                        if (isset($model['is_default']) && $model->is_default) {
                            return '';
                        }
                        return Html::a('<span class="fa fa-trash"></span>', $url, ['title' => Yii::t('label', 'Delete'), 'data-pjax'=>'0', 'data-method'=>'post', 'data-confirm' => Yii::t('label','Are you sure to delete this item?')]);
                    },
                    'activate' => function ($url, $model) {
                        return $model['record_type'] == 'Inactive' ? Html::a('<span class="fa fa-check-circle-o"></span>', $url, ['title' => Yii::t('label', 'Activate')]) : '';
                    },
                    'deactivate' => function ($url, $model) {
                        return $model['record_type'] == 'Active' ? Html::a('<span class="fa fa-ban"></span>', $url, ['title' => Yii::t('label', 'Deactivate')]) : '';
                    },
                ];
        
        
        parent::initDefaultButtons();
    }
}
