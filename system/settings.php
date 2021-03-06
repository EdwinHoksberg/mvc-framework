<?php

/**
 * This class will get all the settings out of the database
 * and make them available
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 * @last-modified 08-09-2014
 *
 */
class Settings {

    /**
     * This function will get any settings from the database.
     * You should set the forceUpdate parameter to true if you inserted
     * an new value into te database, or it won't be available.
     *
     * @param null $name
     * @param bool $forceUpdate
     * @return string|bool
     */
    public static function get($name = null, $forceUpdate = false) {
        static $data = array();

        if (empty($data) || $forceUpdate) {

            $result = Database::select("
            SELECT
                `name`,
                `value`
              FROM ". DB_PREFIX ."settings");

            foreach($result as $row) {
                $data[$row->name] = $row->value;
            }
        }

        if ($name != null && array_key_exists($name, $data)) {
            return $data[$name];
        } else {
            return false;
        }
    }

    /**
     * This function will insert an new value into the database,
     * and if it already exists, it will overwrite it.
     *
     * @param $name - The value name
     * @param $value - The value
     * @return bool
     */
    public static function set($name, $value) {

        $alreadyExists = Database::select("SELECT `name` FROM {DB_PREFIX}settings WHERE `name` = :name",
            array(
                ":name" => $name
            )
        );

        if (count($alreadyExists) > 0) { // update existing setting

            return Database::update("settings",
                array( // values
                    "name" => $name,
                    "value" => $value
                ),
                array( // where
                    "name" => $name
                )
            );
        } else { // insert new setting in database

            return Database::insert("settings",
                array( // values
                    "name" => $name,
                    "value" => $value
                )
            );
        }


    }
}
