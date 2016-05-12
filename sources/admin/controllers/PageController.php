<?php

namespace admin\controllers;

use Yii;
use common\models\Page;
use common\behaviors\PublishedStatusBehavior;
use admin\controllers\search\PageSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Inflector;
use common\enums\RecordType;

class PageController extends \yii\web\Controller
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
        $searchModel = new PageSearch();
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
    public function actionCreate($language = null, $sourceId = null, $empty =false, $backUrl = null)
    {
        $model = new Page();
        $model->record_type = \common\enums\RecordType::Active;
        $model->language_id = $language ? $language : Yii::$app->globalCache->getLanguageId(Yii::$app->translationLanguage->language);
        $model->client_id = Yii::$app->request->getImpersonatedClientId();

        if($language && $sourceId && !$empty) {
            $sourceModel = $this->findModel($sourceId);
            $model->language_id = $language;
            $model->translation_id = $sourceModel->translation_id;
            $model->title = $sourceModel->title;
            $model->slug = $sourceModel->slug;
            $model->published = $sourceModel->published;
            $model->content = $sourceModel->content;
            $model->description = $sourceModel->description;
            $model->robots = $sourceModel->robots;
            $model->open_from = $sourceModel->open_from;
            $model->open_to = $sourceModel->open_to;
        }
        elseif($language && $sourceId && $empty){
            $sourceModel = $this->findModel($sourceId);
            $model->language_id = $language;
            $model->translation_id = $sourceModel->translation_id;
        }
        else {
            $sourceModel = null;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'sourceModel' => $sourceModel
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
            $this->reloadCache($model);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
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
        if ($model->save(false,['record_type']))
        {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }


    /**
     * 
     * Marks navigation element as published.
     * @param int $id ID of the navigation element.
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = RecordType::Active;
        if ($model->save(false,array('record_type'))) {
            $this->reloadCache($model);
        }
        return $this->redirect('index');
    }

    /**
     * 
     * Marks navigation element as unpublished.
     * @param int $id ID of the navigation element.
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = RecordType::InActive;
        if ($model->save(false,array('record_type'))) {
            $this->reloadCache($model);
        }

        return $this->redirect('index');
    }
    
    
    /**
     * Returns autogenerated slug for given title.
     * @param string title of the page
     */
    public function actionSlugtip($title){
        echo Inflector::slug(TransliteratorHelper::process($title)); exit();
    }

    private function reloadCache($model)
    {
        Yii::$app->globalCache->addUpdateCacheAction("loadPagesByClient('".$model->client->key."')");
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
        $model = Page::findOne($id);
        if (($model = Page::findOne($id)) !== null && $model->client_id == Yii::$app->request->getImpersonatedClientId()) {
            return $model;
        } else {
            throw new NotFoundHttpException(T::e('The requested page does not exist.'));
        }
    }

}
