<?php

namespace admin\controllers;

use common\enums\RecordType;
use common\models\RestaurantChain;
use Yii;
use common\models\RestaurantGroup;
use admin\controllers\search\RestaurantGroupSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Label;
use common\components\GlobalCacheMessageSource;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
/**
 * RestaurantGroupController implements the CRUD actions for RestaurantGroup model.
 */
class RestaurantGroupController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowActionsForRoles(['index','create', 'update', 'delete','activate','deactivate'],[
                        UserType::Admin,
                        UserType::ClientAdmin,
                        UserType::RestaurantChainAdmin
                    ]),
                    RbacHelper::allowActionsForRoles(['get-tree-view'],[
                       '@'
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
     * Lists all RestaurantGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RestaurantGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new RestaurantGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestaurantGroup();

        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $model->name_key = uniqid();
            
            $isSaved = true;
            try {
                
                $isSaved = $model->save();

                if ($isSaved)
                {
                    $labelName = new Label();
                    $labelName->code = GlobalCacheMessageSource::getLabelName('Restaurant Group ' . $model->id);
                    $labelName->description = 'Label for Restaurant Group '  . $model->id . ' name';
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
            } catch (Exception $ex) {
                 $transaction->rollBack();
            }
            
        }
            
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing RestaurantGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/restaurant-chain/update','id' => $model->restaurant_chain_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RestaurantGroup model.
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
     * Activates an existing RestaurantGroup model.
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
     * Deactivates an existing RestaurantGroup model.
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

    public function actionGetTreeView()
    {
        $chains = RestaurantChain::getTree(Yii::$app->request->isImpersonated() ? Yii::$app->request->getImpersonatedClientId() : null);
        return $this->renderPartial('_tree_view',['chains' => $chains]);
    }

    /**
     * Finds the RestaurantGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RestaurantGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestaurantGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
