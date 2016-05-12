<?php
namespace admin\controllers;

use admin\forms\PasswordResetRequestForm;
use admin\forms\ResetPasswordForm;
use common\components\language\T;
use common\enums\CookieName;
use common\enums\RecordType;
use common\enums\UserType;
use common\models\Client;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use common\forms\LoginForm;
use yii\filters\VerbFilter;
use admin\forms\ActivationForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'activate', 'request-password-reset' ,'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'activate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->goHome();
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            /** @var User $user */
            $user = Yii::$app->user->identity;

            switch ($user->user_type) {
//                case (UserType::Admin):
//                case (UserType::Finance):
//                    return true;
                case (UserType::RestaurantAdmin):
                case (UserType::RestaurantTeam):
                    Yii::$app->request->impersonateClient(Client::find()->where(['id' => $user->client_id])->andWhere(['<>', 'record_type', RecordType::Deleted])->one());
                    return $this->redirect(['/restaurant/update', 'id' => $user->restaurant_id]);
                    break;
//                    return $user->restaurant_id == $restaurant_id;
//                case (UserType::RestaurantGroupAdmin):
//                    if (!$restaurant_id) {
//                        return false;
//                    }
//                    $restaurant = Restaurant::getById($restaurant_id);
//                    if (!$restaurant) {
//                        return false;
//                    }
//                    return $restaurant->isInGroup($user->restaurant_group_id);
//                case (UserType::RestaurantChainAdmin):
//                    if (!$restaurant_id) {
//                        return false;
//                    }
//                    $restaurant = Restaurant::getById($restaurant_id);
//                    if (!$restaurant) {
//                        return false;
//                    }
//                    return $restaurant->isInChain($user->restaurant_chain_id);
                case (UserType::RestaurantChainAdmin):
                case (UserType::RestaurantGroupAdmin):
                case (UserType::ClientAdmin):
                case (UserType::RestaurantAdmin):
                case (UserType::RestaurantTeam):
                    Yii::$app->request->impersonateClient(Client::find()->where(['id' => $user->client_id])->andWhere(['<>', 'record_type', RecordType::Deleted])->one());
                    break;
//                    return $restaurant->isInClient($user->client_id);
//                default:
//                    return false;
            }

            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionActivate($token) {
        try {
            $model = new ActivationForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->activate()) {
             return $this->redirect('/site/login', 302);
        }
        return $this->render('activation', ['model' => $model,]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $cookies = Yii::$app->response->cookies;
        $cookies->remove(CookieName::AdminImpersonateClient);
        unset($cookies[CookieName::AdminImpersonateClient]);

        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendRequest()) {
                Yii::$app->getSession()->setFlash('success', T::l('Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', T::l('Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
