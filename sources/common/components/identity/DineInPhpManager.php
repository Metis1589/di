<?php
namespace common\components\identity;
 
use Yii;
use common\components\identity\Roles;
 
class DineInPhpManager extends \yii\rbac\PhpManager
{
    public function getAssignments($userId)
    {
        if (Yii::$app->user->isGuest) {
            $token = Yii::$app->request->getFirstParamValue(['api_token']);
            if (isset($token)){
               $user = \common\models\User::find()->where(['api_token' => $token])->one();
               if (isset($user)){
                   return $this->createAssigment($user->id, $user->user_type);
               }
            }
            return $this->createAssigment(\common\enums\UserType::UNAUTHORIZED_USER_ID, \common\enums\UserType::UNAUTHORIZED);
        }
        else if(!Yii::$app->user->isGuest){
            return $this->createAssigment($userId, Yii::$app->user->identity->user_type);
        }
    }
    
    private function createAssigment($userId, $role)
    {
        $assignment = new \yii\rbac\Assignment;
        $assignment->userId = $userId;
        $assignment->roleName = $role;
        return [$assignment->roleName => $assignment];
    }
    
}