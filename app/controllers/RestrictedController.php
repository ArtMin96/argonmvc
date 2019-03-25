<?php

namespace App\Controllers;
use Core\Controller;

class RestrictedController extends Controller {

    public function indexAction() {
        $this->view->render('restricted/index');
    }

    public function badTokenAction() {
        $this->view->render('restricted/badToken');
    }

    public function blockedAction() {
        $this->view->render('restricted/blocked');
    }

}
