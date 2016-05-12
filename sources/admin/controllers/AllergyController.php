<?php

namespace admin\Controllers;

use Yii;
use common\models\Allergy;
use admin\controllers\search\AllergySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AllergyController implements the CRUD actions for Allergy model.
 */
class AllergyController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * save uploaded file
     * @param Allergy $model
     */
    private function saveUploadedFile(\common\models\Allergy &$model) {
        $model->file = UploadedFile::getInstance($model, 'image_file_name');
        if (!empty($model->file) && $model->validate(['file'])) {
            $filename = \common\components\IOHelper::getAllergyImagesPath() . $model->file->baseName . '.' . $model->file->extension;
            $model->file->saveAs(Yii::$app->params['images_upload_path'] . $filename);
            \common\components\ImageHelper::createThumb(Yii::$app->params['images_upload_path'] . $filename, Yii::$app->params['restaurant_thumb_width']);
            $model->image_file_name = $model->file->baseName . '.' . $model->file->extension;
        }
    }
    
    
    /**
     * Lists all Allergy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AllergySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Allergy model.
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
     * Creates a new Allergy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Allergy();

        if ($model->load(Yii::$app->request->post())) {
            $this->saveUploadedFile($model);
            if (Yii::$app->translationLanguage->saveModel($model, null)) {
                $this->reloadCache();
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Allergy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $this->saveUploadedFile($model);
            if (Yii::$app->translationLanguage->saveModel($model, null)) {
                $this->reloadCache();
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Allergy model.
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
            $this->reloadCache();
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing Allergy model.
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
            $this->reloadCache();
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing Allergy model.
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
            $this->reloadCache();
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    private function reloadCache()
    {
        Yii::$app->globalCache->addUpdateCacheAction('loadMenus()');
        Yii::$app->globalCache->addUpdateCacheAction('loadAllergies()');
    }

    /**
     * Finds the Allergy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Allergy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Allergy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
