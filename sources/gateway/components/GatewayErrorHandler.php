<?php

namespace gateway\components;
use common\components\language\T;
use Yii;
use yii\web\ErrorHandler;

class GatewayErrorHandler extends ErrorHandler
{
    public function handleFatalError()
    {
       // parent::handleFatalError();
    }

    protected function renderException($exception)
    {
        header('Content-Type: application/json');
        print json_encode(['status_code'=>500, 'error_message' => $exception->getMessage(), 'stack_trace' => $exception->getTraceAsString(), 'data'=>null]);
        die();
    }
}