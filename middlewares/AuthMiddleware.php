<?php
namespace application\middlewares;

use application\core\Middleware;
use application\core\Request;
use application\core\Response;
use application\core\Session;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, Response $response)
    {
        if (Session::empty('admin_login'))
        {
            $response->redirect('/login');
        }
    }
}
