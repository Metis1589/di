<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   6/24/15
 * @time   2:54 PM
 */

namespace common\components\voucherServices;

use Yii;

class EagleEyeValidationService
{
    protected static $logCategory = 'common.components.voucherservice.eagleeye';

    /**
     * Get Soap xml element
     *
     * @param string $xml
     * @return \SimpleXMLElement
     */
    public static function getSoap($xml)
    {
        $simpleXml = simplexml_load_string($xml);
        $ns        = $simpleXml->getNamespaces(true);
        return $simpleXml->children($ns['soap']);
    }

    /**
     * Encode string
     *
     * @param $string
     * @return string
     */
    public static function encode($string)
    {
        return htmlentities($string, ENT_XML1 | ENT_DISALLOWED);
    }

    /**
     * Verify code
     *
     * @param array|\common\models\Client  $client
     * @param string $code
     * @param string $type
     * @return bool
     */
    public static function verify($client, $code, $type = 'S')
    {
        Yii::info('called EagleEye verify method', self::$logCategory);
        $code   = self::encode($code);
        $params = self::buildParams($client);
        try {
            $body = <<<BODY
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope
xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <SOAP-ENV:Header>
       <AuthHeader>
          <Username>{$params['eagle_eye_username']}</Username>
          <Password>{$params['eagle_eye_password']}</Password>
       </AuthHeader>
    </SOAP-ENV:Header>
    <SOAP-ENV:Body>
       <Validate>
          <VoucherType>$type</VoucherType>
          <VoucherCode>$code</VoucherCode>
          <PromotionIdentifer />
          <LocationIdentifier />
          <TransactionTime />
          <OrderID></OrderID>
          <TerminalID></TerminalID>
          <ServerID></ServerID>
          <ValueUsed></ValueUsed>
          <BrandID />
          <ServingArea />
          <OfflineFlag></OfflineFlag>
          <OfflineAuthCode />
       </Validate>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
BODY;

            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'header' => "Content-Type: text/xml",
                    'content' => $body,
//                    'ignore_errors' => true
                )
            );
            Yii::trace('Context options', self::$logCategory);
            Yii::trace(var_export($options, true), self::$logCategory);
            $context = stream_context_create($options);
            $result  = file_get_contents($params['eagle_eye_endpoint'], false, $context);
            Yii::trace('Response result', self::$logCategory);
            Yii::trace($result, self::$logCategory);
            $soap = self::getSoap($result);
            return $soap->Body->children()->VoucherValidationStatus->ValidationResult == 0;
        } catch (\Exception $e) {
            Yii::error($e->__toString(), 'common.components.voucherservice.eagleeye');
            return false;
        }
    }


    /**
     *
     * Redeem voucher
     *
     * @param array|\common\models\Client  $client
     * @param string $code
     * @param string $type
     * @return bool
     */
    public static function redeem($client, $code, $type = 'S')
    {
        try {
            Yii::info('called EagleEye redeem method', self::$logCategory);
            $code   = self::encode($code);
            $params = self::buildParams($client);
            $body   = <<<BODY
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope
xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <SOAP-ENV:Header>
       <AuthHeader>
          <Username>{$params['eagle_eye_username']}</Username>
          <Password>{$params['eagle_eye_password']}</Password>
       </AuthHeader>
    </SOAP-ENV:Header>
    <SOAP-ENV:Body>
       <VoucherUsed>
          <VoucherType>$type</VoucherType>
          <VoucherCode>$code</VoucherCode>
          <PromotionIdentifer />
          <LocationIdentifier/>
          <TransactionTime/>
          <OrderID></OrderID>
          <TerminalID></TerminalID>
          <ServerID></ServerID>
          <ValueUsed></ValueUsed>
          <BrandID />
          <ServingArea />
          <OfflineFlag></OfflineFlag>
          <OfflineAuthCode />
       </VoucherUsed >
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
BODY;
            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'content' => $body,
                )
            );
            Yii::trace('Context options', self::$logCategory);
            Yii::trace(var_export($options, true), self::$logCategory);
            $context = stream_context_create($options);
            $result  = file_get_contents($params['eagle_eye_endpoint'], false, $context);
            Yii::trace('Response result', self::$logCategory);
            Yii::trace($result, self::$logCategory);
            $soap = self::getSoap($result);
            return $soap->Body->children()->VoucherUsedStatus->UsedResult == 0;
        } catch (\Exception $e) {
            Yii::error($e->__toString(), 'common.components.voucherservice.eagleeye');
            return false;
        }
    }

    /**
     * Unlock voucher
     *
     * @param array|\common\models\Client   $client
     * @param string  $code
     * @param  string $type
     * @return bool
     */
    public static function unlock($client, $code, $type = 'S')
    {
        try {
            $code   = self::encode($code);
            $params = self::buildParams($client);
            $body   = <<<BODY
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope
xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <SOAP-ENV:Header>
       <AuthHeader>
          <Username>{$params['eagle_eye_username']}</Username>
          <Password>{$params['eagle_eye_password']}</Password>
       </AuthHeader>
    </SOAP-ENV:Header>
    <SOAP-ENV:Body>
       <ReactivateVoucher>
          <VoucherType>$type</VoucherType>
          <VoucherCode>$code</VoucherCode>
          <PromotionIdentifer />
          <LocationIdentifier>8830</LocationIdentifier>
          <TransactionTime>2015-04-10T10:42:00.929Z</TransactionTime>
          <OrderID></OrderID>
          <TerminalID></TerminalID>
          <ServerID></ServerID>
          <ValueUsed></ValueUsed>
          <BrandID />
          <ServingArea />
          <OfflineFlag></OfflineFlag>
          <OfflineAuthCode />
       </ReactivateVoucher>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
BODY;
            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'content' => $body,
                )
            );
            Yii::trace('Context options', self::$logCategory);
            Yii::trace(var_export($options, true), self::$logCategory);
            $context = stream_context_create($options);
            $result  = file_get_contents($params['eagle_eye_endpoint'], false, $context);
            Yii::trace('Response result', self::$logCategory);
            Yii::trace($result, self::$logCategory);
            $soap = self::getSoap($result);
            return $soap->Body->children()->VoucherReactivateStatus->ReactivateResult == 0;
        } catch (\Exception $e) {
            Yii::error($e->__toString(), 'common.components.voucherservice.eagleeye');
            return false;
        }
    }

    /**
     * @param array $client
     * @return array
     */
    protected static function buildParams(array $client)
    {
        $params = array_intersect_key(
            $client,
            array_flip(['eagle_eye_username', 'eagle_eye_password', 'eagle_eye_endpoint'])
        );
        $params = array_map(
            function ($string) {
                return self::encode($string);
            },
            $params
        );
        return $params;
    }
}