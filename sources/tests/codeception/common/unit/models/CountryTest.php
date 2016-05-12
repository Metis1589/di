<?php

namespace tests\codeception\common\unit\models;

use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\CountryFixture;
use Codeception\Util\Stub;

/**
 * Login form test
 */
class CountryTest extends DineinDbTestCase
{
    use Specify;

    public function testCountryCreateDelete()
    {
        $fixtures = require Yii::getAlias('@tests/codeception/common/unit/fixtures/data/models/country.php');
        Yii::$app->set(
            'globalCache',
            Stub::make(
                '\common\components\cache\GlobalCache',
                ['getLabel'=>function($label){
                    return $label;
                }]
            )
        );
        parent::testCreateDelete(
            "\\common\\models\\Country",
            $fixtures[0],
            [
                'name_key' => 'Country Name Upd',
                'native_name' => 'Native Name Upd',
                'iso_code' => 'CC',
            ]);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "country" => [
                'class' => CountryFixture::className(),
            ],
        ];
    }

}
