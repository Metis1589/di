<?php

namespace admin\controllers;

use Exception;
use Yii;
use common\models\AddressBase;
use admin\controllers\search\AddressBaseSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Label;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
/**
 * AddressBaseController implements the CRUD actions for AddressBase model.
 */
class AddressBaseController extends BaseController
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
                    'update-multiple' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AddressBase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AddressBaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new AddressBase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AddressBase();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AddressBase model.
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
            $label_name = Label::findOne(['code' => $model->name]);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AddressBase model.
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
     * Activates an existing AddressBase model.
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
     * Deactivates an existing AddressBase model.
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

    public function actionUpdateMultiple()
    {
        $ids = Yii::$app->request->post('ids');
        $property = Yii::$app->request->post('property');
        $value = Yii::$app->request->post('value');
        if (!$ids) {
            return;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            foreach($ids as $id) {
                $model = $this->findModel($id);
                $model->$property = $value;
                if (!$model->save())
                {
                    throw new Exception('Error saving model with record_type = Inactive');
                }
            }
            $transaction->commit();
            return $this->redirectToPreviousPage();
        }
        catch (Exception $ex) {
            $transaction->rollBack();
        }


        throw new Exception('Error saving model with record_type = Inactive');
    }

    /**
     * Finds the AddressBase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AddressBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AddressBase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
