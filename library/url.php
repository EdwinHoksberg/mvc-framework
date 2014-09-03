<?php

/**
 * This class is to help with creating pages and navigating the site
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 * @todo add functions like `getcurrenturl` + more
 */
final class Url {

    public static function segment($key) {
        if (!empty($_GET['route'])) {
            $segments = explode('/', $_GET['route']);
            return (empty($segments[$key])) ? false : $segments[$key];
        } else {
            return false;
        }
    }

    public static function segments() {
        if (!empty($_GET['route'])) {
            return explode('/', $_GET['route']);
        } else {
            return false;
        }
    }

    /**
     * @param string $location
     * @param string $action
     * @param array  $get_data
     * @param int    $error_code
     */
    public static function redirect($location, $action = '', $get_data = array(), $error_code = 302) {
        $url = HTTP_SERVER . $location;

        if (!empty($action)) {
            $url .= '/' . $action;
        }

        foreach($get_data as $get) {
            $url .= '&' . $get['name'] . '=' . $get['value'];
        }

        header("Location: " . $url, true, $error_code);
        exit;
    }

    /**
     * @param string $location
     * @param string $action
     * @param array  $get_data
     * @param string $hash
     *
     * @return string
     */
    public function create($location, $action = '', $get_data = array(), $hash = '') {
        $url = HTTP_SERVER . 'index.php?route=' . $location;

        if (!empty($action)) {
            $url .= '&action=' . $action;
        }

        foreach($get_data as $get) {
            $url .= '&' . $get['name'] . '=' . $get['value'];
        }

        if (!empty($hash)) {
            $url .= '#' . $hash;
        }

        return $url;
    }
}
