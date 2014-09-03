<?php

/**
 * The Log class; this will help logging and/or displaying error messages
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 * @todo add info and more levels or logging
 */
final class Log {

    /**
     * Function that handles error logging and displaying
     *
     * @param      $txt_message - An string that will be provided to the user
     * @param      $html_message - An string that will be saved to the log file
     * @param bool $exit - Whether to exit the application
     */
    public static function error($txt_message, $html_message, $exit = false) {
        $logfile = DIR_SERVER . 'logs/error.txt';

        $txt_message = $txt_message . PHP_EOL;
        $html_message = $html_message . '<br />';

        if (Settings::get('display_errors')) {
            echo $html_message;
        }

        if (Settings::get('log_errors')) {
            if (is_writeable($logfile)) {
                file_put_contents($logfile, $txt_message, FILE_APPEND);
            } else {
                die('<b>Error log is not writable!</b>');
            }
        }

        if ($exit) {
            exit;
        }
    }

    public function error_handler($number, $message, $file, $line) {
        if (!(error_reporting() & $number)) {
            return false;
        }

        switch($number) {
            case E_ERROR:
                $txt_error = "Fatal error: " . $message . "on line " . $line . "in file " . $file;
                $html_error = "<b>Fatal error: </b>" . $message . "on line <b>" . $line . "</b> in file <b>" . $file . "</b>";
                self::error($txt_error, $html_error, true);
                break;

            case E_WARNING:
                $txt_error = "Warning: " . $message . " on line " . $line . "in file " . $file;
                $html_error = "<b>Warning: </b>" . $message . " on line <b>" . $line . "</b> in file <b>" . $file . "</b>";
                self::error($txt_error, $html_error);
                break;

            case E_NOTICE:
                $txt_error = "Notice: " . $message . " on line " . $line . "in file " . $file;
                $html_error = "<b>Notice: </b>" . $message . " on line <b>" . $line . "</b> in file <b>" . $file . "</b>";
                self::error($txt_error, $html_error);
                break;

            default:
                $txt_error = "Unknown error: " . $message . " on line " . $line . "in file " . $file;
                $html_error = "<b>Unknown error: </b>" . $message . " on line <b>" . $line . "</b> in file <b>" . $file . "</b>";
                self::error($txt_error, $html_error);
                break;
        }

        return true;
    }
}
