<?php

namespace admin\controllers;

use common\components\identity\RbacHelper;
use common\enums\UserType;
use Yii;
use common\models\MenuCategory;
use admin\controllers\search\MenuCategorySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use \admin\controllers\BaseController;

/**
 * MenuCategoryController implements the CRUD actions for MenuCategory model.
 */
class MenuCategoryController extends BaseController
{
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
                ],
            ],
        ];
    }

    private function saveUploadedFile(\common\models\MenuCategory &$model) {
        $model->file = UploadedFile::getInstance($model, 'image_file_name');
        if (!empty($model->file) && $model->validate(['file'])) {
            $filename = \common\components\IOHelper::getMenuCategoryImagesPath() . $model->file->baseName . '.' . $model->file->extension;
            $model->file->saveAs(Yii::$app->params['images_upload_path'] . $filename);
            \common\components\ImageHelper::createThumb(Yii::$app->params['images_upload_path'] . $filename, Yii::$app->params['restaurant_thumb_width']);
            $model->image_file_name = $model->file->baseName . '.' . $model->file->extension;
        }
    }

    private function getMaxSortOrder()
    {
        return MenuCategory::find()
            ->where(['record_type' => \common\enums\RecordType::Active])
            ->orderBy(['sort_order' => SORT_DESC])
            ->one();
    }

    private function getMinSortOrder()
    {
        return MenuCategory::find()
            ->where(['record_type' => \common\enums\RecordType::Active])
            ->orderBy(['sort_order' => SORT_ASC])
            ->one();
    }

    public function actionUp($id)
    {
        $current_menu_item  = $this->findModel($id);
        $previous_menu_item = MenuCategory::find()
            ->where('sort_order < '. $current_menu_item->sort_order)
            ->andWhere('record_type <> "' . \common\enums\RecordType::Deleted . '"')
            ->orderBy(['sort_order' => SORT_DESC])
            ->one();
        $pr_sort_order                  = $previous_menu_item->sort_order;
        $previous_menu_item->sort_order = $current_menu_item->sort_order;
        $current_menu_item->sort_order  = $pr_sort_order;
        $transaction                    = Yii::$app->db->beginTransaction();

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

    public function actionDown($id)
    {
        $current_menu_item  = $this->findModel($id);
        $previous_menu_item = MenuCategory::find()
            ->where("sort_order > {$current_menu_item->sort_order}")
            ->andWhere('record_type <> "' . \common\enums\RecordType::Deleted . '"')
            ->orderBy(['sort_order' => SORT_ASC])
            ->one();
        $pr_sort_order                  = $previous_menu_item->sort_order;
        $previous_menu_item->sort_order = $current_menu_item->sort_order;
        $current_menu_item->sort_order  = $pr_sort_order;
        $transaction                    = Yii::$app->db->beginTransaction();

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

    /**
     * Lists all MenuCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel    = new MenuCategorySearch();
        $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
        $max_sort_order = $this->getMaxSortOrder();
        $min_sort_order = $this->getMinSortOrder();

        return $this->render('index', [
            'searchModel'    => $searchModel,
            'dataProvider'   => $dataProvider,
            'max_sort_order' => $max_sort_order->sort_order,
            'min_sort_order' => $min_sort_order->sort_order
        ]);
    }

    /**
     * Displays a single MenuCategory model.
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
     * Creates a new MenuCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MenuCategory();
        
        if (Yii::$app->request->isGet){
            $query = [];
            $parts = parse_url(Yii::$app->request->referrer);
            if (isset($parts['query'])){
                parse_str($parts['query'], $query);
                if (isset($query['MenuCategorySearch'])){
                    if (isset($query['MenuCategorySearch']['menu_id'])){
                        $menu_id = $query['MenuCategorySearch']['menu_id'];
                    }
                }
                if (!empty($menu_id)){
                    $model->menu_id = $menu_id;
                }
            }
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $this->saveUploadedFile($model);
            if (Yii::$app->translationLanguage->saveModel($model, $model->menu->client_id)) {
                $this->reloadCache($model);
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
     * Updates an existing MenuCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $this->saveUploadedFile($model);
            if (Yii::$app->translationLanguage->saveModel($model, $model->menu->client_id)) {
                $this->reloadCache($model);
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
     * Deletes an existing MenuCategory model.
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
     * Activates an existing MenuCategory model.
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
     * Deactivates an existing MenuCategory model.
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

    private function reloadCache($model)
    {
        Yii::$app->globalCache->addUpdateCacheAction("loadMenusByClient('".$model->menu->client->key."')");
    }


    /**
     * Finds the MenuCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MenuCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
