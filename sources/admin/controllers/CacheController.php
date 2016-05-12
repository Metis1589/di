<?php

namespace admin\controllers;

use common\enums\RecordType;
use common\models\User;
use Yii;
use common\models\Cuisine;
use admin\controllers\search\CuisineSearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Label;
use common\components\GlobalCacheMessageSource;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;

/**
 * CuisineController implements the CRUD actions for Cuisine model.
 */
class CacheController extends BaseController
{
    public $enableCsrfValidation = false;
    /**
     * Lists all Cuisine models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $method = Yii::$app->request->post('method');
            $key = Yii::$app->request->post('key');
            if ($method == 'get') {
                var_dump(Yii::$app->globalCache->getValue($key));
                die();
            }
            if ($method == 'remove') {
                Yii::$app->globalCache->deleteValue($key);
            }
        }
        return $this->render('index');
    }

}
