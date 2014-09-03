<?php

/**
 * The main router file
 * @author Edwin Hoksberg
 */
class Router {

    function start() {
        // check for php version 5.3 or higher
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            exit('This script requires PHP version 5.3.0 or higher.');
        }

        // start session
        session_start();

        // require config file
        require_once('config.php');

        // load database functions
        require_once(DIR_LIBRARY . 'database.php');

        // load configuration
        require_once(DIR_LIBRARY . 'settings.php');

        // check if timezone setting has been set
        $timezone = ini_get('date.timezone');
        if ($timezone === false || $timezone == '') {
            if (Settings::get('system_timezone')) {
                date_default_timezone_set(Settings::get('system_timezone'));
            } else {
                date_default_timezone_set('Europe/Amsterdam');
            }
        }

        // load librarys and main functions
        require_once(DIR_LIBRARY . 'log.php');
        $errorHandler = new Log();
        set_error_handler(array(
            $errorHandler,
            'error_handler'
        ));

        require_once(DIR_LIBRARY . 'load.php');
        require_once(DIR_LIBRARY . 'controller.php');
        require_once(DIR_LIBRARY . 'language.php');
        require_once(DIR_LIBRARY . 'session.php');
        require_once(DIR_LIBRARY . 'url.php');

        //all url segments
        $segments = Url::segments();

        //set default action
        $action = 'index';

        // set requested page
        if (Settings::get('maintenance_mode') && empty($_SESSION['session_id'])) {
            $controller = 'maintenance';
            $function = 'index';
        } else if (!empty($segments)) {
            if (count($segments) >= 2) {
                $controller = Url::segment(0);
                $function = Url::segment(1);
            } else if (count($segments) >= 3) {
                $controller = Url::segment(0);
                $function = Url::segment(1);
                $action = Url::segment(3);
            } else {
                $controller = 'home';
                $function = 'index';
            }
        } else {
            $controller = 'home';
            $function = 'index';
        }

        // load page controller
        $load = new Load();
        $load->controller($controller . '/' . $function, $action);
    }
}

$router = new Router();
$router->start();