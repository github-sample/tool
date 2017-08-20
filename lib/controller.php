<?php
class Controller {
    public function __construct()
    {
    }

    /**
    * index.phpから実行される関数
    */
    public function execute()
    {
        require_once('model.php');
        require_once('view.php');
        $modelInstance = new Model();
        $viewInstance = new View();

        if (!empty($_POST)) {
            $data = $modelInstance->dispatch();
        }
        $viewInstance->display($data);

    }
}

