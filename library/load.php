<?php

/**
 * This (messy) class loads views, controllers and models
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 * @last-modified 10-09-2014
 *
 */
final class Load {

    /**
     * This function will start all the page logic, capture its output,
     * and send it back for processing.
     *
     * @param $controller
     * @param $action
     *
     * @return string
     */
    public function dispatch($controller, $action) {

        ob_start();

        $this->_controller = $controller;
        $this->_action = $action;

        $this->controller($controller, $action);

        return ob_get_clean();
    }

    /**
     * Load a new view
     *
     * @param string $page
     * @param bool   $templates
     * @param array  $data []
     */
    public function view($page, $templates = true, $data = null) {
        $template = DIR_SERVER . 'catalog/view/' . $page . '.tpl';
        if (is_readable($template)) {

            if (!empty($data)) {
                extract($data);
                unset($data);
            }

            if ($templates) {
                require(DIR_CATALOG . 'view/header.tpl');
                require($template);
                require(DIR_CATALOG . 'view/footer.tpl');
            } else {
                require($template);
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            require(DIR_CATALOG . 'view/templates/404.tpl');
        }
    }

    /**
     * Load a new controller
     *
     * @internal param string $controller
     * @internal param string $action
     */
    public function controller($controller, $action) {

        $file = DIR_CATALOG . 'controller/' . $controller . '/' . ucfirst($action) . 'Controller.php';
        if (is_readable($file)) {

            require_once($file);
            $class = ucfirst($action) . 'Controller';
            $controller = new $class;

            if (method_exists($controller, $action)) {
                $controller->$action(Url::getRequestParameters());
            } else {
                Log::error("<b>Error:</b> Function {$action} in {$controller} not found", "Error: Function {$action} in {$controller} not found");
                $controller->index(Url::getRequestParameters());
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            $this->view("templates/404", true, array('page' => $controller, 'action' => $action));
        }
    }

    /**
     * Load a new model
     *
     * @param $controller
     * @param $action
     */
    public function model($controller, $action) {
        $file = DIR_SERVER . 'catalog/model/' . $controller . '/' . ucfirst($action) . 'Model.php';
        if (is_readable($file)) {
            require_once($file);
        } else {
            Log::error("<b>Error:</b> Model {$action} in {$file} not found", "Error: Model {$action} in {$file} not found");
            //$this->view("errors/404", false);
        }
    }
}
