<?php

namespace admin\controllers;

use common\enums\CookieName;
use common\models\EmailTemplate;
use Exception;
use Yii;
use common\models\Client;
use admin\controllers\search\ClientSearch;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends BaseController
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
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->key = uniqid() . uniqid();
            
            $transaction = Yii::$app->db->beginTransaction();
            try 
            {
                $isSaved = $model->save();
                $isSaved = $isSaved && EmailTemplate::fillDefaultEmailTemplates($model->id);
                if($isSaved)
                {
                    $transaction->commit();
                    $this->reloadCache($model);
                }
                else
                {
                    $transaction->rollBack();
                }
            } 
            catch (Exception $ex) {
                $transaction->rollBack();
                Yii::error($ex->__toString());
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if($isSaved){
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
//        if (Yii::$app->request->isImpersonated() || Yii::$app->user->identity->user_type == UserType::ClientAdmin) {
//            $id = Yii::$app->request->getImpersonatedClientId();
//        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
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
            $this->reloadCache($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing Client model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
     * Deactivates an existing Client model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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

    public function actionImpersonate($id)
    {
        Yii::$app->request->impersonateClient($this->findModel($id));
        $this->redirect('/order/index');
    }

    public function actionImpersonateClear()
    {
        Yii::$app->request->clearImpersonatedClient();

        return $this->redirect('/');
    }

    private function reloadCache($model) {
        Yii::$app->globalCache->addUpdateCacheAction('loadClients()');
        Yii::$app->globalCache->addUpdateCacheAction("loadRestaurantsByClientKey('$model->key')");
        Yii::$app->globalCache->addUpdateCacheAction("loadCompaniesByClient($model->id)");
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
