<?php

namespace admin\controllers;

use common\enums\RecordType;
use Yii;
use common\models\RestaurantChain;
use admin\controllers\search\RestaurantChainSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Label;
use common\components\GlobalCacheMessageSource;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
/**
 * RestaurantChainController implements the CRUD actions for RestaurantChain model.
 */
class RestaurantChainController extends BaseController
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowAllActionsForRestaurantChainUser(Yii::$app->request->getFirstParamValue(['id'])),
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
     * Lists all RestaurantChain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RestaurantChainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new RestaurantChain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestaurantChain();
                
        if ($model->load(Yii::$app->request->post())) {

            $model->client_id = Yii::$app->request->getImpersonatedClientId();

            if (Yii::$app->translationLanguage->saveModel($model, $model->client_id)) {
                $this->reloadCache($model);
                return $this->redirect(['index']);
            }

        }
            
        return $this->render('update', [
                'model' => $model,
        ]);
 
    }

    /**
     * Updates an existing RestaurantChain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->translationLanguage->saveModel($model, $model->client_id)) {
                $this->reloadCache($model);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RestaurantChain model.
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
            $this->reloadCache($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing RestaurantChain model.
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
            $this->reloadCache($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing RestaurantChain model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Inactive';
        if ($model->save())
        {
            $this->reloadCache($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }
    
    public function actionGetRestaurantGroups($id){
        echo json_encode(\common\models\RestaurantGroup::getTreeAsArray($id), JSON_NUMERIC_CHECK );
        die();
    }
    
    public function actionSaveRestaurantGroups($id){
        $groups = json_decode(Yii::$app->request->rawBody, true);
        $result = \common\models\RestaurantGroup::saveTreeAsArray($id, $groups);
        if ($result) {
            $restaurantChain = RestaurantChain::findOne($id);
            $this->reloadCache($restaurantChain);
        }
        return $result;
    }

    private function reloadCache($model){
        Yii::$app->globalCache->addUpdateCacheAction("loadRestaurantsByClientKey('".$model->client->key."')");
    }

    /**
     * Finds the RestaurantChain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RestaurantChain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestaurantChain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
