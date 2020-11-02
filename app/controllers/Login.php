<?php

namespace app\controllers;

use app\classes\Flash;
use app\classes\Login as UserLogin;
use app\classes\Validate;

class Login extends Base
{
    private $login;

    public function __construct()
    {
        $this->login = new UserLogin;
    }

    public function index($request, $response)
    {
        $messages = Flash::getAll();
        return $this->getTwig()->render($response, $this->setView('site/login'), [
            'title' => 'Home',
            'messages' => $messages
        ]);
    }

    public function store($request, $response)
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $validate = new Validate;
        $validate->required(['email', 'password'])->email($email);
        $errors = $validate->getErrors();

        if ($errors) {
            $response->getBody()->write(json_encode($errors));

            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withSTatus(401);
        }

        $logged = $this->login->login($email, $password);

        if ($logged) {
            $response->getBody()->write(json_encode(['logged' => 'Logado com sucesso']));
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withSTatus(200);
        }

        $response->getBody()->write(json_encode(['error_login' => 'Usuário ou senha inválidos']));

        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withSTatus(401);
    }

    public function destroy($request, $response)
    {
        $this->login->logout();

        return redirect($response, '/');
    }
}
