<?php

namespace admin\common;

use yii\helpers\Html;
use Yii;

class MenuCategoryActionColumn extends CustomActionColumn {
    
    
    protected function initDefaultButtons() {
        
        parent::initDefaultButtons();
        
        $this->template = '{menu_items} {update} {activate} {deactivate} {delete}';

        $this->buttons['menu_items'] = function ($url, $model) {
               return Html::a('<span class="fa fa-sitemap"></span>', \yii\helpers\Url::to(['menu-item/index', 'MenuItemSearch[menu_category_id]' => $model->id]), ['title' => Yii::t('label', 'Menu Items'),'data-pjax'=>'false', 'target'=>'_blank']);
        };        
        
    }
}
