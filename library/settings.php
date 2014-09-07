<?php

/**
 * This class will get all the settings out of the database
 * and make them available
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 * @todo Extend with more functionality
 */
final class Settings extends Router {

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
            $db = new Database();

            $result = $db->select("
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

        $db = new Database();

        $alreadyExists = $db->select("SELECT `name` FROM {DB_PREFIX}settings WHERE `name` = :name",
            array(
                ":name" => $name
            )
        );

        if (count($alreadyExists) > 0) { // update existing setting

            return $db->update("settings",
                array( // values
                    "name" => $name,
                    "value" => $value
                ),
                array( // where
                    "name" => $name
                )
            );
        } else { // insert new setting in database

            return $db->insert("settings",
                array( // values
                    "name" => $name,
                    "value" => $value
                )
            );
        }


    }
}
