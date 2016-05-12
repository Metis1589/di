<?php
namespace gateway\modules\v1\components;

use Exception;
use gateway\modules\v1\forms\BaseRequestApiForm;
use Yii;

abstract class PostApiAction extends ApiAction
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
        
        // For JSONP format POST request is impossible, so we use usual GET params even for POST requests.
        $method = Yii::$app->request->getQueryParam('callback') ? 'getQueryParams' : 'getBodyParams';
        
        if ($requestForm == null || ($requestForm->populate(Yii::$app->request->$method()) && $requestForm->validate())) {
            $responseData = $this->getResponseData($requestForm);
            if (false === $responseData) {
                $this->response->setErrorCode(ApiResponse::STATUS_UNKNOWN_ERROR_CODE);
            } else if ($responseData instanceof Exception) {
                $this->response->setException($responseData);
            }
            else {
                $this->response->setData($responseData);
            }
        }
        else {
            $this->response->setErrorCode($requestForm->getFirstAttributeError());
        }

        $this->response->render();
    }

    /**
     * Creates request form used to validate request parameters.
     *
     * @return BaseRequestApiForm
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