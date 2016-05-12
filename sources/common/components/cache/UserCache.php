<?php

namespace common\components\cache;

use gateway\models\SessionUser;
use Yii;

class UserCache extends Cache
{
    public function getUser()
    {
        if (!Yii::$app->session->id)
        {
            Yii::$app->session->open();
//            Yii::$app->cache->set(Yii::$app->session->id . 'sessionUser', serialize($sessionUser));
        }

            $result = unserialize(Yii::$app->cache->get(Yii::$app->session->id . 'sessionUser'));

            if (!$result) {
                $result = new SessionUser();
                if (!Yii::$app->user->isGuest) {
                    $result->loadAddresses(Yii::$app->user->identity->id);
                }
            }

        return $result;
    }

    public function setUser($sessionUser)
    {
        if (!Yii::$app->session->id)
        {
            Yii::$app->session->open();
//            Yii::$app->cache->set(Yii::$app->session->id . 'sessionUser', serialize($sessionUser));
        }
//        else {
//            throw new \yii\base\Exception('Can not save session user. No active session');
//        }

        Yii::$app->cache->set(Yii::$app->session->id . 'sessionUser', serialize($sessionUser));
    }
}