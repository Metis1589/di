<?php
namespace gateway\modules\v1\forms\common;

use common\enums\RecordType;
use common\enums\UserType;
use common\models\User;
use gateway\modules\v1\forms\BaseRequestApiForm;
use Yii;

class LoginForm extends BaseRequestApiForm
{
    public $username;
    public $password;
    public $user;
    public $remember_me;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'message' => Yii::t('error','Incorrect username or password.')],
            ['password', 'validatePassword'],
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->user = User::findByUsername($this->username);
            
            if ($this->user == null){
                $this->addError($attribute, Yii::t('error','Incorrect username or password.'));
            }
            
            if (!isset($this->client_key)){
                $client_id = $this->user->client_id;
            } else {
                $client_id = $this->getClient()['id'];
            }
            
            if($this->user && $this->user->record_type != RecordType::Active){
                $this->addError($attribute, Yii::t('error','Account not activated.'));
            }
            elseif (!$this->user || !($this->user->validatePassword($this->password)) ||  !in_array($this->user->user_type, [UserType::Member, UserType::CorporateMember, UserType::RestaurantApp, UserType::DispatchApp]) || $client_id != $this->user->client_id) {
                $this->addError($attribute, Yii::t('error','Incorrect username or password.'));
            }
        }
    }
}