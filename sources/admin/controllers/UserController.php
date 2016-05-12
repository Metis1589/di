<?php

namespace admin\controllers;

use admin\common\ArrayHelper;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use Yii;
use common\models\User;
use admin\controllers\search\UserSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \common\enums\RecordType;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    public $enableCsrfValidation = false;

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
                    RbacHelper::allowActionsForRestaurantUser(['list','popup-form', 'get-users'], Yii::$app->user->isGuest ? null : Yii::$app->user->identity->restaurant_id)
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'save' => ['post'],
                ],
            ],
        ];
    }

    public function actionGetUsers($model, $id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $types = implode('\',\'', array_keys(UserType::getUserTypes($model)));
        $users = User::find()->where('record_type <> "'.RecordType::Deleted.'" && user_type IN(\'' . $types . '\') && '.$model.'_id = '.$id)->all();
        foreach($users as &$user) {
            $user->password = '';
        }
        $this->renderJson(ArrayHelper::convertArToArray($users));
    }

    public function actionGetCompanyUsers($role, $id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($role != UserType::CorporateMember) {
            $rawUsers = User::find()
                ->where('record_type <> "'.RecordType::Deleted.'"')
                ->andWhere('user_type = "' . $role . '"')
                ->andWhere('user.company_id = ' . $id)
                ->all();
        } else {
            $rawUsers = User::find()
                ->select('user.*, company_user_group.name AS group_name, user.title AS user_title')
                ->with('primaryAddress')
                ->leftJoin('company_user_group', 'company_user_group.id = user.company_user_group_id')
                ->where('user.record_type <> "'.RecordType::Deleted.'"')
                ->andWhere('user_type = "' . $role . '"')
                ->andWhere('user.company_id = ' . $id)
                ->all();
        }


        $users = ArrayHelper::convertArToArray($rawUsers);
        foreach ($users as $id => &$user) {
            $user['group_name'] = $rawUsers[$id]['group_name'];
            $user['user_title'] = $rawUsers[$id]['user_title'];
            unset($user['password']);
        }
        $this->renderJson($users);
    }

    public function actionGetUserAddresses($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $addresses = \common\models\UserAddress::find()->where('record_type <> "'.RecordType::Deleted.'" AND user_id ='. $id)->with('address.country')->all();
        $this->renderJson(ArrayHelper::convertArToArray($addresses));
    }

    public function actionList($model, $id)
    {
        return $this->renderPartial('_list',['model' => $model, 'id' => $id]);
    }

    public function actionCompanyList($model, $controller = '', $company_id = false)
    {
        return $this->renderPartial('_companyList', [
            'model'      => $model,
            'company_id' => $company_id,
            'controller' => $controller
        ]);
    }

    public function actionPopupForm($model, $controller = '', $company_id = false)
    {
        return $this->renderPartial('_popupForm', [
            'model'      => $model,
            'company_id' => $company_id,
            'controller' => $controller
        ]);
    }

    public function actionPopupAddressForm($model)
    {
        return $this->renderPartial('_popupAddressForm',['model' => $model]);
    }

    public function actionAddressSave(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $user = \common\models\UserAddress::saveByPost($data['user']);
        $this->renderJson(ArrayHelper::convertArToArray($user));
    }

    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);
        $user = User::saveByPost($data['user']);
        if (count($user->errors) == 0) {
            $user->refresh();

            if ($user->user_type != UserType::CorporateMember) {
                $rawUser = User::find()
                    ->where('user.id = '.$user->id)
                    ->one();
            } else {
                $rawUser = User::find()
                    ->select('user.*, company_user_group.name AS group_name, user.title AS user_title')
                    ->with('primaryAddress')
                    ->leftJoin('company_user_group', 'company_user_group.id = user.company_user_group_id')
                    ->where('user.id = '.$user->id)
                    ->one();
            }
            $user = ArrayHelper::convertArToArray($rawUser);
            $user['group_name'] = $rawUser['group_name'];
            $user['user_title'] = $rawUser['user_title'];
        } else {
            $user = ArrayHelper::convertArToArray($user);
        }
        unset($user['password']);
        $this->renderJson($user);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User;
        if (!isset($model->primaryAddress)) {
            $model->populateRelation('primaryAddress', new \common\models\Address);
        }

        if ($this->populateUserTypeValues($model)) {
            $model->api_token = uniqid() . uniqid();
            $model->record_type = RecordType::InActive;

            $password = Yii::$app->request->post('password');
            if (!empty($password)) {
                $model->password = $model->generatePassword($password);
            } else {
                $model->password = '';
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                $model->primaryAddress->name           = Yii::t('label', 'Default');
                $model->primaryAddress->email          = $model->username;
                $model->primaryAddress->first_name     = $model->first_name;
                $model->primaryAddress->last_name      = $model->last_name;
                $model->primaryAddress->title          = $model->title;

                if (!$model->primaryAddress->save()) {
                    throw new \Exception('Error user address saving');
                }

                $userAddress               = new \common\models\UserAddress;
                $userAddress->user_id      = $model->id;
                $userAddress->address_id   = $model->primaryAddress->id;
                $userAddress->address_type = \common\enums\AddressType::Primary;
                $userAddress->record_type  = RecordType::Active;

                if (!$userAddress->save()) {
                    throw new \Exception('Error user address saving');
                }
                $model->generateHash();
                $transaction->commit();


            } catch (Exception $ex) {
                $transaction->rollBack();
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!isset($model->primaryAddress)) {
            $model->populateRelation('primaryAddress', new \common\models\Address);
        }

        if ($this->populateUserTypeValues($model)) {
            $password = Yii::$app->request->post('password');
            if (!empty($password)) {
                $model->password = $model->generatePassword($password);
            }
            $transaction = Yii::$app->db->beginTransaction();

            try {

                if (!$model->save()) {
                    throw new \Exception('Error user saving');
                }
                $model->primaryAddress->email = $model->username;
                if (!$model->primaryAddress->save()) {
                    throw new \Exception('Error user address saving');
                }
                $userAddress               = new \common\models\UserAddress;
                $userAddress->user_id      = $model->id;
                $userAddress->address_id   = $model->primaryAddress->id;
                $userAddress->address_type = \common\enums\AddressType::Primary;
                $userAddress->record_type  = RecordType::Active;

                if (!$userAddress->save()) {
                    throw new \Exception('Error user address saving');
                }
                if ($model->isNewRecord) {
                    $model->generateHash();
                }

                $transaction->commit();
                return $this->redirect(['index']);
            } catch (Exception $ex) {
                $transaction->rollBack();
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

    private function populateUserTypeValues($model){
        $post = Yii::$app->request->post('User');

        try {
            if (Yii::$app->request->isPost) {
                $model->company_id          = null;
                $model->restaurant_chain_id = null;
                $model->restaurant_group_id = null;
                $model->restaurant_id       = null;

                if (in_array($post['user_type'], [UserType::RestaurantAdmin, UserType::RestaurantTeam, UserType::RestaurantApp])) {
                    $model->restaurant_id = $post['restaurant_id'];
                    $model->client_id     = Yii::$app->request->isImpersonated() ?
                                        Yii::$app->request->getImpersonatedClientId() :
                                        \common\models\Restaurant::find()->where(['id' => $model->restaurant_id])->one()->client_id;
                }
                if (in_array($post['user_type'], [UserType::ClientAdmin, UserType::Member, UserType::InnTouch])) {
                    $model->client_id = $post['client_id'];
                }
                if (in_array($post['user_type'], [UserType::CorporateAdmin, UserType::CorporateMember])) {
                    $model->company_id = $post['company_id'];
                    $model->client_id = Yii::$app->request->isImpersonated() ?
                        Yii::$app->request->getImpersonatedClientId():
                        \common\models\Company::find()->where(['id' => $model->company_id])->one()->client_id;
                }
                if (in_array($post['user_type'], [UserType::RestaurantChainAdmin])) {
                    $model->restaurant_chain_id = $post['restaurant_chain_id'];
                    $model->client_id = Yii::$app->request->isImpersonated() ?
                                        Yii::$app->request->getImpersonatedClientId():
                                        \common\models\RestaurantChain::find()->where(['id' => $model->restaurant_chain_id])->one()->client_id;
                }
                if (in_array($post['user_type'], [UserType::RestaurantGroupAdmin])) {
                    $model->restaurant_group_id = $post['restaurant_group_id'];
                    $model->client_id = Yii::$app->request->isImpersonated() ?
                                        Yii::$app->request->getImpersonatedClientId():
                                        \common\models\RestaurantChain::find()->where(['restaurant_group.id' => $model->restaurant_group_id])->joinWith('restaurantGroups')->one()->client_id;
                }

                $model->username   = $post['username'];
                $model->first_name = $post['first_name'];
                $model->last_name  = $post['last_name'];
                $model->title      = $post['title'];
                $model->username   = $post['username'];
                $model->user_type  = $post['user_type'];
                $model->primaryAddress->load(Yii::$app->request->post('Address'), '');
                return true;
            }
        } catch (Exception $ex) {
            return false;
        }

        return false;
    }

    /**
     * Deletes an existing User model.
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
     * Activates an existing User model.
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
     * Deactivates an existing User model.
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

    public function actionResend($id)
    {
        $model = $this->findModel($id);
        if ($model->sendActivationEmail())
        {
             Yii::$app->session->setFlash('success', 'The activation email has been re-sent.');
        }
        else
        {
             Yii::$app->session->setFlash('danger', 'There was an error sending email.');
        }

        return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::find()->where("id = $id")->with('primaryAddress')->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
