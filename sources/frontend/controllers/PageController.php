<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use frontend\components\PageHelper;

class PageController extends \yii\web\Controller
{

    public function actionPage($url)
    {
        $page = PageHelper::getPageByUrl($url);
        if ($page===null){
//            throw new NotFoundHttpException(T::e('The requested page does not exist.'));
            Yii::$app->response->redirect('/');
        }
        $page['content'] = \yii\helpers\Html::decode($page['content']);
        return $this->render('page', [
            'page' => $page
        ]);
    }
}
