<?php

namespace tests\codeception\common\unit\models;

use Yii;
use Codeception\Specify;
use tests\codeception\common\fixtures\CurrencyFixture;

/**
 * Login form test
 */
class CurrencyTest extends DineinDbTestCase
{
    use Specify;

    public function testCurrencyCreateDelete()
    {
        $fixtures = require Yii::getAlias('@tests/codeception/common/unit/fixtures/data/models/currency.php');
        Yii::$app->set(
            'globalCache',
            \Codeception\Util\Stub::make(
                '\common\components\cache\GlobalCache',
                [
                    'loadCurrencies' => \Codeception\Util\Stub::atLeastOnce(),
                    'getLabel'       => function ($label) {
                        return $label;
                    }
                ]
            )
        );
        parent::testCreateDelete(
            "\\common\\models\\Currency",
            $fixtures[0],
            [
                'code' => 'UAH',
                'symbol' => '^',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            "currency" => [
                'class' => CurrencyFixture::className(),
            ],
        ];
    }

}
