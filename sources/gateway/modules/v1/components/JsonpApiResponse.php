<?php
namespace gateway\modules\v1\components;

use Yii;
use yii\helpers\Json;

class JsonpApiResponse extends ApiResponse
{

	/**
	 * Renders response in JSON format.
	 *
	 * @param array $response Response data array.
	 *
	 * @return void
	 */
	protected function renderFormatted(array $response)
	{
                $callbackName = Yii::$app->request->getQueryParam('callback');
                echo $callbackName.'('.Json::encode($response).')';
	}

	/**
	 * Adds specific Content-Type header to response.
	 *
	 * @return void
	 */
	protected function addHeader()
	{
		header('Content-Type: application/javascript');
	}

} 