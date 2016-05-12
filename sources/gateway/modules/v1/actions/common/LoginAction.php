<?php
namespace gateway\modules\v1\actions\common;

use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\LoginForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;
use yii\base\ErrorException;

class LoginAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return LoginForm
	 */
	protected function createRequestForm()
	{
		return new LoginForm();
	}

	/**
	 * Login
	 *
	 * @param LoginForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            if ($this->login($requestForm)) {

                $session_user->client_id = $requestForm->client_key;

                $session_user->loadAddresses(Yii::$app->user->identity->id);

                Yii::$app->userCache->setUser($session_user);

                return [
                    'name'  => Yii::$app->user->identity->first_name,
                    'email' => Yii::$app->user->identity->username,
//                    'token' => md5(Yii::$app->user->identity->password)
                ];
            } else {
                throw new ErrorException('Error logging in');
            }
		}
		catch (Exception $ex) {
			return $ex;
		}
	}

    private function login($requestForm)
    {
//        Yii::$app->user->enableAutoLogin = $requestForm->remember_me == "true";
        return Yii::$app->user->login($requestForm->user, $requestForm->remember_me == "true" ? 3600 * 24 * 30 : 0);
    }
}