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

            $query = "SELECT `name`,`value` FROM {db_prefix}settings;";
            $result = $db->query($query);

            $cfg = array();
            foreach($result->rows as $row) {
                $cfg[$row['name']] = $row['value'];
            }
            $data = $cfg;
        }
        if ($name != null && array_key_exists($name, $data)) {
            return $data[$name];
        } else {
            return $data;
        }
    }
}
