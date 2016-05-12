<?php

namespace admin\controllers;

use common\components\identity\RbacHelper;
use common\enums\UserType;
use common\models\Postcode;
use Exception;
use Yii;
use common\models\PostcodeBlacklist;
use admin\controllers\search\PostcodeBlacklistSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostcodeBlacklistController implements the CRUD actions for PostcodeBlacklist model.
 */
class PostcodeBlacklistController extends Controller
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

    private function getPostcodeId($postcode) {
        $result = Yii::$app->locationService->getPostcode($postcode);

        if ($result) {
            return $result['id'];
        }

        return null;
    }

    /**
     * Lists all PostcodeBlacklist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostcodeBlacklistSearch(Yii::$app->request->getImpersonatedClientId());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PostcodeBlacklist model.
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
     * Creates a new PostcodeBlacklist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PostcodeBlacklist();
        $model->client_id = Yii::$app->request->getImpersonatedClientId();

        if ($model->load(Yii::$app->request->post())) {

            $model->postcode_id = $this->getPostcodeId($model->postcode_name);

            if ($model->save()) {
                $this->reloadCache($model);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PostcodeBlacklist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->postcode_name = $model->postcode->postcode;

        if ($model->load(Yii::$app->request->post())) {

            $model->postcode_id = $this->getPostcodeId($model->postcode_name);

            if ($model->save()) {
                $this->reloadCache($model);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PostcodeBlacklist model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing PostcodeBlacklist model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing PostcodeBlacklist model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    private function reloadCache($model)
    {
        $client = Yii::$app->globalCache->getClient($model->client_id);
        Yii::$app->globalCache->addUpdateCacheAction("loadPostcodeBlacklistByClient('".$client['key']."')");
    }

    /**
     * Finds the PostcodeBlacklist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PostcodeBlacklist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostcodeBlacklist::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
