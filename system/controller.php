<?php

/**
 * All these variables can be used in a controller
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 * @last-modified 31-08-2014
 *
 */
class Controller extends Router {

    public $_load;
    public $_url;
    public $_lang;
    public $_session;

    function __construct() {
        $this->_load = new Load();
        $this->_url = new Url();

        $this->session = new Session();

        if (!empty($_COOKIE['lang']) && is_numeric($_COOKIE['lang'])) {
            $this->_lang = $_COOKIE['lang'];
        } else {
            $this->_lang = Settings::get('default_language');
        }
        $this->_data = array_merge($this->_data, Language::load($this->_page, $this->_lang));
    }
}
