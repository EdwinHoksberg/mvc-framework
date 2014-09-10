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
        require_once(DIR_SYSTEM . 'database.php');
        Database::initialize();

        // load configuration
        require_once(DIR_SYSTEM . 'settings.php');

        // load logging library
        require_once(DIR_SYSTEM . 'log.php');

        // load additional system classes
        require_once(DIR_SYSTEM . 'load.php');
        require_once(DIR_SYSTEM . 'controller.php');
        require_once(DIR_SYSTEM . 'response.php');
        require_once(DIR_SYSTEM . 'language.php');
        require_once(DIR_SYSTEM . 'session.php');

        // check if timezone setting has been set
        $timezone = ini_get('date.timezone');
        if ($timezone === false || $timezone == '') {
            if (Settings::get('system_timezone')) {
                date_default_timezone_set(Settings::get('system_timezone'));
            } else {
                date_default_timezone_set('Europe/Amsterdam');
            }
        }
        // set the php error handler
        $errorHandler = new Log();
        set_error_handler(array(
            $errorHandler,
            'error_handler'
        ));

        // load helper libraries
        require_once(DIR_LIBRARY . 'url.php');
        require_once(DIR_LIBRARY . 'mail.php');
        require_once(DIR_LIBRARY . 'security.php');
        require_once(DIR_LIBRARY . 'upload.php');

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
