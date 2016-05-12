<?php

namespace admin\controllers;

use Yii;
use common\models\BestForItem;
use admin\controllers\search\BestForItemSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Label;
use common\components\GlobalCacheMessageSource;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;

/**
 * BestForItemController implements the CRUD actions for BestForItem model.
 */
class BestForItemController extends BaseController
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
     * Lists all BestForItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BestForItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new BestForItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BestForItem();
        $isSaved = false;
        
        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $model->name_key = uniqid();
            
            try
            {
                $isSaved = $model->save();

                if ($isSaved)
                {
                    $labelName = new Label();
                    $labelName->code = GlobalCacheMessageSource::getLabelName('Best for name ' . $model->id);
                    $labelName->description = 'Best for item '  . $model->id . ' name';
                    $labelName->record_type = \common\enums\RecordType::Active;
                    $isSaved = $isSaved && $labelName->save();

                    if ($isSaved) {
                        $model->name_key = $labelName->code;
                        $isSaved = $model->save();
                    }
                }
                if ($isSaved) {
                    $transaction->commit();
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                }
            }
            catch(Exception $ex){
                $transaction->rollBack();
            }
            
        }
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing BestForItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing BestForItem model.
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
     * Activates an existing BestForItem model.
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
     * Deactivates an existing BestForItem model.
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
     * Finds the BestForItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BestForItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BestForItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
