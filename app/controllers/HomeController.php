<?php

namespace App\Controllers;
use Core\Controller;
use Core\Security\Restricted;

class HomeController extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->banIP->isIPExists();
    }

    public function indexAction() {
        $this->view->render('home/index');
    }

    public function testAction() {
        $this->view->render('home/test');
    }

}
