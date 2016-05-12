<?php
namespace gateway\modules\v1\components;

use Yii;
use Exception;

abstract class GetApiAction extends ApiAction
{
    public $modelClass = '';

	/**
	 * Performs action.
	 *
	 * @return void
	 */
	public function run()
	{
		$requestForm = $this->createRequestForm();

        if ($requestForm == null || ($requestForm->populate(Yii::$app->request->getQueryParams()) && $requestForm->validate())) {
            $responseData = $this->getResponseData($requestForm);
            if (false === $responseData) {
                $this->response->setErrorCode(ApiResponse::STATUS_UNKNOWN_ERROR_CODE);
            } else if ($responseData instanceof Exception) {
                $this->response->setException($responseData);
            } else {
                $this->response->setData($responseData);
            }
        } else {
            $this->response->setErrorCode($requestForm->getFirstAttributeError());
        }

		$this->response->render();
	}

	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return null
	 */
	protected function createRequestForm()
    {
        return null;
    }

	/**
	 * Returns response data.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return mixed
	 */
	abstract protected function getResponseData($requestForm);

} 