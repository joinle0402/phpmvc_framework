<?php
namespace application\controllers\client;

use application\core\Controller;
use application\core\Request;
use application\core\Response;
use application\core\Session;
use application\models\AccountModel;

class AuthController extends Controller
{
    public function loginForm()
    {
        $parameters = [];
        $parameters['message'] = Session::flash('message');
        $parameters['error'] = Session::flash('error');
        $parameters['remember'] = $_COOKIE['remember'] ?? false;
        $parameters['rememberUsername'] = $_COOKIE['rememberUsername'] ?? '';
        $parameters['rememberPassword'] = $_COOKIE['rememberPassword'] ?? '';

        return $this->view('pages.auth.login', $parameters );
    }

    public function registerForm(Request $request)
    {
        return $this->view('pages.auth.register', [
            'account' => Session::flash('account'),
            'errors' => Session::flash('errors')
        ]);
    }

    public function login(Request $request, Response $response)
    {
        $request->setRules([ "username" => 'required', "password" => 'required' ]);

        if (!$request->validate())
        {
            Session::flash('error', "Username hoặc Password để trống!!");
            return $response->redirect('/login');
        }

        $accountModel = new AccountModel($request->getDataRequest());
        $account = $accountModel->findAccountByUsername()[0];

        if (empty($account))
        {
            Session::flash('error', "Username không tồn tại!!");
            return $response->redirect('/login');
        }

        if (!password_verify($accountModel->getPassword(), $account['password']))
        {
            Session::flash('error', "Password không hợp lệ!!");
            return $response->redirect('/login');
        }

;
        Session::set('id', $account['id']);
        Session::set('username', $account['username']);
        Session::set('role', $account['role']);

        $remember = isset($request->getDataRequest()['remember']);

        if ($remember)
        {
            setcookie('remember', $remember, time() + 60 * 60 * 24 * 6 * 30);
            setcookie('rememberUsername', $request->getDataRequest()['username'], time() + 60 * 60 * 24 * 6 * 30);
            setcookie('rememberPassword', $request->getDataRequest()['password'], time() + 60 * 60 * 24 * 6 * 30);
        }
        else
        {
            if (isset($_COOKIE['remember']))
            {
                setcookie('remember', "");
            }
            if (isset($_COOKIE['rememberUsername']))
            {
                setcookie('rememberUsername', "");
            }
            if (isset($_COOKIE['rememberUsername']))
            {
                setcookie('rememberPassword', "");
            }
        }

        return $response->redirect('/');
    }

    public function register(Request $request, Response $response)
    {
        $request->setRules([
            "firstName" => "required|min:3|max:40",
            "lastName" => "required|min:3|max:40",
            "email" => 'required|email|min:6|unique:accounts',
            "username" => 'required|min:5|max:40|unique:accounts',
            "password" => 'required|min:5|max:40',
            "confirmPassword" => 'required|min:5|max:40|match:password',
        ]);

        if ($request->validate())
        {
            $accountModel = new AccountModel($request->getDataRequest());
            $accountModel->register();

            Session::flash('message', "Đăng ký tài khoản thành công!!!");

            return $response->redirect('/login');
        }

        Session::flash('errors', $request->getFirstErrors());
        Session::flash('account', $request->getDataRequest());

        return $response->redirect('/register');
    }

    public function logout(Request $request, Response $response)
    {
        Session::remove('id');
        Session::remove('role');
        Session::remove('username');

        return $response->redirect('/login');
    }

}
