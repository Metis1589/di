<?php

namespace admin\controllers;

use Yii;
use common\models\RestaurantContact;
use admin\controllers\search\RestaurantContactSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\forms\RestaurantContactForm;
use common\enums\RecordType;
use common\models\Restaurant;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;

/**
 * RestaurantContactController implements the CRUD actions for RestaurantContact model.
 */
class RestaurantContactController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowAllActionsForRoles([
                            UserType::Admin,
                            UserType::RestaurantGroupAdmin, 
                            UserType::RestaurantOwner,
                            UserType::RestaurantTeam, 
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
     * Lists all RestaurantContact models.
     * @return mixed
     */
    public function actionIndex($restaurant_id = null)
    {
        $searchModel = new RestaurantContactSearch();
        $searchModel->restaurant_id = $restaurant_id;
        $restaurant = Restaurant::getById($restaurant_id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * Creates a new RestaurantContact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurant_id = null)
    {
        $model = new RestaurantContactForm();
        $model->getByRestaurantContact();
        $restaurant = Restaurant::getById($restaurant_id);
        $model->restaurant_id = $restaurant_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_id' => $restaurant_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'restaurant' => $restaurant
            ]);
        }
    }

    /**
     * Updates an existing RestaurantContact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $restaurant_id
     * @param integer $contact_id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $restaurantContact = $this->findModel($id);
        $model = new RestaurantContactForm();
        $model->getByRestaurantContact($restaurantContact);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_id' => $model->restaurant_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'restaurant' => $restaurantContact->restaurant
            ]);
        }
    }

    /**
     * Deletes an existing RestaurantContact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $restaurant_id
     * @param integer $contact_id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $restaurantContact = $this->findModel($id);
        $model = new RestaurantContactForm($restaurantContact);
        $model->getByRestaurantContact($restaurantContact);
        $model->record_type = RecordType::Deleted;
        if ($model->save())
        {
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing RestaurantContact model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param integer $restaurant_id
     * @param integer $contact_id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $restaurantContact = $this->findModel($id);
        $model = new RestaurantContactForm($restaurantContact);
        $model->getByRestaurantContact($restaurantContact);
        $model->record_type = RecordType::Active;
        if ($model->save())
        {
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing RestaurantContact model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param integer $restaurant_id
     * @param integer $contact_id
     * @return mixed
     */
    public function actionDeactivate($id)
    {
        $restaurantContact = $this->findModel($id);
        $model = new RestaurantContactForm($restaurantContact);
        $model->getByRestaurantContact($restaurantContact);
        $model->record_type = RecordType::InActive;
        if ($model->save())
        {
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    /**
     * Finds the RestaurantContact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $restaurant_id
     * @param integer $contact_id
     * @return RestaurantContact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestaurantContact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
