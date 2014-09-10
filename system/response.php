<?php

/**
 * The Response class, this class outputs the code and optionally compresses it
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 09-09-2014
 * @last-modified 10-09-2014
 *
 */
class Response {

    private $_output;
    private $_encoding;

    public function __construct($output) {
        $this->_output = $output;

        if (Settings::get('gzip_output')) {
            $this->compress();
        }
    }

    /**
     * This function will compress the html output of a page
     */
    private function compress() {
        if (!empty($this->_output)) {

            if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
                $this->_encoding = 'gzip';
            }

            if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip')) {
                $this->_encoding = 'x-gzip';
            }

            if (!empty($this->_encoding)) {
                $gz_level = Settings::get('gzip_level') ?: -1;
                $this->_output = gzencode($this->_output, $gz_level);
            }
        }
    }

    /**
     * This function will send all the html code to the browser, ready to display
     */
    public function output() {
        if (!empty($this->_output)) {

            if (!headers_sent() && !empty($this->_encoding)) {
                header('Content-Encoding: ' . $this->_encoding, true);
            }

            echo $this->_output;
        }
    }
}
