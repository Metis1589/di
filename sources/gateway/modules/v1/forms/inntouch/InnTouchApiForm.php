<?php
namespace gateway\modules\v1\forms\inntouch;

use common\components\language\T;
use common\enums\RecordType;
use common\enums\UserType;
use common\models\User;
use gateway\modules\v1\forms\FormModel;
use Yii;

class InnTouchApiForm extends FormModel
{
    public $user;
    public $pwd;
    public $requesttype;

    public function rules()
    {
        return array_merge([
            ['user', 'required', 'message' => T::e('User is missing')],
            ['pwd', 'required', 'message' => T::e('Password is missing')],
            ['pwd', 'isPasswordValid'],
            ['requesttype', 'required', 'message' => T::e('Request type is missing')]
        ], $this->customRules());
    }

    protected function customRules() {
        return [];
    }

    public function isPasswordValid(){

            /** @var User $user */
            $user = \common\models\User::find()->where(['username' => $this->user, 'user_type' => UserType::InnTouch, 'record_type' => RecordType::Active])->one();

            if ($user == null || empty($user->client_id) || !$user->client->has_inntouch) {
                $this->addError('pwd', T::e('Invalid password'));
                return;
            }

            Yii::$app->user->enableSession = false;
            if (!$user->validatePassword($this->pwd) || !Yii::$app->user->login($user)) {
                $this->addError('pwd', T::e('Invalid password'));
                return;
            }
    }
}