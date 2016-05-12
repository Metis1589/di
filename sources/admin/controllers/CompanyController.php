<?php

namespace admin\controllers;

use Yii;
use common\models\Company;
use admin\controllers\search\CompanySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowActionsForImpersonatedRolesAndSetting([
                        'index', 'create'
                    ],[
                        UserType::Admin, UserType::ClientAdmin
                    ]),
                    RbacHelper::allowActionsForCompanyUser([
                        'update', 'delete', 'deactivate', 'activate'
                    ], [
                        UserType::Admin, UserType::ClientAdmin
                    ], Yii::$app->request->getFirstParamValue(['id']))
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
        $model->prepareRelationRecords(Yii::$app->request->post());
        $model->client_id = Yii::$app->request->getImpersonatedClientId();

        if ($model->load(Yii::$app->request->post()) && $model->saveCompanyDetails(Yii::$app->request->post())) {
            return $this->redirect(['index']);
        } else {
            $redirect = false;
            if (Yii::$app->request->post()) {
                $redirect = $model->saveCompanyDetails(Yii::$app->request->post());
            }
            if ($redirect) {
                return $this->redirect(['update','id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->prepareRelationRecords(Yii::$app->request->post());
        $model->client_id = Yii::$app->request->getImpersonatedClientId();

        if ($model->load(Yii::$app->request->post()) && $model->saveCompanyDetails(Yii::$app->request->post())) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Company model.
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
     * Activates an existing Company model.
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
     * Deactivates an existing Company model.
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
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
