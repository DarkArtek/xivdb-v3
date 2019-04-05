<?php

namespace XIVCommon\Language;

class LanguageConverter
{
    /**
     * Convert an array of data into a specific language
     */
    public static function handle($object, $language = LanguageList::DEFAULT)
    {
        $language = substr(strtolower($language), 0, 2);

        if (!is_array($object) && !is_object($object)) {
            return;
        }

        if (!in_array($language, LanguageList::LANGUAGES)) {
            $language = LanguageList::LANGUAGES[0];
        }
        
        foreach ($object as $key => $value) {
            if (is_object($value) || is_array($value)) {
                self::handle($value, $language);
                continue;
            }
            
            $postfix = '_'. $language;
            
            // test if this key is the language we want
            if (strpos($key, $postfix) !== false) {
                // assign a new key (with no postfix) to the language we put
                $newkey = str_ireplace($postfix, null, $key);
                $object->{$newkey} = $value;
            }
        }
    }
}
