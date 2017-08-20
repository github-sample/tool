<?php
class View {
     
    public function __construct()
    {
    }
     
    /**
     * 画面を表示する
     */
    public function display($data)
    {
        include('template/index.tpl');
    }
}

