<?php

namespace console\models;
use Yii;

class TwilioService {
    private $_twilioNumber;
    private $_clientAccount;

    public function __construct() {
        $this->_twilioNumber  = Yii::$app->params['phone_number'];
        $this->_clientAccount = Yii::$app->twillio->getClient()->account;
    }

    /**
     * Send SMS message via twilio service
     *
     * @param array  $addressData Recipient address data
     * @param string $messageBody The text of the message you want to send, limited to 1600 characters.
     *
     * @return string
     */
    public function sendTextMessage($addressData, $messageBody, $order) {
        if ($addressData['phone']) {
            // Send SMS
            if (isset(Yii::$app->params['redirect_to_phone_number']) && !empty(Yii::$app->params['redirect_to_phone_number'])) {
                $addressData['phone'] =  Yii::$app->params['redirect_to_phone_number'];
            }
            $message = $this->_clientAccount->messages->sendMessage(
                $this->_twilioNumber,
                $addressData['phone'],
                $messageBody
            );

            \common\models\OrderContactHistory::addRecord(
                $order->id,
                \common\enums\RestaurantContactOrderType::Sms,
                'sms sent',
                $addressData['name'] ?: \common\enums\UserType::UNAUTHORIZED,
                $message->to,
                $message->sid,
                1,
                $order->delivery_address_data ? \common\enums\UserType::Member : \common\enums\UserType::UNAUTHORIZED,
                0,
                isset($message->price) ? $message->price : 0,
                isset($message->PriceUnit) ? $message->PriceUnit : '$'
            );

            return $message->sid;
        } else {
            return false;
        }
    }

    /**
     * Make twilio call and save information in OrderIvrHistory
     *
     * @param string $recipient   Recipient phone number. Format with a '+' and country code e.g., +16175551212 (E.164 format). For 'To' numbers without a '+', Twilio will use the same country code as the 'From' number. Twilio will also attempt to handle locally formatted numbers for that country code (e.g. (415) 555-1212 for US, 07400123456 for GB). If you are sending to a different country than the 'From' number, you must include a '+' and the country code to ensure proper delivery.
     * @param array  $orderData   Order details to fill into database
     * @param string $url         The fully qualified URL that should be consulted when the call connects. Just like when you set a URL on a phone number for handling inbound calls. See the Url Parameter section below for more details.
     *
     * @return string
     */
    public function makeIvrCall($recipient, $orderData, $url) {
        try  {
            // Make call
            $call = $this->_clientAccount->calls->create(
                $this->_twilioNumber,
                $recipient,
                $url,
                []
            );

            Yii::$app->globalCache->setIvrSession($call->sid, $orderData);
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }

        return $call->sid;
    }

    /**
     * The <Gather> verb collects digits that a caller enters into his or her telephone keypad.
     * When the caller is done entering data, Twilio submits that data to the provided 'action'
     * URL in an HTTP GET or POST request, just like a web browser submits data from an HTML form.
     * Inits 'Gather' with redirect url
     *
     * @param \Services_Twilio_Twiml $twiml
     * @param string  $actionRouter Relative or absolute URL
     * @param array   $messages     Response messages
     * @param string  $voice        Man / woman voice selection
     * @param string  $language     Language to answer using it
     * @param integer $numDigits    The digits the caller pressed, excluding the finishOnKey digit if used
     * @param integer $timeout      The 'timeout' attribute sets the limit in seconds that Twilio will wait for the caller to press another digit before moving on and making a request to the 'action' URL. For example, if 'timeout' is '10', Twilio will wait ten seconds for the caller to press another key before submitting the previously entered digits to the 'action' URL. Twilio waits until completing the execution of all nested verbs before beginning the timeout period.
     * @param string  $finishOnKey  Key which identifies code entering end
     *
     * @return object
     */
    public static function constructResponse($twiml, $actionRouter, $messages = [], $voice = 'man', $language = 'en', $numDigits = 1, $timeout = false, $finishOnKey = false) {
        $timeout = $timeout ?: Yii::$app->params['timeout'];
        $params  = [
            'action'    => $actionRouter,
            'numDigits' => $numDigits,
            'timeout'   => $timeout
        ];

        if ($finishOnKey) {
            $params['finishOnKey'] = $finishOnKey;
        }

        $gather = $twiml->gather($params);

        foreach ($messages as $message) {
            $gather->say($message, [
                'voice'    => $voice,
                'language' => $language
            ]);
        }

        header('Content-Type: text/xml');
        print $twiml;
        die;
    }

    /**
     * Init twilio's 'Gather' method without redirect
     *
     * @param \Services_Twilio_Twiml $twiml       Twilion TWIML
     * @param array                  $messages    Messages to say by IVR robot
     * @param integer                $numDigits   Number of maximum entered digits
     * @param string                 $finishOnKey Key to identify digits enternig finish
     */
    public static function say($twiml, $messages = [], $numDigits = 1, $finishOnKey = false)
    {
        $params = [
            'numDigits' => $numDigits
        ];

        if ($finishOnKey) {
            $params['finishOnKey'] = $finishOnKey;
        }

        $gather = $twiml->gather($params);

        foreach ($messages as $message) {
            $gather->say($message, [
                'voice'    => 'man',
                'language' => 'en'
            ]);
        }

        header('Content-Type: text/xml');
        print $twiml;
        die;
    }

    /**
     * Get compiled message
     *
     * @param string $messageIdent Message identificator from \IvrMessageType
     * @param array  $messageVars  Message variables {{key}} => value
     *
     * @return string
     */
    public static function getMessage($messageIdent, $messageVars)
    {
        return str_replace(
            array_keys($messageVars),
            array_values($messageVars),
            \common\enums\IvrMessageType::getContents()[$messageIdent]
        );
    }
}