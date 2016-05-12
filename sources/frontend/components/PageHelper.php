<?php

namespace frontend\components;

use Yii;
use yii\helpers\Url;

class PageHelper {

    public static function getPageByUrl($url,$language_id=null){
        if(!$language_id){
            $language_id = Yii::$app->frontendCache->getLanguageId(Yii::$app->translationLanguage->language);
        }
        $pages = Yii::$app->frontendCache->getPages();
        foreach($pages as $page){
            if($page['slug']===$url && $language_id==$page['language_id']){
                return $page;
            }
        }
        return null;
    }

    public static function getPagesMenu($language_id=null){
        $return = [];
        if(!$language_id){
            $language_id = Yii::$app->frontendCache->getLanguageId(Yii::$app->translationLanguage->language);
        }
        $pages = Yii::$app->frontendCache->getPages();
        foreach($pages as $page){
            // Don't include main page in pages list for menu
            if($page['slug'] && $language_id==$page['language_id']){
                $return[] = [
                    'label' => $page['title'],
                    'url' => Url::to(['/page/page','url'=>$page['slug']])
                ];
            }
        }
        return $return;
    }

}