<?php

class BloglarController extends Page{
    public $bloglar;

    protected function preprocessPage()
    {
        $this->bloglar = Blog::getAll([]);
    } 

    public function echoContent(){
        include "bloglar_html.php";
        echo_bloglar_page($this);
    }

    public function check_access(): bool
    {
        return true;
    }
} 