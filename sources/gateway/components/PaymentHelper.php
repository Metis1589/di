<?php

namespace gateway\components;

class PaymentHelper {

    public static function Refund($psp_reference, $currency, $value, $client) {
        
        $request = array(
            "action" => "Payment.refund",
            "modificationRequest.merchantAccount" => $client["payment_merchant_account"],
            "modificationRequest.modificationAmount.currency" => strtoupper($currency),
            "modificationRequest.modificationAmount.value" => $value * 100,
            "modificationRequest.originalReference" => $psp_reference,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pal-test.adyen.com/pal/adapter/httppost");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "ws_397332@Company.DineInLimited:@rnZW%TZ4)v/>IXtcPTG3GTz5");
        curl_setopt($ch, CURLOPT_POST, count($request));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if ($result === false)
            echo "Error: " . curl_error($ch);
        else {
            parse_str($result, $result);
            return array_key_exists('modificationResult_response', $result);
        }

        curl_close($ch);
        
        return false;
    }

}
