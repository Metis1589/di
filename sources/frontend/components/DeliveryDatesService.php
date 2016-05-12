<?php
/**
 * Created by PhpStorm.
 * User: Serginnios
 * Date: 5/12/2015
 * Time: 10:37 PM
 */

namespace frontend\components;


use DateInterval;
use DateTime;
use yii\base\Component;

class DeliveryDatesService extends Component {

    public $today_time_delay;

    public $from_time;

    public $to_time;

    public $time_interval;

    public $number_of_days;

    public function generateDeliveryDates()
    {
        $result = [];
        for($i = 0; $i < $this->number_of_days; $i++) {
            $date = (new DateTime('today'))->add(new DateInterval('P'.$i.'D'));
            $dateAsString =  $date->format('Y-m-d');
            $timeFrom =  new DateTime($dateAsString. ' ' . $this->from_time);
            $timeTo =  new DateTime($dateAsString. ' ' . $this->to_time);
            if ($i == 0) {
                $timeFrom = (new DateTime('now'))->add(new DateInterval('PT'.$this->today_time_delay.'M'));
                $second = $timeFrom->format("s");
                $timeFrom->add(new DateInterval("PT".(60-$second)."S"));
                $minute = $timeFrom->format("i");
                $minute = $minute % 10;
                $diff = 10 - $minute;
                $timeFrom->add(new DateInterval("PT".$diff."M"));
            }
            while($timeFrom <= $timeTo) {
                $start = $timeFrom->format('H:i');
                $timeFrom->add(new DateInterval('PT' . $this->time_interval . 'M'));
                $result[$dateAsString][] = $start . '-' . $timeFrom->format('H:i');
            }
        }
        return $result;
    }

} 