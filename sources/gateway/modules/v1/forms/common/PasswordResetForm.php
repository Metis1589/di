<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use common\models\User;
use gateway\modules\v1\forms\BaseRequestApiForm;

class PasswordResetForm extends BaseRequestApiForm
{
    public $token;
    public $password;
    public $user;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['token', 'required', 'message' => T::e('Token is missing')],
            ['password', 'required', 'message' => T::e('Password is missing')],
            ['token', 'validateToken']
        ];
    }

    public function validateToken($attribute, $params) {
        $this->user =User::findByPasswordResetHash($this->token);
        if (!$this->user) {
            $this->addError($attribute, T::e('Invalid token'));
        }

    }
}