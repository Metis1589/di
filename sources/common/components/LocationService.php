<?php

namespace common\components;

use admin\common\ArrayHelper;
use common\components\language\T;
use common\models\Postcode;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class LocationService extends Component {

    /**
     * Find postcode in cache.
     * If postcode was not found than call Google API for its lat/long and add it to cache.
     * If postcode was not found by Google API then send email to global admin.
     *
     * @param $postcode
     * @return array/false
     */
    public function getPostcode($postcode){
        if ($postcode == null) {
            return null;
        }
        $cachedPostcode = Yii::$app->globalCache->getPostcode($postcode);
        if (!isset($cachedPostcode)) {
            $coordinates = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($postcode) . '&sensor=true');
            $coordinates = json_decode($coordinates);

            if (!isset($coordinates->results[0]) || !isset($coordinates->results[0]->geometry)) {
//                 Yii::$app->mailer->sendOne(Yii::$app->params['adminEmail'], T::l('Postcode was not found'), 'admin/missingPostcode', ['postcode' => $postcode]);
                return false;
            }
            $p = new Postcode();
            $p->postcode = $postcode;
            $p->latitude = $coordinates->results[0]->geometry->location->lat;
            $p->longitude = $coordinates->results[0]->geometry->location->lng;
            if (!$p->save()) {
                throw new Exception('Postcode save error. Sender is PostcodeService');
            }
            $p->refresh();
            $cachedPostcode = ArrayHelper::convertArToArray($p);
            Yii::$app->globalCache->setPostcode($cachedPostcode);
        }
        return $cachedPostcode;
    }


    /**
     * Caclulate distance between two geopoints (http://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php)
     *
     * @param $from Array with latitude and longitude keys
     * @param $to Array with latitude and longitude keys
     * @return float in meters
     */
    public function getDistance($from, $to) {
        // convert from degrees to radians
        $earthRadius = 6371000;
        $latFrom = deg2rad($from['latitude']);
        $lonFrom = deg2rad($from['longitude']);
        $latTo = deg2rad($to['latitude']);
        $lonTo = deg2rad($to['longitude']);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    public function isInBlacklist($client_key, $postcode)
    {
        $blacklist = Yii::$app->globalCache->getBlacklist($client_key, $postcode);

        return array_key_exists($postcode, $blacklist);
    }
} 