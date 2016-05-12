<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   5/8/15
 * @time   10:55 AM
 */

namespace common\components;


use yii\i18n\MessageSource;

class DummyMessageSource extends MessageSource
{
    public $cache;

    protected function translateMessage($category, $message, $language)
    {
        return $message;
    }
}