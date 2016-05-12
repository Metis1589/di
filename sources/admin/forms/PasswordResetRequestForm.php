<?php
namespace admin\forms;

use common\components\language\T;
use common\enums\RecordType;
use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'email'],
            ['username', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['record_type' => RecordType::Active],
                'message' => T::e('There is no user with such email.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendRequest()
    {
        /* @var $user User */
        $user = User::findOne([
            'record_type' => RecordType::Active,
            'username' => $this->username,
        ]);

        if ($user) {
            return $user->requestToResetPassword();
        }

        return false;
    }
}
