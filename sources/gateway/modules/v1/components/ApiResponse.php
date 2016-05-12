<?php
namespace gateway\modules\v1\components;

use ErrorException;
use Yii;
use yii\db\Exception;

abstract class ApiResponse
{
	// Response status codes
	const STATUS_SUCCESS                     = 0;
	const STATUS_UNKNOWN_ERROR               = 100;
	const STATUS_VALIDATION_ERROR            = 101;
	const STATUS_EXCEPTION                   = 500;

    const STATUS_UNKNOWN_ERROR_CODE          = "ERR_API_UNKNOWN_ERROR";
    const STATUS_FATAL_ERROR_CODE          = "ERR_FATAL_UNKNOWN_ERROR";

	/**
	 * Status code.
	 *
	 * @var int
	 */
	protected $statusCode;

    /**
     * Error message.
     *
     * @var string
     */
    private $_errorMessage;

	/**
	 * Response data.
	 *
	 * @var array
	 */
	private $_data;

	/**
	 * Constructor sets default response status code to 'success'.
	 *
	 * @param integer $statusCode Status code.
	 */
	public function __construct($statusCode = self::STATUS_SUCCESS)
	{
		$this->statusCode = $statusCode;
	}

	/**
	 * Sets response status code.
	 *
	 * @param integer $statusCode Status code.
	 *
	 * @return void
	 */
//	public function setStatusCode($statusCode)
//	{
//		$this->_statusCode = $statusCode;
//	}

    /**
     * Sets response status code.
     *
     * @param integer $statusCode Status code.
     * @param string $errorCode Error Message.
     *
     * @return void
     */
    public function setErrorCode($errorCode)
    {
        $this->statusCode = self::STATUS_VALIDATION_ERROR;
        $this->_errorMessage = Yii::$app->globalCache->getLabel($errorCode);
    }

    /**
     * @param Exception $ex
     */
    public function setException($ex)
    {
        $this->statusCode = self::STATUS_EXCEPTION;
        $this->_errorMessage = Yii::$app->globalCache->getLabel($ex->getMessage());
    }

	/**
	 * Returns response status code.
	 *
	 * @return integer
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * Sets response data.
	 *
	 * @param array $data Response data.
	 *
	 * @return void
	 */
	public function setData($data)
	{
		$this->_data = $data;
	}

	/**
	 * Outputs API response.
	 *
	 * @return void
	 */
	public function render()
	{
		$response = array();

		$response['status_code'] = (int) $this->statusCode;
        if ($this->statusCode != self::STATUS_SUCCESS)
        {
            $response['error_message'] = $this->_errorMessage;
        }

    	$response['data'] = $this->_data;

		$this->addHeader();
		$this->renderFormatted($response);

		Yii::$app->end();
	}

	/**
	 * Renders formatted response data.
	 *
	 * @param array $response Response array.
	 *
	 * @return void
	 */
	abstract protected function renderFormatted(array $response);

	/**
	 * Adds specific Content-Type header to response.
	 *
	 * @return void
	 */
	abstract protected function addHeader();

} 