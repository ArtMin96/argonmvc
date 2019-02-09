<?php

namespace App\Controllers;
use Core\Controller;
use Core\Security\Restricted;

class HomeController extends Controller {

    public function onConstruct() {
        $this->banIP->isIPExists();
    }

    public function indexAction() {
        $this->view->render('home/index');
    }

}
