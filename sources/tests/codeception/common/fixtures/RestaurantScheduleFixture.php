<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date 5/8/15
 * @time 12:53 PM
 */

namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

class RestaurantScheduleFixture extends  ActiveFixture
{
    public $modelClass = "common\\models\\RestaurantSchedule";
}