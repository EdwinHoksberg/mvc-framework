<?php

/**
 * Functions for loading language files
 * @author Edwin Hoksberg
 */
final class Language {

    public static function load($file, $lang_id) {
        $data = array();

        if (empty($lng)) {
            $db = new Database();
            $result = $db->query("SELECT `name` FROM {db_prefix}language WHERE `language_id`=" . $db->escape($lang_id));
            $currentLang = $result->row['name'];

            $defaultFile = DIR_LANGUAGE . 'english/' . $file . '.lng';

            $langDir = DIR_LANGUAGE . $currentLang . '/';
            $pageFile = $langDir . $file . '.lng';
            $mainFile = $langDir . $currentLang . '.lng';

            if (is_readable($pageFile) || is_readable($mainFile)) {
                require_once($pageFile);
                require_once($mainFile);
                $data = array_merge($data, $lng);
            } else {
                if (is_readable($defaultFile)) {
                    require_once($defaultFile);
                    $data = array_merge($data, $lng);
                } else {
                    Log::error('<b>Error:</b> Language files not found!', 'Error: language files not found', false);
                }
            }
        }

        return $data;
    }
}
