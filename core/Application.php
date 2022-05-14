<?php
namespace application\core;

class Application
{
    public static string $ROOT_DIRECTORY;
    public static Application $application;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $database;

    public function __construct()
    {
        self::$application = $this;

        $this->database = new Database();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        $this->router->resolve($this->request, $this->response);
    }
}
