<?php
namespace common\components\cache;

use yii\base\Component;
use Yii;

class Cache extends Component
{
    public $innerKey;
    
    public function getValue($key)
    {
        $cachedValue = Yii::$app->cache->get($key.$this->innerKey);
        if ($cachedValue) {
            return unserialize($cachedValue);
        }
    }

    public function setValue($key, $value, $duration = 0, $dependency = null)
    {
        Yii::$app->cache->set($key.$this->innerKey, serialize($value), $duration, $dependency);
    }

    public function deleteValue($key)
    {
        Yii::$app->cache->delete($key.$this->innerKey);
    }
}