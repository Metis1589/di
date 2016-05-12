<?php
namespace gateway\modules\v1\components\inntouch;

use common\enums\RecordType;
use common\models\IntouchLastRequest;
use DateTime;
use Exception;
use gateway\modules\v1\components\ApiAction;
use gateway\modules\v1\components\ApiResponse;
use Yii;

class InnTouchApiAction extends ApiAction
{
    public $modelClass = '';
    protected $intouchLastRequest;

    /**
     * Returns response object depending on the input data.
     *
     * @return ApiResponse
     */
    protected function _createResponseObject()
    {
        return new InnTouchXmlApiResponse();
    }

    /**
     * Performs action.
     *
     * @return void
     */
    public function run()
    {
        $requestForm = $this->createRequestForm();

        if (Yii::$app->request->isPost && $requestForm->populate(Yii::$app->request->get()) && $requestForm->validate()) {
            $this->intouchLastRequest = IntouchLastRequest::find()->where(['type' => $requestForm->requesttype, 'client_id' => Yii::$app->user->identity->client_id, 'record_type' => RecordType::Active])->one();
            $responseData = $this->getResponseData($requestForm);
            if (false === $responseData) {
                $this->response->setErrorCode(ApiResponse::STATUS_UNKNOWN_ERROR_CODE);
            } else if ($responseData instanceof Exception) {
                $this->response->setException($responseData);
            }
            else {
                $this->response->setData($responseData);

                if (!isset($this->intouchLastRequest)) {
                    $this->intouchLastRequest = new IntouchLastRequest();
                    $this->intouchLastRequest->type = $requestForm->requesttype;
                    $this->intouchLastRequest->client_id = Yii::$app->user->identity->client_id;
                }
                $this->intouchLastRequest->time = (new DateTime('now'))->format('Y-m-d H:i:s');
                $this->intouchLastRequest->save();
            }
        }
        else {
            $this->response->setErrorCode($requestForm->getFirstAttributeError());
        }

        $this->response->render();
    }
}