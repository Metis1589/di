<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\SuggestRestaurantForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class SuggestRestaurantAction extends PostApiAction
{
    protected function createRequestForm()
    {
        return new SuggestRestaurantForm;
    }


    protected function getResponseData($requestForm)
    {
        try {
            $client = Yii::$app->globalCache->getClient($requestForm->client_key);
            if($client){
                $language_id = Yii::$app->globalCache->getLanguageId(Yii::$app->language);
                if(!$language_id){
                    $language_id = Yii::$app->globalCache->getDefaultLanguageId();
                }
                \gateway\modules\v1\services\EmailService::sendRestarauntSuggest($client,$requestForm,$language_id);
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}