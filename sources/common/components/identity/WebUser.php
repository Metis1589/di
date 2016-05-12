<?php
namespace common\components\identity;

use Yii;

class WebUser extends \yii\web\User
{
    public function getId()
    {
        Yii::$app->session->open();
        if ($this->getIsGuest()) {
            return \common\enums\UserType::UNAUTHORIZED_USER_ID;
        }
        else if(!$this->getIsGuest()){
            return parent::getId();
        } else {
            return Yii::$app->session->id;
        }
    }
    
    public function isSessionStarted()
    {
        $sessionUser = Yii::$app->userCache->getUser();
        return isset($sessionUser);
    }

}