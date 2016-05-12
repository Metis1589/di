<?php
namespace gateway\modules\v1\components;

use yii\helpers\Json;

class JsonApiResponse extends ApiResponse
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
		echo Json::encode($response);
	}

	/**
	 * Adds specific Content-Type header to response.
	 *
	 * @return void
	 */
	protected function addHeader()
	{
		header('Content-Type: application/json');
	}

} 