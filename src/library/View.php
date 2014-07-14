<?php

namespace library;

/**
 * Obsługa widoków
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class View
{

    /**
     * Generuje widok z wykorzystaniem pliku layout'u
     *
     * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
     *
     * @param String $method nazwa metody której pobrać widok
     *
     * @throws Exception
     */
    public function render($method, $params = array(), $error = false)
    {
        $exploded_method = explode('::', $method);

        //e.g. 'index/index'
        if(count($exploded_method) < 2)
        {
            $exploded_view_name = explode('/', $method);
            $controller = $exploded_view_name[0];
            $action = $exploded_view_name[1];
        }
        //e.g. apps/controllers/IndexController::IndexAction
        else
        {
            $lastNamespaceStringPosition = strripos($exploded_method[0], '\\');
            $controller = str_replace('Controller', '', lcfirst(substr($exploded_method[0], $lastNamespaceStringPosition + 1)));
            $action = str_replace('Action', '', lcfirst($exploded_method[1]));
        }

        //name of a view file
        $file_name = $controller . DIRECTORY_SEPARATOR . $action . '.phtml';

        //path to view file
        $view_file = VIEWS_PATH . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . $file_name;

        //which layout should be used
        $layout_name = $error ? 'error' : 'layout';
        $layout_file = VIEWS_PATH . DIRECTORY_SEPARATOR . $layout_name . '.phtml';

        //generate content
        $params['content'] = $this->renderViewWithParams($view_file, $params);
        //generate layout with content
        echo $this->renderViewWithParams($layout_file, $params);
    }

    /**
     * Generuje widok dla danej akcji, widok pobrany z szablonu
     *
     * @param String $view_file plik szablonu
     *
     * @param array $params parametry przekazywane do szablonu
     *
     * @return String
     * @throws \Exception
     */
    public function renderViewWithParams($view_file, $params)
    {
        if(!is_readable($view_file))
        {
            throw new \Exception("View file $view_file not found");
        }

        extract($params);
        ob_start();

        include $view_file;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
