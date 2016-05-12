<?php
namespace tests\codeception\common\components\voucherServices;


use common\components\voucherServices\EagleEyeValidationService;
use Codeception\Specify;
use yii\codeception\TestCase;
use Yii;
class EagleEyeTest extends TestCase
{
    use Specify;
    public $appConfig = '@tests/codeception/config/common/unit.php';
    /**
     * @var \tests\codeception\common\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe()
    {
        $testClient = [
            'eagle_eye_username'=>'dineinUAT',
            'eagle_eye_password'=>'YSWZyt1UQ93MZ0EK6',
            'eagle_eye_endpoint'=>'https://uat-4.eagleeyesolutions.co.uk/hercules/micros/micros'
        ];
        $code = 'HHXP46F';
        $testVerifyResponse = <<<RESPONSE
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <soap:Body>
      <VoucherValidationStatus xmlns="http://www.w3.org/2001/XMLSchema">
         <ValidationResult>0</ValidationResult>
         <ApplicationsRemaining>0</ApplicationsRemaining>
         <ValidationNotes>Promotion – 30% off mains</ValidationNotes>
         <VoucherValue>0</VoucherValue>
         <MinIncrement>0</MinIncrement>
         <PromotionIdentifier>H</PromotionIdentifier>
      </VoucherValidationStatus>
   </soap:Body>
</soap:Envelope>
RESPONSE;
        $testRedeemResponse = <<<RESP
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <soap:Body>
      <VoucherUsedStatus xmlns="http://www.w3.org/2001/XMLSchema">
         <UsedResult>0</UsedResult>
         <TransID>17088860</TransID>
      </VoucherUsedStatus>
   </soap:Body>
</soap:Envelope>
RESP;
        $testUnlockResponse = <<<RESP
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <soap:Body>
      <VoucherReactivateStatus xmlns="http://www.w3.org/2001/XMLSchema">
         <ReactivateResult>0</ReactivateResult>
         <TransID>16397441</TransID>
      </VoucherReactivateStatus>
   </soap:Body>
</soap:Envelope>
RESP;
        $usedTestCode = ['HHXP46F'];
        $this->specify('Test responses',function() use($testVerifyResponse,$testRedeemResponse,$testUnlockResponse){
            expect(
                'Soap is instance of SimpleXMLElement',
                EagleEyeValidationService::getSoap($testVerifyResponse) instanceof \SimpleXMLElement
            )->true();
            expect(
                'ValidationResult == 0',
                EagleEyeValidationService::getSoap($testVerifyResponse)->Body->children()->VoucherValidationStatus->ValidationResult == 0
            )->true();
            expect(
                'Soap is instance of SimpleXMLElement',
                EagleEyeValidationService::getSoap($testRedeemResponse) instanceof \SimpleXMLElement
            )->true();
            expect(
                'UsedResult == 0',
                EagleEyeValidationService::getSoap($testRedeemResponse)->Body->children()->VoucherUsedStatus->UsedResult == 0
            )->true();
            expect(
                'Soap is instance of SimpleXMLElement',
                EagleEyeValidationService::getSoap($testUnlockResponse) instanceof \SimpleXMLElement
            )->true();
            expect(
                'ReactivateResult == 0',
                EagleEyeValidationService::getSoap($testUnlockResponse)->Body->children()->VoucherReactivateStatus->ReactivateResult == 0
            )->true();
        });

        $this->specify('Test already used code',function() use ($testClient,$usedTestCode){
            $verifyResult = EagleEyeValidationService::verify($testClient, $usedTestCode[0]);
            codecept_debug($verifyResult);
            expect('Verify false', $verifyResult)->false();
        });

    }

}