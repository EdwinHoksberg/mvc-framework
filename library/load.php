<?php

/**
 * This (messy) class loads views, controllers and models
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 * @todo refactoring
 */
final class Load {

    function __construct() {
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
                require(DIR_CATALOG . 'view/templates/header.tpl');
                require($template);
                require(DIR_CATALOG . 'view/templates/footer.tpl');
            } else {
                require $template;
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            require(DIR_CATALOG . 'view/templates/404.tpl');
        }
    }

    /**
     * Load a new controller
     *
     * @param string $controller
     * @param string $action
     *
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
     * @param string $page
     */
    public function model($page) {
        $tokens = explode("/", $page);
        $file = DIR_SERVER . 'catalog/model/' . $tokens[0] . '/' . ucfirst($tokens[1]) . 'Model.php';
        if (is_readable($file)) {
            require_once($file);
        } else {
            Log::error("<b>Error:</b> Model {$page} in {$file} not found", "Error: Model {$page} in {$file} not found");
            //$this->view("errors/404", false);
        }
    }
}
