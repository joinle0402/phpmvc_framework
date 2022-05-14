<?php
namespace application\middlewares;

use application\core\Middleware;
use application\core\Request;
use application\core\Response;
use application\core\Session;
use application\models\AccountModel;

class AdminMiddleware implements Middleware
{
    public function handle(Request $request, Response $response)
    {
        if (preg_match('@/admin@', $request->path()))
        {
            if (Session::get('role') !== AccountModel::ROLE_ADMINISTRATOR)
            {
                $response->redirect('/login');
            }
        }
    }
}
