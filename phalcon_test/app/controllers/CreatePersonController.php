<?php

class CreatePersonController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->assets
            ->addCss('css/style.css')
            ->addCss('libs/bootstrap/dist/css/bootstrap.min.css');

        $this->assets
            ->addJs('libs/angular/angular.min.js')
            ->addJs('libs/angular-resource/angular-resource.js')
            ->addJs('public/js/create_person_controller.js')
            ->addJs('libs/jquery/dist/jquery.js')
            ->addJs('libs/bootstrap/dist/js/bootstrap.min.js');
    }

}

