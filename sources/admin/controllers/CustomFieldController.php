<?php

namespace admin\controllers;

use common\components\identity\RbacHelper;
use common\enums\UserType;
use common\models\MenuItem;
use common\models\Restaurant;
use common\models\Client;
use common\models\RestaurantDeliveryCharge;
use Exception;
use Yii;
use common\models\CustomField;
use admin\controllers\search\CustomFieldSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomFieldController implements the CRUD actions for CustomField model.
 */
class CustomFieldController extends Controller
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
     * Lists all CustomField models.
     * @return mixed
     */
    public function actionIndex($type)
    {
        $searchModel = new CustomFieldSearch(Yii::$app->request->getImpersonatedClientId(), $type);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type
        ]);
    }

    public function actionForm($model) {
        $fields = CustomField::getKeyValues(
            Yii::$app->request->getImpersonatedClientId(),
            $model instanceof Restaurant ? $model->id : null,
            $model instanceof MenuItem ? $model->id : null,
            $model instanceof RestaurantDeliveryCharge ? $model->id : null,
            true
        );

        return $this->renderPartial('_form_multiple', [
            'fields' => $fields,
            'id' => $model->id,
            'model' => get_class($model)
        ]);
    }

    public function actionUpdateMultiple($id) {
        $values = Yii::$app->request->post('CustomFieldValue');
        $class = Yii::$app->request->post('modelClass');

        $fields = CustomField::getKeyValues(
            Yii::$app->request->getImpersonatedClientId(),   
            $class == 'common\models\Restaurant' ? $id : null,
            $class == 'common\models\MenuItem' ? $id : null,
            $class == 'common\models\RestaurantDeliveryCharge' ? $id : null,
            true
        );
        if (count($fields) > 0){
            foreach ($values as $key => $value) {
                $fields[$key]->customFieldValue->value = $value['value'];
                $fields[$key]->customFieldValue->save();
            }
        }

        if ($class == 'common\models\Client'){
            return $this->redirect(['/client/update', 'id' => $id]);
        } 
        else if ($class == 'common\models\Restaurant'){
            return $this->redirect(['/restaurant/update', 'id' => $id]);
        }
        else if ($class == 'common\models\MenuItem'){
            return $this->redirect(['/menu-item/update', 'id' => $id]);
        }
    }

    /**
     * Creates a new CustomField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type)
    {
        $model = new CustomField();
        $model->type = $type;
        $model->client_id = Yii::$app->request->getImpersonatedClientId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'type' => $model->type]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'type' => $type
            ]);
        }
    }

    /**
     * Updates an existing CustomField model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'type' => $model->type]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'type' => $model->type
            ]);
        }
    }

    /**
     * Deletes an existing CustomField model.
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
            return $this->redirect(['index', 'type' => $model->type]);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing CustomField model.
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
            return $this->redirect(['index', 'type' => $model->type]);
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing CustomField model.
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
            return $this->redirect(['index', 'type' => $model->type]);
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    /**
     * Finds the CustomField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CustomField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomField::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
