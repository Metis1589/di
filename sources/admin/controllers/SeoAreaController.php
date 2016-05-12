<?php

namespace admin\controllers;

use common\components\identity\RbacHelper;
use common\enums\RecordType;
use common\enums\UserType;
use Yii;
use common\models\SeoArea;
use admin\controllers\search\SeoAreaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SeoAreaController implements the CRUD actions for SeoArea model.
 */
class SeoAreaController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowAllActionsForRoles([
                        UserType::Admin,
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
     * Lists all SeoArea models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SeoAreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SeoArea model.
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
     * Creates a new SeoArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SeoArea();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->reloadCache();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SeoArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->reloadCache();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deactivate an existing SeoArea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = RecordType::InActive;
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }

        throw new Exception('Error saving model with record_type = Inactive');
    }

    /**
     * Activate an existing SeoArea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = RecordType::Active;
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }

        throw new Exception('Error saving model with record_type = Active');
    }

    /**
     * Deletes an existing SeoArea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->record_type = RecordType::Deleted;
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }

        throw new Exception('Error saving model with record_type = Deleted');
    }

    private function reloadCache()
    {
        Yii::$app->globalCache->addUpdateCacheAction("loadSeoAreas()");
    }

    /**
     * Finds the SeoArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SeoArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SeoArea::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
