<?php

/**
 * Controller for Index
 * @author Edwin Hoksberg
 */
class IndexController extends Controller {

    public $_data = array();
    public $_page = "maintenance/index";

    function index() {

        if (!Settings::get('maintenance_mode')) {
            Url::redirect('home/index');
        }

        $this->_data['document_title'] = Settings::get('product_name') . " :: Under Construction";
        $this->_load->view($this->_page, true, $this->_data);
    }
}
