<?php

class IndexController extends ControllerBase
{

    public function indexAction() {
        $this->assets
            ->addCss('css/style.css')
            ->addCss('libs/bootstrap/dist/css/bootstrap.min.css');

        $this->assets
            ->addJs('libs/jquery/dist/jquery.js')
            ->addJs('libs/bootstrap/dist/js/bootstrap.min.js');
    }

}

