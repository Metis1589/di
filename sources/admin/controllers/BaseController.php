<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

/**
 * Site controller
 */
class BaseController extends Controller
{
    protected function redirectToPreviousPage()
    {
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function renderJson($array)
    {
        header('Content-type: application/json');
        echo json_encode( $array );
        Yii::$app->end();
    }
    
    protected function redirectWithFilters($url)
    {
        $query = [];
        $parts = parse_url(Yii::$app->request->referrer);
        if (isset($parts['query'])){
            parse_str($parts['query'], $query);

            if (!empty($query)){
                return $this->redirect(Url::to($url) .'?'. http_build_query($query));
            }
        }
        return $this->redirect(Url::to($url));
    }
}
