<?php

namespace App\Login;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class LoginController {

    private $twig;

    public function __construct(Twig $twig){
        $this->twig = $twig;
    }
   
    public function login ($response) {
    return $this->twig->render($response, 'registro_usuario.twig');
    
    }
    

   /* public $view;

    public function __construct(\Slim\Views\Twig $view){
        return $this->view = $view;
    }
    
    public function login ($request, $response, $args){

        $this->view->render($response, 'login_usuario.html', $args);
        return $response->withStatus(200);
    }**/
     
    
    }



?>