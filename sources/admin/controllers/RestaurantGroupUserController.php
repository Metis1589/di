<?php

namespace admin\controllers;

use common\models\RestaurantGroup;
use Yii;
use common\models\RestaurantGroupUser;
use admin\controllers\search\RestaurantGroupUserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
/**
 * RestaurantGroupUserController implements the CRUD actions for RestaurantGroupUser model.
 */
class RestaurantGroupUserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowAllActionsForRoles([
                            UserType::Admin,
                            UserType::RestaurantOwner,

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
     * Lists all RestaurantGroupUser models.
     * @return mixed
     */
    public function actionIndex($restaurant_group_id = null)
    {
        $searchModel = new RestaurantGroupUserSearch();
        $restaurantGroup = RestaurantGroup::getById($restaurant_group_id);
        $searchModel->restaurant_group_id = $restaurant_group_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'restaurantGroup' => $restaurantGroup,
        ]);
    }

    /**
     * Creates a new RestaurantGroupUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurant_group_id = null)
    {
        $model = new RestaurantGroupUser();
        $restaurantGroup = RestaurantGroup::getById($restaurant_group_id);
        $model->restaurant_group_id = $restaurant_group_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_group_id' => $model->restaurant_group_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'restaurantGroup' => $restaurantGroup,
            ]);
        }
    }

    /**
     * Updates an existing RestaurantGroupUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_group_id' => $model->restaurant_group_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'restaurantGroup' => $model->restaurantGroup,
            ]);
        }
    }

    /**
     * Deletes an existing RestaurantGroupUser model.
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
     * Activates an existing RestaurantGroupUser model.
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
     * Deactivates an existing RestaurantGroupUser model.
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
     * Finds the RestaurantGroupUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RestaurantGroupUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestaurantGroupUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
