<?php

namespace admin\Controllers;

use common\components\identity\RbacHelper;
use common\enums\UserType;
use Yii;
use common\models\MenuAssignment;
use admin\Controllers\Search\MenuAssignmentSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuAssignmentController implements the CRUD actions for MenuAssignment model.
 */
class MenuAssignmentController extends Controller
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
                        UserType::RestaurantChainAdmin,
                        UserType::RestaurantGroupAdmin,
                        UserType::RestaurantAdmin,
                        UserType::RestaurantTeam
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
     * Lists all MenuAssignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuAssignmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionForm($model) {
        $assignments = $this->getMenuAssignments($model);

        return $this->renderPartial('_form', [
                    'assignments' => $assignments,
                    'id' => $model->id,
                    'model' => get_class($model)
        ]);
    }

    private function getMenuAssignments($model) {
        $menus = \common\models\Menu::find()->where('record_type <> "' . \common\enums\RecordType::Deleted . '"');
        if (Yii::$app->request->isImpersonated()) {
            $menus->andFilterWhere(['=', 'menu.client_id', Yii::$app->request->getImpersonatedClientId()]);
        }
        
        $menus = $menus->all();
        
        $menu_assignment = null;

        if ($model instanceof \common\models\RestaurantChain) {
            $restaurant_chain_assignment = MenuAssignment::find()->where(['restaurant_chain_id' => $model->id])->all();
            if (!empty($restaurant_chain_assignment)) {
                $menu_assignment = $restaurant_chain_assignment;
            }
        }

        if ($model instanceof \common\models\RestaurantGroup) {
            $restaurant_group_assignment = MenuAssignment::find()->where(['restaurant_group_id' => $model->id])->all();
            if (!empty($restaurant_group_assignment)) {
                $menu_assignment = $restaurant_group_assignment;
            }
        }

        if ($model instanceof \common\models\Restaurant) {
            $restaurant_assignment = MenuAssignment::find()->where(['restaurant_id' => $model->id])->all();
            if (!empty($restaurant_assignment)) {
                $menu_assignment = $restaurant_assignment;
            }
        }

        if ($model instanceof \common\models\Client) {
            $client_assignment = MenuAssignment::find()->where(['client_id' => $model->id])->all();
            if (!empty($client_assignment)) {
                $menu_assignment = $client_assignment;
            }
        }

        foreach ($menus as $menu) {
            if (is_array($menu_assignment) && count($menu_assignment) > 0) {
                $assignmentObject = \admin\common\ArrayHelper::searchRowInArArray($menu_assignment, ['menu_id' => $menu->id]);
            } else {
                $assignmentObject = null;
            }

            if (!empty($assignmentObject)) {
                $menu->record_type = $assignmentObject->record_type;
            } else {
                $menu->record_type = \common\enums\RecordType::Deleted;
            }
        }

        return $menus;
    }

    /**
     * Creates a new MenuAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuAssignment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MenuAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->getModel($id);

        if (Yii::$app->request->isPost) {
            if ($this->saveMenuAssignment($model)) {
                Yii::$app->session->setFlash('success', Yii::t('label', 'Menu Assignments were successfully updated.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('label', 'Something went wrong.'));
            }
            return $this->redirectMenu($model);
            
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    private function getModel($id){
        if (Yii::$app->request->isPost) {
            $class = Yii::$app->request->post('modelClass');
            return $this->findModel($id, $class);
        }
    }
    
    private function redirectMenu($model){
        $class = get_class($model);
        
        if ($class == 'common\models\RestaurantChain'){
            return $this->redirect(['/restaurant-chain/update', 'id' => $model->id]);
        }
        
        if ($class == 'common\models\RestaurantGroup'){
            return $this->redirect(['/restaurant-group/update', 'id' => $model->id]);
        }
        
        if ($class == 'common\models\Restaurant'){
            return $this->redirect(['/restaurant/update', 'id' => $model->id]);
        }
        
        return $this->redirect(['/client/update', 'id' => $model->id]);
    }
    
    private function saveMenuAssignment($model) {
        $menus = Yii::$app->request->post('Menu');
        $class = Yii::$app->request->post('modelClass');

        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($menus as $key => $menu) {
                $menuAssignment = $this->getMenuAssignment($model, $class, $key);
                if (!empty($menuAssignment)) {
                    $menuAssignment->record_type = $menu['record_type'];
                    $is_saved = $menuAssignment->save();
                } else {
                    $field = $this->getMenuAssignmentField($class);
                    $menuAssignment = new MenuAssignment();
                    $menuAssignment->$field = $model->id;
                    $menuAssignment->menu_id = $key;
                    $menuAssignment->record_type = $menu['record_type'];
                    $is_saved = $menuAssignment->save();
                }

                if (!$is_saved) {
                    $transaction->rollBack();
                    return false;
                }

            }
            $transaction->commit();

            $client = Yii::$app->globalCache->getClientById(Yii::$app->request->getImpersonatedClientId());
            $this->reloadCache($client['key']);

        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }



        return true;
    }

    private function reloadCache($client_key) {
        Yii::$app->globalCache->addUpdateCacheAction("loadMenusByClient('".$client_key."')");
    }

    private function getMenuAssignmentField($class){
        if ($class == 'common\models\RestaurantChain'){
            return 'restaurant_chain_id';
        }
        
        if ($class == 'common\models\RestaurantGroup'){
            return 'restaurant_group_id';
        }
        
        if ($class == 'common\models\Restaurant'){
            return 'restaurant_id';
        }
        
        return 'client_id';
        
    }
    private function getMenuAssignment($model, $class, $menu_id){
        $field = $this->getMenuAssignmentField($class);
        
        return MenuAssignment::find()->where([$field => $model->id, 'menu_id' => $menu_id])->one();
    }

    /**
     * Deletes an existing MenuAssignment model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing MenuAssignment model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing MenuAssignment model.
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
            return $this->redirect(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    /**
     * Finds the MenuAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MenuAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $class)
    {
        if (($model = $class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
