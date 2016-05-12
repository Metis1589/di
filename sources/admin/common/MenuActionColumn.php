<?php

namespace admin\common;

use yii\helpers\Html;
use Yii;

class MenuActionColumn extends CustomActionColumn {
    
    
    protected function initDefaultButtons() {
        
        parent::initDefaultButtons();
        
        $this->template = '{categories} {update} {activate} {deactivate} {delete}';

        $this->buttons['categories'] = function ($url, $model) {
               return Html::a('<span class="fa fa-sitemap"></span>', \yii\helpers\Url::to(['menu-category/index', 'MenuCategorySearch[menu_id]' => $model->id]), ['title' => Yii::t('label', 'Categories'),'data-pjax'=>'false', 'target'=>'_blank']);
        };        
        
    }
}
