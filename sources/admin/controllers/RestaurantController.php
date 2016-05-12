<?php

namespace admin\controllers;

use common\components\FormatHelper;
use common\enums\RecordType;
use common\models\BestForItem;
use common\models\Cuisine;
use common\models\RestaurantBestForItem;
use common\models\RestaurantCuisine;
use common\models\Restaurant;
use common\models\RestaurantContact;
use Exception;
use Yii;
use admin\controllers\search\RestaurantSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use yii\web\Response;
use admin\common\ArrayHelper;
use common\components\DispatchService;


/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends BaseController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowAllActionsForRestaurantUser(Yii::$app->request->getFirstParamValue(['id','restaurant_id']))
                    ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'assign-best-for-item' => ['post'],
                    'assign-cuisine' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionGetDetails($restaurant_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $restaurant = Restaurant::find(['id' => $restaurant_id])->with(['contacts'])->one();
        if ($restaurant == null) {
            $restaurant = new Restaurant();
        }
        if (empty($restaurant->contact))
        {
            $restaurant->populateRelation('contact', new RestaurantContact());
        }
        if (empty($restaurant->billing))
        {
            $restaurant->populateRelation('billing', new RestaurantContact());
        }
        
        $this->renderJson(ArrayHelper::convertArToArray($restaurant));
    }

    public function actionSaveDetails()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        if (Restaurant::saveDetails($data['schedules'], $data['restaurant_id'])) {
            $restaurant = Restaurant::findOne($data['restaurant_id']);
            $this->reloadCache($restaurant);
            return 'Success';
        }
        return 'Error';
        //$this->renderJson($data['schedules']);
    }
    
    public function actionForm()
    {
        return $this->renderPartial('_form');
    }

    public function actionCuisineBestForItemForm($restaurant_id)
    {
        $cuisines = Cuisine::find()->where('record_type <> "'.RecordType::Deleted.'"')->all();
        $best_for_items = BestForItem::find()->where('record_type <> "'.RecordType::Deleted.'"')->all();
        $restaurantCuisines = RestaurantCuisine::find()->where('record_type = "'.RecordType::Active.'" && restaurant_id ='. $restaurant_id)->all();
        $restaurantBestForItems = RestaurantBestForItem::find()->where('record_type = "'.RecordType::Active.'" && restaurant_id ='. $restaurant_id)->all();
        $assignedCuisines = [];
        $assignedBestForItems = [];
        foreach($restaurantCuisines as $c) {
            $assignedCuisines[] = $c->cuisine_id;
        }
        foreach($restaurantBestForItems as $i) {
            $assignedBestForItems[] = $i->best_for_item_id;
        }
        return $this->renderPartial('_cuisine_best_for_items', [
            'cuisines' => $cuisines,
            'best_for_items' => $best_for_items,
            'assignedCuisines' => $assignedCuisines,
            'assignedBestForItems' => $assignedBestForItems
        ]);
    }

    public function actionAssignBestForItem()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $result = RestaurantBestForItem::saveByPost($data);
        if ($result) {
            $restaurant_id = $data['restaurant_id'];
            $restaurant = Restaurant::findOne($restaurant_id);
            $this->reloadCache($restaurant);
        }
    }

    public function actionAssignCuisine()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $result = RestaurantCuisine::saveByPost($data);
        if ($result) {
            $restaurant_id = $data['restaurant_id'];
            $restaurant = Restaurant::findOne($restaurant_id);
            $this->reloadCache($restaurant);
        }
        return $result;
    }

    /**
     * Lists all Restaurant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RestaurantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Restaurant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($tab = 'restaurant')
    {
        $model = new Restaurant();
        $model->default_preparation_time = FormatHelper::formatTime(mktime(0, 0, 0));
        $model->default_cook_time = FormatHelper::formatTime(mktime(0, 0, 0));
        $model->prepareRelationRecords(Yii::$app->request->post());
        $redirect = false;
        if (Yii::$app->request->post()) {
            $redirect = $model->saveRestaurantDetails(Yii::$app->request->post());
        }
        if($redirect){
            $depot = DispatchService::createDepot($model);
            if(!$depot){
                Yii::error('Depot create error');
            }
            $this->reloadCache($model);
            return $this->redirect(['update','id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'tab' => $tab
        ]);
    }

    /**
     * Updates an existing Restaurant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $tab = 'restaurant')
    {
        $model = $this->findModel($id);
        $model->prepareRelationRecords(Yii::$app->request->post());
        $redirect = false;
        if (Yii::$app->request->post()) {
            $redirect = $model->saveRestaurantDetails(Yii::$app->request->post());
        }
        if($redirect){
            $this->reloadCache($model);
            return $this->redirect(['update','id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'tab' => $tab
        ]);
    }
    
    /**
     * Deletes an existing Restaurant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Deleted';
        if ($model->save())
        {
            DispatchService::deleteDepot($model->id);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing Restaurant model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Active';
        if ($model->save(true,['record_type']))
        {
            DispatchService::createDepot($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing Restaurant model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Inactive';
        if ($model->save(true,['record_type']))
        {
            DispatchService::deleteDepot($model->id);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    private function reloadCache($model) {
        Yii::$app->globalCache->addUpdateCacheAction("loadRestaurantsByClientKey('".$model->client->key."')");
        Yii::$app->globalCache->addUpdateCacheAction("loadLabels('".$model->client->key."')");
    }
    
    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Restaurant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
