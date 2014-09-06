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

    public static function get($name = null) {
        static $data = array();

        if (empty($data)) {
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
}
