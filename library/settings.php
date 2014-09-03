<?php

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
