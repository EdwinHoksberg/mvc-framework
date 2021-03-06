<?php

/**
 * The language class will load all the neccesary languages for the page
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 * @last-modified 08-09-2014
 *
 */
class Language {

    /**
     * This function will return all the language needed for the appropiate page.
     *
     * @param $file
     * @param $lang_id
     *
     * @return array
     */
    public static function load($file, $lang_id) {
        $data = array();

        if (empty($lng)) {
            $result = Database::select("SELECT `name` FROM ". DB_PREFIX ."language",
                array(
                    'language_id' => $lang_id
                )
            );
            $currentLang = $result[0]->name;

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
