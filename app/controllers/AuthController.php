<?php

namespace App\Controllers;
use Core\{Controller, Router, Session};
use App\Models\{Users, Login};

class AuthController extends Controller {
    
    public function onConstruct() {
        $this->view->setLayout('default');
    }
    
    public function loginAction() {
        $loginModel = new Login();
        if($this->request->isPost()) {
            // form validation
            $this->request->csrfCheck();
            $loginModel->assign($this->request->get());
            $loginModel->validator();
            if($loginModel->validationPassed()) {
                $user = Users::findByUsername($this->request->get('username'));
                if($user && password_verify($this->request->get('password'), $user->password)) {
                    $remember = $loginModel->getrememberMeChecked();
                    $user->login($remember);
                    Router::redirect('');
                } else {
                    $loginModel->addErrorMessage('username', 'There is an error with your username or password.');
                }
            }
        }
        $this->view->login = $loginModel;
        $this->view->displayErrors = $loginModel->getErrorMessages();
        $this->view->render('auth/login');
    }
    
    public function logoutAction() {
        if(Users::currentUser()) {
            Users::currentUser()->logout();
        }
        Router::redirect('home');
    }
    
    public function signupAction() {
        $newUser = new Users();
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $newUser->assign($this->request->get(), Users::blackListedFormKeys);
            $newUser->confirm = $this->request->get('confirm');
            if($newUser->save()) {
                Router::redirect('auth/login');
            }
        }
            
        $this->view->newUser = $newUser;
        $this->view->displayErrors = $newUser->getErrorMessages();
        $this->view->render('auth/signup');
    }
    
}