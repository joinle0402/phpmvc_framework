<?php

namespace application\core;

class Controller
{
    public function __construct()
    {
        
    }

    public function view($viewPath, $parameters = [])
    {
        Application::$application->router->renderView($viewPath, $parameters);
    }
}
