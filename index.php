<?php

/**
 * The main router file
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 * @last-modified 10-09-2014
 *
 */
class Router {

    function start() {
        // check for php version 5.3 or higher
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            exit('This script requires PHP version 5.3.0 or higher.');
        }

        // start session
        session_start();

        // require config file if it exists
        if (is_readable('config.php')) {
            require_once('config.php');
        } else {
            die('config.php not found');
        }

        // load database functions
        require_once(DIR_LIBRARY . 'database.php');
        Database::initialize();

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

        // load logging library
        require_once(DIR_LIBRARY . 'log.php');

        // set the php error handler
        $errorHandler = new Log();
        set_error_handler(array(
            $errorHandler,
            'error_handler'
        ));

        // load all necessary libraries
        require_once(DIR_LIBRARY . 'load.php');
        require_once(DIR_LIBRARY . 'controller.php');
        require_once(DIR_LIBRARY . 'response.php');
        require_once(DIR_LIBRARY . 'language.php');
        require_once(DIR_LIBRARY . 'session.php');
        require_once(DIR_LIBRARY . 'url.php');

        // set requested page and controller
        if (Settings::get('maintenance_mode') && empty($_SESSION['session_id'])) {
            $controller = 'maintenance';
            $action = 'index';
        } else {
            $controller = Url::getController();
            $action = Url::getAction();
        }

        // load page controller and fetch the page contents
        $load = new Load();
        $output = $load->dispatch($controller, $action);

        // process the page and output it
        $response = new Response($output);
        $response->output();
    }
}

$router = new Router();
$router->start();
