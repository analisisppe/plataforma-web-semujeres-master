<?php
declare(strict_types=1);

namespace App\Application\Controladores;

use App\Application\Modelos\Catalogos;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;


class baseCatalogosController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarCatalogos(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol= $usuario[0]->rol; 
        
    if(Catalogos::All() != []){
        $catalogos = Catalogos::All();
    }

    
        return $this->container->get('view')->render($response, 'baseCatalogos.html', ['nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'catalogos'=>$catalogos]);
    
}
    }

    public function subirCatalogo(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol= $usuario[0]->rol; 

      

        if(!empty($input['nombre_catalogo']) && !empty($_FILES['catalogo']['name'])){
            if($_FILES['catalogo']['type'] == "application/pdf"){
       
     
            $directory = $this->container->get('files');
            $uploadedFiles = $request->getUploadedFiles();
            $uploadedFile = $uploadedFiles['catalogo'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
           
               $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $_FILES['catalogo']['name']);
            }

            
        $catalogo = new Catalogos();

        $catalogo->nombre_catalogo = $input['nombre_catalogo'];
        $catalogo->catalogo = $_FILES['catalogo']['name'];

        $catalogo->save();

        $message = '¡Archivo Guardado Exitosamente!';
        $class = 'blue';
        return $this->container->get('view')->render($response, 'baseCatalogos.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol]);

        
        }else{
            $message = 'El archivo no es formato PDF';
            $class = 'red';
            return $this->container->get('view')->render($response, 'baseCatalogos.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol]);

        }}
        $message = 'No se pudo guardar porque hay campos vacios';
        $class = 'red';
        return $this->container->get('view')->render($response, 'baseCatalogos.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol]);
    }
    }
 


    public function descargarCatalogo(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol= $usuario[0]->rol; 



        if($input['ruta_catalogo']){
            $ruta = $input['ruta_catalogo'];
            $directory = $this->container->get('files');

            $catalogo = Catalogos::where('catalogo','=',$ruta)->get('catalogo');

            $filepath = $directory.DIRECTORY_SEPARATOR.$catalogo[0]->catalogo;

            if (file_exists($filepath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
               $response->getBody()->write(readfile($filepath));
                return $response;
            }
            $message = 'El archivo no existe.';
            $class = 'red';
            return $this->container->get('view')->render($response, 'baseCatalogos.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol]);

          

    }
    $message = 'Seleccione una opción';
    $class = 'red';
    return $this->container->get('view')->render($response, 'baseCatalogos.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol]);
    }

    }
      
}