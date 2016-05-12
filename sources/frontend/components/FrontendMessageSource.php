<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\caching\Cache;
use yii\db\Connection;
use yii\db\Query;
use yii\i18n\MessageSource;

/**
 * DbMessageSource extends [[MessageSource]] and represents a message source that stores translated
 * messages in database.
 *
 * The database must contain the following two tables:
 *
 * ~~~
 * CREATE TABLE source_message (
 *     id INTEGER PRIMARY KEY AUTO_INCREMENT,
 *     category VARCHAR(32),
 *     message TEXT
 * );
 *
 * CREATE TABLE message (
 *     id INTEGER,
 *     language VARCHAR(16),
 *     translation TEXT,
 *     PRIMARY KEY (id, language),
 *     CONSTRAINT fk_message_source_message FOREIGN KEY (id)
 *         REFERENCES source_message (id) ON DELETE CASCADE ON UPDATE RESTRICT
 * );
 * ~~~
 *
 * The `source_message` table stores the messages to be translated, and the `message` table stores
 * the translated messages. The name of these two tables can be customized by setting [[sourceMessageTable]]
 * and [[messageTable]], respectively.
 *
 * @author resurtm <resurtm@gmail.com>
 * @since 2.0
 */
class FrontendMessageSource extends MessageSource
{
    /**
     * Prefix which would be used when generating cache key.
     */
    const CACHE_KEY_PREFIX = 'FrontendMessageSource';

    /**
     * @var Cache|string the cache object or the application component ID of the cache object.
     * The messages data will be cached using this cache object. Note, this property has meaning only
     * in case [[cachingDuration]] set to non-zero value.
     * After the DbMessageSource object is created, if you want to change this property, you should only assign
     * it with a cache object.
     */
    public $cache = 'cache';
    
    /**
     * Translates the specified message.
     * If the message is not found, a [[EVENT_MISSING_TRANSLATION|missingTranslation]] event will be triggered.
     * If there is an event handler, it may provide a [[MissingTranslationEvent::$translatedMessage|fallback translation]].
     * If no fallback translation is provided this method will return `false`.
     * @param string $category the category that the message belongs to.
     * @param string $message the message to be translated.
     * @param string $language the target language.
     * @return string|boolean the translated message or false if translation wasn't found.
     */
    protected function translateMessage($category, $message, $language)
    {
        $label = self::getLabelName($message);
        if(!$label){
            return '';
        }
        $label = $this->getCategoryCode($category).$label;
        return Yii::$app->{$this->cache}->getLabelByLanguage($language, $label, $message);
    }
    
    /**
     * 
     */
    public static function getLabelName($string){
        $string = str_replace(' ', '_', $string);
        $string = preg_replace('/[^A-Za-z0-9\_\?\!]/', '', $string);
        $string = strtoupper($string);
        return preg_replace('/-+/', '_', $string);
    }
    
    /**
     * 
     */
    private function getCategoryCode($categoryName){
        switch($categoryName){
            case 'label':
               $return = 'LBL';
            break;
            case 'error':
               $return = 'ERR';
            break;
            default:
                $return = '';
            break;    
        }
        if($return){
            $return .= '_';
        }
        return $return;
    }
    
}
