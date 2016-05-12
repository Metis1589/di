<?php

namespace admin\common;

use Yii;

class StringHelper {
    
    public static function SubstrForTable($str, $n) {
        
        $str = substr($str,0,$n-4);
        $str = (strlen($str) == $n-4) ? $str . ' ...' : $str; 
        return $str;
    }

    public static function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    public function hasSubString($str, $needle) {
        return (strpos($str, $needle) !== FALSE);
    }
    
}
