<?php

namespace admin\controllers;

use Yii;
use common\models\EmailTemplate;
use common\components\language\T;
use admin\controllers\search\EmailtemplateSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use common\enums\RecordType;

class EmailtemplateController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowAllActionsForImpersonatedRoles([UserType::Admin, UserType::ClientAdmin])
                    ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'delete'],
                    'bulk-delete' => ['post'],
                    'delete-file' => ['post'],
                    'publish' => ['post'],
                    'unpublish' => ['post'],
                    'ordering' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailtemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string|null $language
     * @param string|null $sourceId
     * @param string|null $backUrl
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $model = new EmailTemplate();
        $model->record_type = \common\enums\RecordType::Active;
        $model->client_id = Yii::$app->request->getImpersonatedClientId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string|null $backUrl
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = EmailTemplate::findOne($id);
        if (($model = EmailTemplate::findOne($id)) !== null && $model->client_id == Yii::$app->request->getImpersonatedClientId()) {
            return $model;
        } else {
            throw new NotFoundHttpException(T::e('The requested page does not exist.'));
        }
    }

}
