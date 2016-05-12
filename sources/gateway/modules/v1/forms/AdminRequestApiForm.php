<?php

namespace gateway\modules\v1\forms;
use common\components\language\T;
use Yii;

class AdminRequestApiForm extends BaseRequestApiForm {
//
//    public $api_token;
//    private $user = null;
//
//    public function rules() {
//        return array_merge(
//                [
//                    ['api_token', 'required', 'message' => T::e('Api Token is missing')],
//                    ['api_token', 'isTokenValid']
//                ], $this->customRules()
//        );
//    }
//
//    /**
//     * Custom validation rules. Can be re-declared in the child class to extend validation rules.
//     *
//     * @return array
//     */
//    protected function customRules() {
//        return [
//        ];
//    }
//
//    /**
//     * Logs in a user using the provided username and password.
//     *
//     * @return boolean whether the user is logged in successfully
//     */
//    public function login()
//    {
//        $user = $this->getUser();
//        if ($user) {
//            Yii::$app->user->enableSession = false;
//            return Yii::$app->user->login($this->getUser());
//        } else {
//            return false;
//        }
//    }
//
//    public function getUser() {
//        if ($this->user != null){
//            return $this->user;
//        }
//        $this->user = \common\models\User::find()->where(['api_token' => $this->api_token])->one();
//        return $this->user;
//    }
//
//    public function isTokenValid(){
//        if ($this->getUser() == null) {
//            $this->addError('api_token', T::e('Invalid api token'));
//        } else {
//            $this->login();
//        }
//    }
//
}
