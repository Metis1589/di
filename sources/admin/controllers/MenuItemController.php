<?php

namespace admin\Controllers;

use common\components\identity\RbacHelper;
use common\enums\UserType;
use common\models\MenuOption;
use Exception;
use Yii;
use common\models\MenuItem;
use admin\controllers\search\MenuItemSearch;
use yii\filters\AccessControl;
use admin\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * MenuItemController implements the CRUD actions for MenuItem model.
 */
class MenuItemController extends BaseController
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    array_merge(RbacHelper::allowAllActionsForRoles([
                        UserType::Admin,
                        UserType::ClientAdmin
                    ]),[
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->request->isImpersonated();
                        }])
                ],

            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'save-menu-allergies' => ['post']
  //                  'save-menu-options' => ['post'],
                ],
            ],
        ];
    }

    private function saveUploadedFile(\common\models\MenuItem &$model) {
        $model->file = UploadedFile::getInstance($model, 'image_file_name');
        if (!empty($model->file) && $model->validate(['file'])) {
            $filename = \common\components\IOHelper::getMenuItemImagesPath() . $model->file->baseName . '.' . $model->file->extension;
            $model->file->saveAs(Yii::$app->params['images_upload_path'] . $filename);
            \common\components\ImageHelper::createThumb(Yii::$app->params['images_upload_path'] . $filename, Yii::$app->params['restaurant_thumb_width']);
            $model->image_file_name = $model->file->baseName . '.' . $model->file->extension;
            $model->save(false,['image_file_name']);
        }
    }
    /**
     * Lists all MenuItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $max_sort_order = $this->getMaxSortOrder();
        $min_sort_order = $this->getMinSortOrder();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'max_sort_order' => $max_sort_order->sort_order,
            'min_sort_order' => $min_sort_order->sort_order
        ]);
    }

    /**
     * Displays a single MenuItem model.
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
     * Creates a new MenuItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuItem();
        
        $max_sort_order = $this->getMaxSortOrder();
        
        if (Yii::$app->request->isGet){
            $query = [];
            $parts = parse_url(Yii::$app->request->referrer);
            if (isset($parts['query'])){
                parse_str($parts['query'], $query);
                if (isset($query['MenuItemSearch'])){
                    if (isset($query['MenuItemSearch']['menu_category_id'])){
                        $menu_category_id = $query['MenuItemSearch']['menu_category_id'];
                    }
                    Yii::$app->session['menu_item_filters'] = $query;
                }
                if (!empty($menu_category_id)){
                    $model->menu_category_id = $menu_category_id;
                }
            }
        }
        
        
        if ($model->load(Yii::$app->request->post())) {
            
            $model->sort_order = $max_sort_order->sort_order + 1;
            
            if (Yii::$app->translationLanguage->saveModel($model, $model->menuCategory->menu->client_id)) {
                
                 $this->saveUploadedFile($model);
                
                if (count($model->getErrors()) > 0){
                    return $this->render('create', [
                            'model' => $model,
                    ]);
                }
                $this->reloadCache($model);
                $menu_item_filters = Yii::$app->session['menu_item_filters'];
                if (!empty($menu_item_filters)){
                    $params = html_entity_decode(http_build_query($menu_item_filters));
                    return $this->redirect(['index?'.$params]);
                } 
                return $this->redirect(['index']);
            }
            else {
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
    
    private function getMaxSortOrder(){
       return MenuItem::find()->where(['record_type' => \common\enums\RecordType::Active])->orderBy(['sort_order' => SORT_DESC])->one();
    }
    
    private function getMinSortOrder(){
       return MenuItem::find()->where(['record_type' => \common\enums\RecordType::Active])->orderBy(['sort_order' => SORT_ASC])->one();
    }

    /**
     * Updates an existing MenuItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @param string $tab
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionUpdate($id, $tab = 'menu_item') {
        $model = $this->findModel($id);

        $selected_allergies = $this->findSelectedAllergies($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            
            if (Yii::$app->translationLanguage->saveModel($model, $model->menuCategory->menu->client_id)) {
                
                $this->saveUploadedFile($model);
                
                if (count($model->getErrors()) > 0){
                    return $this->render('update', [
                            'model' => $model,
                            'tab' => 'menu_item',
                            'selected_allergies' => $selected_allergies
                    ]);
                }
                $this->reloadCache($model);
                return $this->redirect(['/menu-item/update', 'id' => $model->id, 'tab' => 'menu_item']);
            } else {
                return $this->render('update', [
                            'model' => $model,
                            'tab' => 'menu_item',
                            'selected_allergies' => $selected_allergies
                ]);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'tab' => $tab,
                        'selected_allergies' => $selected_allergies
            ]);
        }
    }

    /**
     * Deletes an existing MenuItem model.
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
            $this->reloadCache($model);
            return $this->redirectWithFilters(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing MenuItem model.
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
            $this->reloadCache($model);
            return $this->redirectWithFilters(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing MenuItem model.
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
            $this->reloadCache($model);
            return $this->redirectWithFilters(['index']);
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }
    
    public function actionUp($id){
        
        $current_menu_item = $this->findModel($id);
        $previous_menu_item = MenuItem::find()->where('sort_order <'. $current_menu_item->sort_order.' and record_type<>"'. \common\enums\RecordType::Deleted.'"')->orderBy(['sort_order' => SORT_DESC])->one();
        
        $pr_sort_order = $previous_menu_item->sort_order;
        
        $previous_menu_item->sort_order = $current_menu_item->sort_order;
        $current_menu_item->sort_order = $pr_sort_order;
        
         $transaction = Yii::$app->db->beginTransaction();
        try {
            
            $is_saved = $current_menu_item->save();
            $is_saved = $is_saved && $previous_menu_item->save();

            if ($is_saved) {
                $transaction->commit();
                $this->reloadCache($current_menu_item);
            } else {
                $transaction->rollBack();
                return false;
            }
            
        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }
        
        return $this->redirectWithFilters(['index']);
    }
    
    public function actionDown($id){
        
        $current_menu_item = $this->findModel($id);
        $previous_menu_item = MenuItem::find()->where('sort_order >'. $current_menu_item->sort_order.' and record_type<>"'. \common\enums\RecordType::Deleted.'"')->orderBy(['sort_order' => SORT_ASC])->one();
        
        $pr_sort_order = $previous_menu_item->sort_order;
        
        $previous_menu_item->sort_order = $current_menu_item->sort_order;
        $current_menu_item->sort_order = $pr_sort_order;
        
         $transaction = Yii::$app->db->beginTransaction();
        try {
            
            $is_saved = $current_menu_item->save();
            $is_saved = $is_saved && $previous_menu_item->save();

            if ($is_saved) {
                $transaction->commit();
                $this->reloadCache($current_menu_item);
            } else {
                $transaction->rollBack();
                return false;
            }
            
        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }
        
        return $this->redirectWithFilters(['index']);
    }
    
    public function actionGetMenuOptions($id){
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return MenuOption::getTreeAsArray($id);

        echo json_encode(MenuOption::getTreeAsArray($id), JSON_NUMERIC_CHECK );
        die();
    }

    public function actionSaveMenuAllergies($id){
        $menu_item_id = $id;

        $existing_allergies = \common\models\MenuItemAllergy::findAll(['menu_item_id' => $menu_item_id, 'record_type' => \common\enums\RecordType::Active]);

        $allergy_list = Yii::$app->request->post('checkBoxList');

        $transaction = Yii::$app->db->beginTransaction();
        try {
        
            $is_saved = true;   
            
            foreach ($existing_allergies as $allery) {
                if (!empty($allergy_list)) {
                    $neededObject = array_filter($allergy_list, function ($e) use (&$allery) {
                        return $e == $allery->allergy_id;
                    });
                }
                if (empty($neededObject)){
                    $allery->record_type = \common\enums\RecordType::Deleted;
                    $is_saved = $is_saved && $allery->save();
                }
            }

            if (!empty($allergy_list)) {

                foreach ($allergy_list as $value) {
                    $neededObject = array_filter($existing_allergies, function ($e) use (&$value) {
                        return $e->allergy_id == $value;
                    });

                    if (empty($neededObject)) {
                        if (($model = \common\models\MenuItemAllergy::findOne(['menu_item_id' => $menu_item_id, 'allergy_id' => $value])) !== null) {
                            $model->record_type = \common\enums\RecordType::Active;
                            $is_saved = $is_saved && $model->save();
                        } else {
                            $menuItemAllergy = new \common\models\MenuItemAllergy();
                            $menuItemAllergy->menu_item_id = $menu_item_id;
                            $menuItemAllergy->allergy_id = $value;
                            $menuItemAllergy->record_type = \common\enums\RecordType::Active;
                            $is_saved = $is_saved && $menuItemAllergy->save();
                        }
                    }
                }
            }

            if ($is_saved) {
                $transaction->commit();
                $this->reloadCache($this->findModel($menu_item_id));
            } else {
                $transaction->rollBack();
            }
        }
        catch (Exception $ex) {
            $transaction->rollBack();
        }


        return $this->redirect(['/menu-item/update', 'id' => $id, 'tab' => 'allergies']);
    }

    public function actionSaveMenuOptions($id) {
        
        $options = json_decode(Yii::$app->request->rawBody, true);

        $result = MenuOption::saveTreeAsArray($id, $options);

        if ($result) {
            $this->reloadCache(MenuItem::findOne($id));
        }

        return $result;
    }

    private function findSelectedAllergies($id){
        $items = yii\helpers\ArrayHelper::map(\common\models\MenuItemAllergy::findAll(['menu_item_id' => $id, 'record_type' => \common\enums\RecordType::Active]), 'allergy_id', 'menu_item_id');
        
        return array_keys($items);
    }

    private function reloadCache($model){
        Yii::$app->globalCache->addUpdateCacheAction("loadMenusByClient('".$model->menuCategory->menu->client->key."')");
    }


    /**
     * Finds the MenuItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MenuItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
