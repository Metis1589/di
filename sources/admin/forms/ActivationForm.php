<?php
namespace admin\forms;
use yii\base\Model;
use common\models\User;
use Yii;
use yii\base\InvalidParamException;
/**
 * Password reset request form
 */
class ActivationForm extends Model
{
    public $password;
    public $confirm_password;
    
    private $_user;
    /**
     * @inheritdoc
     */
    
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Activation token cannot be blank.');
        }
        $this->_user = User::findIdentityByActivationHash($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong activation token.');
        }
        parent::__construct($config);
    }
    
    
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'required'],
            ['confirm_password', 'compare', 'compareAttribute'=>'password', 'message'=> Yii::t('error', "Passwords don''t match")],
        ];
    }
    
    public function activate(){
        $user = $this->_user;
        $user->password = $user->generatePassword($this->password);
        return $user->activate();
    }
    
}