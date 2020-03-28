<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
ini_set( 'date.timezone', 'Europe/Moscow');
mb_internal_encoding("UTF-8");

// Get path from request
$_URL = urldecode($_SERVER['REQUEST_URI']);
$_URL = strip_tags($_URL);
$_URL = ltrim($_URL, '/');
$_URL = explode('?', $_URL);
if(isset($_URL[0])){
    $_DIR = explode('/', $_URL[0]);
    $_PAGE = array_pop($_DIR);
    $_PATH = ltrim(implode('/', $_DIR)."/", '/').$_PAGE;
}

// Handle API request
if(isset($_DIR[0]) && $_DIR[0]=="API"){
    if(is_file("API/".$_DIR[1].".php")){
        require "API/".$_DIR[1].".php";
        $api = new $_DIR[1]();
        if(method_exists($api, $_PAGE)){
            $api->{$_PAGE}($_REQUEST);
        }
    }
    exit;
}

// Include layouts from path
if(is_file("layouts/$_PATH.php")){
    require "layouts/$_PATH.php";
}else{
    require "layouts/home.php";
}