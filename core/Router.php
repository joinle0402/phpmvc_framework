<?php

namespace application\core;

class Router
{
    protected static array $routes = [];
    protected Request $request;
    protected Response $response;
    protected string $uri = '';

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public static function get($path, $callback)
    {
        self::$routes['GET'][$path] = $callback;
    }

    public static function post($path, $callback)
    {
        self::$routes['POST'][$path] = $callback;
    }

    public function getCallback()
    {
        $path = $this->request->path();
        $path = trim($path, '/');
        $method = $this->request->method();

        $routes = self::$routes[$method] ?? [];
        $routeParameters = false;

        foreach ($routes as $route => $callback)
        {
            $this->setUri($route);

            $route = trim($route, '/');
            $routeParameterNames = [];

            if (empty($route)) continue;

            // /admin/quizzes/{id} => id
            // /admin/quizzes/{id:\d+}/{username} => [id, username]
            // find all route parameter names from route
            if (preg_match_all('~\{(\w+)(:[^}]+)?\}~', $route, $matches))
            {
                $routeParameterNames = $matches[1];
            }

            // convert route parameter names into regex pattern
            // /admin/quizzes/{id} => /admin/quizzes/(\w+)
            // /admin/quizzes/{id:\d+}/{username} => /admin/quizzes/(\d+)/(\w+)
            $routeRegex = preg_replace_callback('~\{\w+(:([^}]+))?}~', fn ($match) => isset($match[2]) ? "($match[2])" : '(\w+)', $route);
            $routeRegex = "@^$routeRegex$@";


            if (preg_match_all($routeRegex, $path, $matches))
            {
                $routeParameterValues = [];
                for ($index = 1; $index < count($matches); $index++)
                {
                    $routeParameterValues[] = $matches[$index][0];
                }
                $routeParameters = array_combine($routeParameterNames, $routeParameterValues);

                $this->request->setRouteParameters($routeParameters);

                return $callback;
            }
        }

        return false;
    }

    public function resolve(Request $request, Response $response)
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $callback = self::$routes[$method][$path] ?? false;

        if ($callback === false)
        {
            $callback = $this->getCallback();

            if ($callback === false)
            {
                return $this->renderView('pages._404');
            }
        }

        if (is_string($callback))
        {
            return $this->renderView($callback);
        }

        if (is_array($callback))
        {
            $callback[0] = new $callback[0];
        }

        $this->handleGlobalMiddleware();
        $this->handleRouteMiddleware($this->getUri());

        call_user_func($callback, $request, $response);
    }

    public function handleRouteMiddleware(string $uri = '')
    {
        global $configurations;

        if (!empty($configurations['application']['routeMiddlewares']))
        {
            $routeMiddlewares = $configurations['application']['routeMiddlewares'];

            if (is_array($routeMiddlewares))
            {
                foreach ($routeMiddlewares as $routeUri => $routeMiddleware)
                {
                    $fileName = explode('\\', $routeMiddleware);
                    $fileName = end($fileName);

                    if ($routeUri === $uri && file_exists(transformDirectoryPath(ROOT_DIRECTORY."/middlewares/$fileName.php")))
                    {
                        require_once transformDirectoryPath(ROOT_DIRECTORY."/middlewares/$fileName.php");

                        if (class_exists($routeMiddleware))
                        {
                            $routeMiddlewareInstance = new $routeMiddleware();
                            $routeMiddlewareInstance->handle($this->request, $this->response);
                        }
                    }
                }
            }
        }
    }

    public function handleGlobalMiddleware()
    {
        global $configurations;

        if (!empty($configurations['application']['globalMiddlewares']))
        {
            $globalMiddlewares = $configurations['application']['globalMiddlewares'];

            if (is_array($globalMiddlewares))
            {
                foreach ($globalMiddlewares as $globalMiddleware)
                {
                    $fileName = explode('\\', $globalMiddleware);
                    $fileName = end($fileName);

                    if (file_exists(transformDirectoryPath(ROOT_DIRECTORY."/middlewares/$fileName.php")))
                    {
                        require_once transformDirectoryPath(ROOT_DIRECTORY."/middlewares/$fileName.php");

                        if (class_exists($globalMiddleware))
                        {
                            $globalMiddlewareInstance = new $globalMiddleware();
                            $globalMiddlewareInstance->handle($this->request, $this->response);
                        }
                    }
                }
            }
        }
    }

    public function renderView($viewPath, $parameters = [])
    {
        extract($parameters);

        $viewContent = $this->viewContent($viewPath, $parameters);

        eval("?>$viewContent<?php");
    }

    public function viewContent($viewPath, $parameters = [])
    {
        $viewPath = str_replace('.', '/', $viewPath);

        $contentView = null;
        if (file_exists(ROOT_DIRECTORY . "/views/$viewPath.blade.php"))
        {
            $contentView = file_get_contents(ROOT_DIRECTORY . "/views/$viewPath.blade.php");
        }

        $templateEngine = new TemplateEngine($contentView);

        return $templateEngine->run();
    }

	/**
	 *
	 * @return string
	 */
	function getUri(): string {
		return $this->uri;
	}

	/**
	 *
	 * @param string $uri
	 * @return Router
	 */
	function setUri(string $uri): self {
		$this->uri = $uri;
		return $this;
	}
}
