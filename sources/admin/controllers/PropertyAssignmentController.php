<?php

namespace admin\Controllers;

use common\components\identity\RbacHelper;
use common\enums\UserType;
use Yii;
use common\models\PropertyAssignment;
use admin\Controllers\Search\PropertyAssignmentSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropertyAssignmentController implements the CRUD actions for PropertyAssignment model.
 */
class PropertyAssignmentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowAllActionsForRoles([
                        UserType::Admin,
                        UserType::ClientAdmin,
                        UserType::RestaurantChainAdmin,
                        UserType::RestaurantGroupAdmin,
                        UserType::RestaurantAdmin,
                        UserType::RestaurantTeam
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
     * Lists all PropertyAssignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertyAssignmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    private function getPropertyAssignments($model){
        $assignment = new PropertyAssignment();
         
         if ($model instanceof \common\models\RestaurantChain){
             $restaurant_chain_assignment = PropertyAssignment::find()->where(['restaurant_chain_id' => $model->id])->one();
             if (!empty($restaurant_chain_assignment)){
                 $assignment = $restaurant_chain_assignment;
             }
         }
         
         if ($model instanceof \common\models\RestaurantGroup){
             $restaurant_group_assignment = PropertyAssignment::find()->where(['restaurant_group_id' => $model->id])->one();
             if (!empty($restaurant_group_assignment)){
                 $assignment = $restaurant_group_assignment;
             }
         }
         
         if ($model instanceof \common\models\Restaurant){
             $restaurant_assignment = PropertyAssignment::find()->where(['restaurant_id' => $model->id])->one();
             if (!empty($restaurant_assignment)){
                 $assignment = $restaurant_assignment;
             }
         }
         
         if ($model instanceof \common\models\Client){
             $client_assignment = PropertyAssignment::find()->where(['client_id' => $model->id])->one();
             if (!empty($client_assignment)){
                 $assignment = $client_assignment;
             }
         }
         
         return $assignment;
    }


    public function actionForm($model)
    {
         $assignment = $this->getPropertyAssignments($model);   
         
         return $this->renderPartial('_form', [
                'model' => $assignment,
                'id' => $model->id,
                'modelClass' => get_class($model)
            ]);
    }

    /**
     * Displays a single PropertyAssignment model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PropertyAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PropertyAssignment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PropertyAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->getModel($id);
        $assignment = $this->getPropertyAssignments($model);

        if ($assignment->load(Yii::$app->request->post())){
             if ($model instanceof \common\models\RestaurantChain){
                 $assignment->restaurant_chain_id = $model->id;
             }
             if ($model instanceof \common\models\RestaurantGroup){
                 $assignment->restaurant_group_id = $model->id;
             }
             if ($model instanceof \common\models\Restaurant){
                 $assignment->restaurant_id = $model->id;
             }
             if ($model instanceof \common\models\Client){
                 $assignment->client_id = $model->id;
             }
             
             if ($assignment->save()){
                 Yii::$app->session->setFlash('success', Yii::t('label','Assignments were successfully updated.'));
                 $this->reloadCache($model);
                 $this->redirectProperty($model);
             }
         }
         
         return $this->renderPartial('update', [
                'model' => $assignment,
            ]);
    }
    
    private function redirectProperty($model){
        $class = get_class($model);
        
        if ($class == 'common\models\RestaurantChain'){
            return $this->redirect(['/restaurant-chain/update', 'id' => $model->id]);
        }
        
        if ($class == 'common\models\RestaurantGroup'){
            return $this->redirect(['/restaurant-group/update', 'id' => $model->id]);
        }
        
        if ($class == 'common\models\Restaurant'){
            return $this->redirect(['/restaurant/update', 'id' => $model->id]);
        }
        
        return $this->redirect(['/client/update', 'id' => $model->id]);
    }

    /**
     * Deletes an existing PropertyAssignment model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing PropertyAssignment model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing PropertyAssignment model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    private function reloadCache($model) {
        $client_key = null;
        $class = get_class($model);
        if ($class == 'common\models\RestaurantChain') {
            $client_key = $model->client->key;
        } else if ($class == 'common\models\RestaurantGroup') {
            $client_key = $model->restaurantChain->client->key;
        } else if ($class == 'common\models\Restaurant') {
            $client_key = $model->client->key;
        } else if ($class == 'common\models\Client') {
            $client_key = $model->key;
        } else {
            throw new Exception('Client was not found');
        }
        Yii::$app->globalCache->addUpdateCacheAction("loadRestaurantsByClientKey('".$client_key."')");
    }
    
    private function getModel($id){
        if (Yii::$app->request->isPost) {
            $class = Yii::$app->request->post('modelClass');
            return $this->findModel($id, $class);
        }
    }

    /**
     * Finds the PropertyAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PropertyAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $class)
    {
        if (($model = $class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
