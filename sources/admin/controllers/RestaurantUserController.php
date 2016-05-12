<?php

namespace admin\controllers;

use common\models\Restaurant;
use Yii;
use common\models\RestaurantUser;
use admin\controllers\search\RestaurantUserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
/**
 * RestaurantUserController implements the CRUD actions for RestaurantUser model.
 */
class RestaurantUserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowAllActionsForRestaurantUser(Yii::$app->request->getFirstParamValue(['restaurant_id'])),
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
     * Lists all RestaurantUser models.
     * @return mixed
     */
    public function actionIndex($restaurant_id = null)
    {
        $searchModel = new RestaurantUserSearch();
        $restaurant = Restaurant::getById($restaurant_id);
        $searchModel->restaurant_id = $restaurant_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * Creates a new RestaurantUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurant_id = null)
    {
        $model = new RestaurantUser();
        $model->restaurant_id = $restaurant_id;
        $restaurant = Restaurant::getById($restaurant_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_id' => $restaurant->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'restaurant' => $restaurant
            ]);
        }
    }

    /**
     * Updates an existing RestaurantUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_id' => $model->restaurant_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'restaurant' => $model->restaurant
            ]);
        }
    }

    /**
     * Deletes an existing RestaurantUser model.
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
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing RestaurantUser model.
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
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing RestaurantUser model.
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
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    /**
     * Finds the RestaurantUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RestaurantUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestaurantUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
