<?php

namespace admin\controllers;

use common\enums\VoucherDiscountType;
use Exception;
use Yii;
use common\models\Voucher;
use admin\controllers\search\VoucherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \common\enums\UserType;
use common\components\identity\RbacHelper;
use yii\filters\AccessControl;
use \common\enums\VoucherCategory;
use \common\models\VoucherMenuCategory;
use \common\models\VoucherMenuItem;

/**
 * VoucherController implements the CRUD actions for Voucher model.
 */
class VoucherController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowActionsForImpersonatedRolesAndSetting(
                        [
                            'index', 'create'
                        ],
                        [
                            UserType::Admin, UserType::ClientAdmin
                        ]),
                    RbacHelper::allowActionsForVoucher(
                        [
                            'update', 'delete', 'deactivate', 'activate'
                        ],
                        [
                            UserType::Admin, UserType::ClientAdmin
                        ], Yii::$app->request->getFirstParamValue(['id']))
                ],
            ],
        ];
    }

    /**
     * Lists all Voucher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VoucherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Voucher model.
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
     * Creates a new Voucher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Voucher();

        $model->assignment = \common\enums\VoucherAssignmentType::Client;
        $model->discount_type = VoucherDiscountType::Discount;

        if ($model->load(Yii::$app->request->post()) && $this->updateVoucherDiscountValues($model, Yii::$app->request->post('Voucher')) && $model->save()) {
            if ($this->updateVoucherMenuValues($model, Yii::$app->request->post('Voucher'))) {
                $this->reloadCache($model);
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'menu_category_selected' => '',
                    'menu_items_selected' => '',
                    'user_selected' => ''
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'menu_category_selected' => '',
                'menu_items_selected' => '',
                'user_selected' => ''
            ]);
        }
    }

    /**
     * Updates an existing Voucher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->request->isPost) {
            $this->setVoucherAssignment($model);
            $this->setVoucherMenu($model);
        }

        if ($model->load(Yii::$app->request->post()) && $this->updateVoucherAssignment($model, Yii::$app->request->post('Voucher')) && $model->save()) {
            if ($this->updateVoucherMenuValues($model, Yii::$app->request->post('Voucher'))) {
                $this->reloadCache($model);
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'menu_category_selected' => $this->getMenuCategorySelected($model),
                    'menu_items_selected' => $this->getMenuItemsSelected($model),
                    'user_selected' => $model->user_id,
                    'model' => $model,
                ]);
            }

        } else {
            return $this->render('update', [
                'menu_category_selected' => $this->getMenuCategorySelected($model),
                'menu_items_selected' => $this->getMenuItemsSelected($model),
                'user_selected' => $model->user_id,
                'model' => $model,
            ]);
        }
    }

    private function setVoucherAssignment($model)
    {
        if (!empty($model->restaurant_chain_id)) {
            $model->assignment = \common\enums\VoucherAssignmentType::RestaurantChain;
        }
        if (!empty($model->restaurant_group_id)) {
            $model->assignment = \common\enums\VoucherAssignmentType::RestaurantGroup;
        }
        if (!empty($model->restaurant_id)) {
            $model->assignment = \common\enums\VoucherAssignmentType::Restaurant;
        }
        if (!empty($model->user_id)) {
            $model->assignment = \common\enums\VoucherAssignmentType::User;
        }
        if (empty($model->user_id) && empty($model->restaurant_id) && empty($model->restaurant_group_id) && empty($model->restaurant_chain_id)) {
            $model->assignment = \common\enums\VoucherAssignmentType::Client;
        }
    }

    private function setVoucherMenu($model)
    {
        $model->source_menu_category = count($model->sourceVoucherMenuCategories) > 0 ? $model->sourceVoucherMenuCategories[0]->menu_category_id : null;
        $model->target_menu_category = count($model->targetVoucherMenuCategories) > 0 ? $model->targetVoucherMenuCategories[0]->menu_category_id : null;
    }

    private function getMenuCategorySelected($model)
    {
        $menu_category_id_array = [];
        foreach ($model->voucherMenuCategories as $menu_category) {
            $menu_category_id_array[] = $menu_category->menu_category_id;
        }

        return implode(',', $menu_category_id_array);
    }

    private function getMenuItemsSelected($model)
    {
        $menu_items_id_array = [];
        foreach ($model->voucherMenuItems as $menu_item) {
            $menu_items_id_array[] = $menu_item->menu_item_id;
        }

        return implode(',', $menu_items_id_array);
    }


    private function updateVoucherAssignment($model, $post)
    {
        $result = true;

        try {
            $model->restaurant_chain_id = NULL;
            $model->restaurant_group_id = NULL;
            $model->restaurant_id = NULL;

            $result = $this->updateVoucherDiscountValues($model, $post);

            switch ($model->assignment) {
                case \common\enums\VoucherAssignmentType::RestaurantChain:
                    $model->restaurant_chain_id = $post['restaurant_chain_id'];
                    $model->restaurant_group_id = NULL;
                    $model->restaurant_id = NULL;
                    $model->user_id = NULL;
                    break;
                case \common\enums\VoucherAssignmentType::RestaurantGroup:
                    $model->restaurant_group_id = $post['restaurant_group_id'];
                    $model->restaurant_chain_id = NULL;
                    $model->restaurant_id = NULL;
                    $model->user_id = NULL;
                    break;
                case \common\enums\VoucherAssignmentType::Restaurant:
                    $model->restaurant_id = $post['restaurant_id'];
                    $model->restaurant_group_id = NULL;
                    $model->restaurant_chain_id = NULL;
                    $model->user_id = NULL;
                    break;
                case \common\enums\VoucherAssignmentType::User:
                    $model->restaurant_id = NULL;
                    $model->restaurant_group_id = NULL;
                    $model->restaurant_chain_id = NULL;
                    break;
            }
        } catch (Exceprion $e) {
            $result = false;
        }

        return $result;
    }

    private function updateVoucherDiscountValues($model, $post)
    {
        try {
            if ($model->discount_type != null && $model->category == VoucherCategory::MenuItems) {
                if ($model->discount_type == \common\enums\VoucherDiscountType::Price) {
                    $model->price_value = $post['price_value'];
                    $model->discount_value = NULL;
                    $model->value_type = NULL;
                } else {
                    $model->discount_value = $post['discount_value'];
                    $model->value_type = $post['value_type'];
                    $model->price_value = NULL;
                }
            } else {
                $model->discount_type = null;
                if ($model->category == VoucherCategory::Free) {
                    $model->discount_value = null;
                    $model->value_type = null;
                    $model->price_value = null;
                    $model->item_quantity = null;

                }
                if (in_array($model->category, [VoucherCategory::Delivery, VoucherCategory::Wine, VoucherCategory::Food, VoucherCategory::All])) {
                    $model->price_value = null;
                }

                if ($model->category == VoucherCategory::FoodPrice) {
                    $model->item_quantity = null;
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }

    private function updateVoucherMenuValues($model, $post)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $isSaved = true;

        try {
            $isSaved = $this->updateMenuItems($model, $post);

            $isSaved = $this->updateMenuCategories($model, $post);

            if (isset($post['source_menu_category']) && !empty($post['source_menu_category'])) {
                $voucher = VoucherMenuCategory::find()->where(['voucher_id' => $model->id, 'menu_category_type' => \common\enums\VoucherMenuCategoryType::Source, 'record_type' => \common\enums\RecordType::Active])->one();
                if ($voucher == null) {
                    $voucher = new \common\models\VoucherMenuCategory();
                }

                $voucher->voucher_id = $model->id;
                $voucher->menu_category_id = $post['source_menu_category'];
                $voucher->menu_category_type = \common\enums\VoucherMenuCategoryType::Source;
                $voucher->record_type = \common\enums\RecordType::Active;
                $isSaved = $isSaved && $voucher->save();
            }

            if (isset($post['target_menu_category']) && !empty($post['target_menu_category'])) {
                $voucher = VoucherMenuCategory::find()->where(['voucher_id' => $model->id, 'menu_category_type' => \common\enums\VoucherMenuCategoryType::Target, 'record_type' => \common\enums\RecordType::Active])->one();
                if ($voucher == null) {
                    $voucher = new \common\models\VoucherMenuCategory();
                }
                $voucher->voucher_id = $model->id;
                $voucher->menu_category_id = $post['target_menu_category'];
                $voucher->menu_category_type = \common\enums\VoucherMenuCategoryType::Target;
                $voucher->record_type = \common\enums\RecordType::Active;
                $isSaved = $isSaved && $voucher->save();
            }

            if ($isSaved) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }

        return $isSaved;
    }

    private function updateMenuItems($model, $post)
    {
        $isSaved = true;

        $menu_items = [];

        try {

            if (isset($post['menu_item_ids']) && !empty($post['menu_item_ids'])) {
                $menu_items = $post['menu_item_ids'];
            }

            $vouchers = VoucherMenuItem::find()->where(['voucher_id' => $model->id, 'record_type' => \common\enums\RecordType::Active])->all();

            foreach ($vouchers as $voucher) {
                if (!empty($menu_items)) {
                    $neededObject = array_filter($menu_items, function ($e) use (&$voucher) {
                        return $e == $voucher->menu_item_id;
                    });
                }
                if (empty($neededObject)) {
                    $voucher->record_type = \common\enums\RecordType::Deleted;
                    $isSaved = $isSaved && $voucher->save();
                }
            }

            if (!empty($menu_items)) {
                foreach ($menu_items as $menu_item_id) {
                    $voucher = VoucherMenuItem::find()->where(['voucher_id' => $model->id, 'menu_item_id' => $menu_item_id, 'record_type' => \common\enums\RecordType::Active])->one();
                    if ($voucher == null) {
                        $voucher = new \common\models\VoucherMenuItem();
                    }

                    $voucher->voucher_id = $model->id;
                    $voucher->menu_item_id = $menu_item_id;
                    $voucher->record_type = \common\enums\RecordType::Active;
                    $isSaved = $isSaved && $voucher->save();
                }
            }

        } catch (Exception $ex) {
            return false;
        }

        return $isSaved;
    }

    private
    function updateMenuCategories($model, $post)
    {
        $isSaved = true;

        try {
            if (isset($post['menu_category']) && !empty($post['menu_category'])) {
                $vouchers = \common\models\VoucherMenuCategory::find()->where(['voucher_id' => $model->id, 'menu_category_type' => null, 'record_type' => \common\enums\RecordType::Active])->all();

                $menu_category = $post['menu_category'];

                foreach ($vouchers as $voucher) {
                    if (!empty($menu_category)) {
                        $neededObject = array_filter($menu_category, function ($e) use (&$voucher) {
                            return $e == $voucher->menu_category_id;
                        });
                    }
                    if (empty($neededObject)) {
                        $voucher->record_type = \common\enums\RecordType::Deleted;
                        $isSaved = $isSaved && $voucher->save();
                    }
                }

                if (!empty($menu_category)) {
                    foreach ($menu_category as $menu_category_id) {
                        $voucher = VoucherMenuCategory::find()->where(['voucher_id' => $model->id, 'menu_category_id' => $menu_category_id, 'record_type' => \common\enums\RecordType::Active])->one();
                        if ($voucher == null) {
                            $voucher = new VoucherMenuCategory();
                        }

                        $voucher->voucher_id = $model->id;
                        $voucher->menu_category_id = $menu_category_id;
                        $voucher->menu_category_type = null;
                        $voucher->record_type = \common\enums\RecordType::Active;
                        $isSaved = $isSaved && $voucher->save();
                    }
                }
            }
        } catch (Exception $ex) {
            return false;
        }

        return $isSaved;
    }

    /**
     * Deletes an existing Voucher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public
    function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->record_type = \common\enums\RecordType::Deleted;
        if ($model->save(false)) {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        }

        throw new Exception('Error saving model with record_type = Deleted');
    }

    /**
     * Activates an existing Voucher model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public
    function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = \common\enums\RecordType::Active;
        if ($model->save(false)) {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        }

        throw new Exception('Error saving model with record_type = Active');
    }

    /**
     * Deactivates an existing Voucher model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public
    function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = \common\enums\RecordType::InActive;
        if ($model->save(false)) {
            $this->reloadCache($model);
            return $this->redirect(['index']);
        }

        throw new Exception('Error saving model with record_type = Inactive');
    }

    private
    function reloadCache($model)
    {
        Yii::$app->globalCache->addUpdateCacheAction("loadVouchersByClient('" . $model->client->key . "')");
    }

    /**
     * Finds the Voucher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Voucher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Voucher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
