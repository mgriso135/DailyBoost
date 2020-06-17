<?php

class App
{
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];
    public function __construct()
    {
        // Gets user language
        session_start();
        $_SESSION['language'] = "en";
        $_SESSION['language'] = isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ? substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2) : 'en';

        $acceptLang = ['it', 'en', 'es']; 
        $_SESSION['language'] = in_array($_SESSION['language'], $acceptLang) ? $_SESSION['language'] : 'en';
    
        $url = $this->parseUrl();
        if(file_exists('../app/controllers/' . $url[0] . '.php'))
        {
            $this->controller = $url[0];
            unset($url[0]);
        }       
        require_once '../app/controllers/'. $this->controller. '.php';
        //echo $this->controller;
        $this->controller = new $this->controller;
        if(isset($url[1]))
        {
            if(method_exists($this->controller, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
        } 
        //echo $this->method;
        
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    public function parseUrl()
    {
       if(isset($_GET['url']))
       {
           return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
       }
    }
    
}