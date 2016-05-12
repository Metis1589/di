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
class CuisineController extends BaseController
{
    public function behaviors()
    {
        return [
             'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowAllActionsForRoles([
                            UserType::Admin
                        ]),
                    ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cuisine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CuisineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Cuisine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cuisine();
               
        if ($model->load(Yii::$app->request->post()) && Yii::$app->translationLanguage->saveModel($model, null)) {
            $this->reloadCache();
            return $this->redirect(['index']);
        }
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cuisine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->translationLanguage->saveModel($model, null)) {
            $this->reloadCache();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cuisine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Deleted';
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing Cuisine model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Active';
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing Cuisine model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = RecordType::InActive;
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    private function reloadCache() {
        Yii::$app->globalCache->addUpdateCacheAction('loadCuisines()');
    }

    /**
     * Finds the Cuisine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Cuisine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cuisine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
