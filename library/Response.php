<?php

/**
 * The Response class
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 09-09-2014
 *
 * @todo add compression
 */
class Response {

    private $_output;
    private $_headers = array();

    public function __construct($output) {
        $this->_output = $output;

        if (Settings::get('gzip_output')) {
            $this->compress();
        }
    }

    private function compress() {
        if (!empty($this->_output)) {

            if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {

                $this->_headers[] = 'Content-Encoding: gzip';

                $gz_level = Settings::get('gzip_level') ?: -1;
                $this->_output = gzencode($this->_output, $gz_level);
            }
        }
    }

    public function output() {
        if (!empty($this->_output)) {

            if (!headers_sent()) {
                foreach($this->_headers as $header) {
                    header($header, true);
                }
            }

            echo $this->_output;
        }
    }
}
