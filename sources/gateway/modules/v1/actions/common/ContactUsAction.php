<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\ContactUsForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class ContactUsAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return ContactUsForm
	 */
	protected function createRequestForm()
	{
            return new ContactUsForm();
	}

	/**
	 * Send Contact Us Request.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
            try {
                $client = Yii::$app->globalCache->getClient($requestForm->client_key);
                if($client){
                    $language_id = Yii::$app->globalCache->getLanguageId(Yii::$app->language);
                    if(!$language_id){
                        $language_id = Yii::$app->globalCache->getDefaultLanguageId();
                    }
                    \gateway\modules\v1\services\EmailService::sendContactUs($client,$requestForm,$language_id);
                }
                return true;
            } catch (Exception $ex) {
                return $ex;
            }
	}
}