<?php

/**
 * Controller for Index
 * @author Edwin Hoksberg
 */
class IndexController extends Controller {

    public $_data = array();
    public $_page = "home/index";

    function index($request) {

        $this->_data['document_title'] = Settings::get('product_name');

        $this->_load->view($this->_page, true, $this->_data);
    }
}
