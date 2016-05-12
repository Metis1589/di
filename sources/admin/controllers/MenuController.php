<?php

namespace admin\Controllers;

use common\components\identity\RbacHelper;
use common\enums\UserType;
use Yii;
use common\models\Menu;
use admin\controllers\search\MenuSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \admin\controllers\BaseController;
/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    array_merge(RbacHelper::allowAllActionsForRoles([
                        UserType::Admin,
                        UserType::ClientAdmin
                    ]),[
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->request->isImpersonated();
                    }])
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $model->client_id = Yii::$app->request->getImpersonatedClientId();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->translationLanguage->saveModel($model, $model->client_id)) {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $tab = 'menu')
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->translationLanguage->saveModel($model, $model->client_id)) {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'tab' => $tab
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
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
            return $this->redirectWithFilters(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing Menu model.
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
            return $this->redirectWithFilters(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing Menu model.
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
            return $this->redirectWithFilters(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    private function reloadCache($model)
    {
        Yii::$app->globalCache->addUpdateCacheAction("loadMenusByClient('".$model->client->key."')");
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
