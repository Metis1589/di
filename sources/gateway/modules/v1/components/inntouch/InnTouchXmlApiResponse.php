<?php
namespace gateway\modules\v1\components\inntouch;

use gateway\modules\v1\components\XmlApiResponse;
use Yii;

class InnTouchXmlApiResponse extends XmlApiResponse
{
    /**
     * Renders response in XML format.
     *
     * @param array $response Response data array.
     *
     * @return void
     */
    protected function renderFormatted(array $response)
    {
        $result = $this->statusCode == self::STATUS_SUCCESS ? 'Success' : 'Failed';
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-1"?><Responses></Responses>');
        $xml->addAttribute('Result', 'Success');
        $responseXml= $xml->addChild('Response');
        $responseXml->addAttribute('RequestRef', '');
        $guid = $this->getGUID();
        $responseXml->addAttribute('Ref', $guid);
        $responseXml->addAttribute('RequestType', Yii::$app->request->get('requesttype'));
        $responseXml->addAttribute('TxnResult', $result);
        $responseXml->addAttribute('TxnStatus', $result);
        $data = $responseXml->addChild('Data');
        if ( $this->statusCode == self::STATUS_SUCCESS) {
            parent::_arrayToXml($response['data'], $data);
        } else {
            $responseXml->addAttribute('Reason', $response['error_message']);
        }

        echo $xml->asXML();

    }

    private function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }
}