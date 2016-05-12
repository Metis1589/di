<?php
namespace gateway\modules\v1\components;

use Yii;
use yii\rest\Action;

class ApiAction extends Action
{
    /**
     * Api response object.
     *
     * @var ApiResponse
     */
    protected $response;

    /**
     * Default output format.
     *
     * @var string
     */
    protected static $_defaultOutputFormat = 'json';

    /**
     * Overrides parent controller.
     *
     * @param mixed  $controller Controller id.
     * @param string $id         Action id.
     */
    public function __construct($controller, $id, $config = [])
    {
        parent::__construct($controller, $id, $config);
        $this->response = $this->_createResponseObject();
    }

    /**
     * Renders response.
     *
     * @return void
     */
    public function render()
    {
        $this->response->render();
    }

    /**
     * Returns response object depending on the input data.
     *
     * @return ApiResponse
     */
    protected function _createResponseObject()
    {
        $outputFormat = Yii::$app->request->getQueryParam('output', self::$_defaultOutputFormat);
        if(Yii::$app->request->getQueryParam('callback')){
            $outputFormat = 'jsonp';
        }
        if ($outputFormat == self::$_defaultOutputFormat) {
            return new JsonApiResponse();
        }
        elseif($outputFormat == 'jsonp'){
            return new JsonpApiResponse();
        }

        return new XmlApiResponse();
    }
}