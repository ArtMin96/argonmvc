<?php

namespace App\Controllers;
use Core\{Controller, Migration};
use Core\Security\Restricted;

class HomeController extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        // var_dump($this->banIP->writeBannedIPLog($this->banIP->getIP()));
        $this->banIP->isIPExists();
    }

    public function indexAction() {
        $this->view->render('home/index');
    }

    public function testAction() {
        $this->view->render('home/test');
    }

}
