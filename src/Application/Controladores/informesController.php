<?php
declare(strict_types=1);

namespace App\Application\Controladores;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use App\Application\Modelos\Entregable;
use App\Application\Modelos\Informe;
use Twig\TokenParser\EmbedTokenParser;

class informesController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarInformes(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $id = $args['id'];
        $rol = $usuario[0]->rol;
      $entregable = Entregable::all();
        $informe = Informe::all();
        $today = date("Y-m-d H:i:s");  

        return $this->container->get('view')->render($response, 'informe.html',['nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id, 'today'=>$today,'rol'=>$rol]);
        }
    }

    public function guardarInforme(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
    $input = $request->getParsedBody();
    

       $id = $args['id'];

        $prueba = Informe::where('trimestre','=',$input['trimestre'])->where('fk_id_entregable','=',$id)->get();

        if(empty($prueba) != false){
        $fechaActual = date("Y-m-d H:i:s"); 
        $fechaFin = $prueba[0]->informe_finalizado;
        if( $fechaActual == $fechaFin){
            $usuario = $_SESSION['user'];
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $dependencia_usuario = $usuario[0]->dependencia;
            $rol = $usuario[0]->rol;
            $entregable = Entregable::all();
            $informe = Informe::all();

            $message = 'FECHA LIMITE DE CAPTURA EXCEDIDA';
            $class = 'red';
            return $this->container->get('view')->render($response, 'informe.html',['class'=>$class,'message'=>$message,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id,'rol'=>$rol]);
        }else{
             
            if($fechaFin == Null){
                if(!empty($input['trimestre']) && !empty($input['periodo']) && !empty($input['accion']) && !empty($input['personas']) && !empty($input['municipios']) &&
         !empty($input['objetivo']) && !empty($input['descripcion'])){
             try{
                $in = Informe::find($prueba[0]->id_informe);
               
                $in->trimestre = $input['trimestre'];
                $in->periodo = $input['periodo'];
                $in->accion = $input['accion'];
                $in->personas = $input['personas'];
                $in->municipios = $input['municipios'];
                $in->objetivo = $input['objetivo'];
                $in->descripcion=$input['descripcion'] ;
                $in->informe_finalizado = $fechaFin;
                $in->fk_id_entregable=$id;
                
                $in->save();
    
                $host  = $_SERVER['HTTP_HOST'];
                 $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                 return $response->withHeader('Location',"http://$host$uri/informe/$id")->withStatus(302);
             }catch(\PDOException $e){
                $this->logger->error($e->getMessage());
            }
         }else{
            $msg = 'Tu informe no ha sido guardado porque hay campos vacíos. Llena todos los campos y haz clic en guardar nuevamente.';
            $clase = 'red';
            $usuario = $_SESSION['user'];
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $dependencia_usuario = $usuario[0]->dependencia;
            $id = $args['id'];
            $rol = $usuario[0]->rol;
          $entregable = Entregable::all();
            $informe = Informe::all();
            $today = date("Y-m-d H:i:s");  

                $trimestre = $input['trimestre'];
                $periodo = $input['periodo'];
                $accion = $input['accion'];
                $personas = $input['personas'];
                $municipios = $input['municipios'];
                $objetivo = $input['objetivo'];
                $descripcion=$input['descripcion'] ;
    
            return $this->container->get('view')->render($response, 'informe.html',['trimestre'=>$trimestre,'periodo'=>$periodo,'accion'=>$accion,
            'personas'=>$personas,'municipio'=>$municipios,'objetivo'=>$objetivo,'descripcion'=>$descripcion,'message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id, 'today'=>$today,'rol'=>$rol]);
         }
            }else{
                if($fechaActual < $fechaFin){
                    if(!empty($input['trimestre']) && !empty($input['periodo']) && !empty($input['accion']) && !empty($input['personas']) && !empty($input['municipios']) &&
                    !empty($input['objetivo']) && !empty($input['descripcion'])){
                        try{
                    $in = Informe::find($prueba[0]->id_informe);
               
                    $in->trimestre = $input['trimestre'];
                    $in->periodo = $input['periodo'];
                    $in->accion = $input['accion'];
                    $in->personas = $input['personas'];
                    $in->municipios = $input['municipios'];
                    $in->objetivo = $input['objetivo'];
                    $in->descripcion=$input['descripcion'] ;
                    $in->informe_finalizado = $fechaFin;
                    $in->fk_id_entregable=$id;
                    
                    $in->save();
        
                    $host  = $_SERVER['HTTP_HOST'];
                     $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                     return $response->withHeader('Location',"http://$host$uri/informe/$id")->withStatus(302);
                        }catch(\PDOException $e){
                            $this->logger->error($e->getMessage());
                        }
                    }else{
                        $msg = 'Tu informe no ha sido guardado porque hay campos vacíos. Llena todos los campos y haz clic en guardar nuevamente.';
                        $clase = 'red';
                        $usuario = $_SESSION['user'];
                        $nombre_usuario = $usuario[0]->nombre_usuario;
                        $dependencia_usuario = $usuario[0]->dependencia;
                        $id = $args['id'];
                        $rol = $usuario[0]->rol;
                      $entregable = Entregable::all();
                        $informe = Informe::all();
                        $today = date("Y-m-d H:i:s");  
            
                            $trimestre = $input['trimestre'];
                            $periodo = $input['periodo'];
                            $accion = $input['accion'];
                            $personas = $input['personas'];
                            $municipios = $input['municipios'];
                            $objetivo = $input['objetivo'];
                            $descripcion=$input['descripcion'] ;
                
                        return $this->container->get('view')->render($response, 'informe.html',['trimestre'=>$trimestre,'periodo'=>$periodo,'accion'=>$accion,
                        'personas'=>$personas,'municipio'=>$municipios,'objetivo'=>$objetivo,'descripcion'=>$descripcion,'message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id, 'today'=>$today,'rol'=>$rol]);
                    }
                }
            }
        }
      
    }


            $otro = Informe::where('trimestre','=',$input['trimestre'])->first();
           
            if(empty($otro) == false){
            $fechaActual = date("Y-m-d H:i:s"); 
            $fechaFin = $otro->informe_finalizado;
            if( $fechaActual == $fechaFin ){
                $usuario = $_SESSION['user'];
                $nombre_usuario = $usuario[0]->nombre_usuario;
                $dependencia_usuario = $usuario[0]->dependencia;
                $rol = $usuario[0]->rol;
                $entregable = Entregable::all();
                $informe = Informe::all();
    
                $message = 'FECHA LIMITE DE CAPTURA EXCEDIDA';
                $class = 'red';
                return $this->container->get('view')->render($response, 'informe.html',['class'=>$class,'message'=>$message,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id,'rol'=>$rol]);
            }else{
             
            if($fechaFin == Null){
                if(!empty($input['trimestre']) && !empty($input['periodo']) && !empty($input['accion']) && !empty($input['personas']) && !empty($input['municipios']) &&
                    !empty($input['objetivo']) && !empty($input['descripcion'])){
            try{
                $in = Informe::find($otro->id_informe);
               
                $in->trimestre = $input['trimestre'];
                $in->periodo = $input['periodo'];
                $in->accion = $input['accion'];
                $in->personas = $input['personas'];
                $in->municipios = $input['municipios'];
                $in->objetivo = $input['objetivo'];
                $in->descripcion=$input['descripcion'] ;
                $in->informe_finalizado = $fechaFin;
                $in->fk_id_entregable=$id;
                
                $in->save();
    
                $host  = $_SERVER['HTTP_HOST'];
                 $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                 return $response->withHeader('Location',"http://$host$uri/informe/$id")->withStatus(302);
            }catch(\PDOException $e){
                $this->logger->error($e->getMessage());
            }
        }else{
            $msg = 'Tu informe no ha sido guardado porque hay campos vacíos. Llena todos los campos y haz clic en guardar nuevamente.';
            $clase = 'red';
            $usuario = $_SESSION['user'];
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $dependencia_usuario = $usuario[0]->dependencia;
            $id = $args['id'];
            $rol = $usuario[0]->rol;
          $entregable = Entregable::all();
            $informe = Informe::all();
            $today = date("Y-m-d H:i:s");  

                $trimestre = $input['trimestre'];
                $periodo = $input['periodo'];
                $accion = $input['accion'];
                $personas = $input['personas'];
                $municipios = $input['municipios'];
                $objetivo = $input['objetivo'];
                $descripcion=$input['descripcion'] ;
    
            return $this->container->get('view')->render($response, 'informe.html',['trimestre'=>$trimestre,'periodo'=>$periodo,'accion'=>$accion,
            'personas'=>$personas,'municipio'=>$municipios,'objetivo'=>$objetivo,'descripcion'=>$descripcion,'message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id, 'today'=>$today,'rol'=>$rol]);
        }
            }else{
                if($fechaActual < $fechaFin){
                    if(!empty($input['trimestre']) && !empty($input['periodo']) && !empty($input['accion']) && !empty($input['personas']) && !empty($input['municipios']) &&
                    !empty($input['objetivo']) && !empty($input['descripcion'])){
                    try{
                    $in = Informe::find($otro->id_informe);
               
                    $in->trimestre = $input['trimestre'];
                    $in->periodo = $input['periodo'];
                    $in->accion = $input['accion'];
                    $in->personas = $input['personas'];
                    $in->municipios = $input['municipios'];
                    $in->objetivo = $input['objetivo'];
                    $in->descripcion=$input['descripcion'] ;
                    $in->informe_finalizado = $fechaFin;
                    $in->fk_id_entregable=$id;
                    
                    $in->save();
        
                    $host  = $_SERVER['HTTP_HOST'];
                     $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                     return $response->withHeader('Location',"http://$host$uri/informe/$id")->withStatus(302);
                    }catch(\PDOException $e){
                        $this->logger->error($e->getMessage());
                    }
                }else{
                    $msg = 'Tu informe no ha sido guardado porque hay campos vacíos. Llena todos los campos y haz clic en guardar nuevamente.';
                    $clase = 'red';
                    $usuario = $_SESSION['user'];
                    $nombre_usuario = $usuario[0]->nombre_usuario;
                    $dependencia_usuario = $usuario[0]->dependencia;
                    $id = $args['id'];
                    $rol = $usuario[0]->rol;
                  $entregable = Entregable::all();
                    $informe = Informe::all();
                    $today = date("Y-m-d H:i:s");  
        
                        $trimestre = $input['trimestre'];
                        $periodo = $input['periodo'];
                        $accion = $input['accion'];
                        $personas = $input['personas'];
                        $municipios = $input['municipios'];
                        $objetivo = $input['objetivo'];
                        $descripcion=$input['descripcion'] ;
            
                    return $this->container->get('view')->render($response, 'informe.html',['trimestre'=>$trimestre,'periodo'=>$periodo,'accion'=>$accion,
                    'personas'=>$personas,'municipio'=>$municipios,'objetivo'=>$objetivo,'descripcion'=>$descripcion,'message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id, 'today'=>$today,'rol'=>$rol]);
                }
                }
            }
        }
                 

    }else{
        if(!empty($input['trimestre']) && !empty($input['periodo']) && !empty($input['accion']) && !empty($input['personas']) && !empty($input['municipios']) &&
        !empty($input['objetivo']) && !empty($input['descripcion'])){
        try{
    
        $informe = new Informe();
        $informe->trimestre = $input['trimestre'];
        $informe->periodo = $input['periodo'];
        $informe->accion = $input['accion'];
        $informe->personas = $input['personas'];
        $informe->municipios = $input['municipios'];
        $informe->objetivo = $input['objetivo'];
        $informe->descripcion=$input['descripcion'] ;
        $informe->informe_finalizado = NULL;
        $informe->fk_id_entregable=$id;
        

        $informe->save();

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
       return $response->withHeader('Location',"http://$host$uri/informe/$id")->withStatus(302);
        }catch(\PDOException $e){
            $this->logger->error($e->getMessage());
        }
    }else{
        $msg = 'Tu informe no ha sido guardado porque hay campos vacíos. Llena todos los campos y haz clic en guardar nuevamente.';
        $clase = 'red';
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $id = $args['id'];
        $rol = $usuario[0]->rol;
      $entregable = Entregable::all();
        $informe = Informe::all();
        $today = date("Y-m-d H:i:s");  

            $trimestre = $input['trimestre'];
            $periodo = $input['periodo'];
            $accion = $input['accion'];
            $personas = $input['personas'];
            $municipios = $input['municipios'];
            $objetivo = $input['objetivo'];
            $descripcion=$input['descripcion'] ;

        return $this->container->get('view')->render($response, 'informe.html',['trimestre'=>$trimestre,'periodo'=>$periodo,'accion'=>$accion,
        'personas'=>$personas,'municipio'=>$municipios,'objetivo'=>$objetivo,'descripcion'=>$descripcion,'message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'informe'=>$informe, 'entregable'=>$entregable,'id'=>$id, 'today'=>$today,'rol'=>$rol]);  
    }
    }

}
       
    }
}