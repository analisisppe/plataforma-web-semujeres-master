<?php
declare(strict_types=1);

namespace App\Application\Controladores;

use App\Application\Modelos\Informe;
use App\Application\Modelos\Entregable;
use App\Application\Modelos\Programa;
use App\Application\Modelos\Avance;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;


class calendarController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarCalendario(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol= $usuario[0]->rol; 
    $año = date("Y"); 
        


    
        return $this->container->get('view')->render($response, 'calendario.html', ['nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'año'=>$año]);
    
        }
    }

    public function asignarFechaInforme(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol= $usuario[0]->rol; 
        $año = date("Y"); 
       
        if(!empty($input['trimestre']) && !empty($input['year']) && !empty($input['fecha'])){
            try{
        $trimestre = $input['trimestre'];
        $year =$input['year'];
        $fecha = $input['fecha'];
        $informe = Informe::where('trimestre','=',$trimestre)->get();
        $entregable = Entregable::all();
        $programa = Programa::where('año','=',$year)->get();

      
        if(count($informe) != 0){
                foreach($informe as $i){
                    foreach($entregable as $e){
                    if($e['id_entregable'] == $i['fk_id_entregable']){
                        foreach($programa as $p){
                        if($e['fk_id_programa'] == $p['id_programa']){
                           $in = Informe::find($i['id_informe']);
                          $in->informe_finalizado = $fecha;
                           $in->save();
                           $in++;
                          
                        }
                    }
                }
            }
        }
        $message = 'Fecha Limite Actualizada';
        $class = 'blue';
        return $this->container->get('view')->render($response, 'calendario.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'año'=>$año]);
    }else{
        $message = 'No se encontraron informes correspondientes al trimestre seleccionado';
        $class = 'red';
        return $this->container->get('view')->render($response, 'calendario.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'año'=>$año]);
    }
}catch(\PDOException $e){
    $this->logger->error($e->getMessage());
}
}else{
   
        
    $msg = 'Fecha limite no actualizada. Asegurate de llenar todo el formulario e intenta guardar nuevamente.';
    $clase = 'red';
    
        return $this->container->get('view')->render($response, 'calendario.html', ['message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'año'=>$año]);
}
}
    }

    public function asignarFechaAvance(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol= $usuario[0]->rol; 
        $año = date("Y"); 

        if(!empty($input['mes']) && !empty($input['year']) && !empty($input['fecha'])){
            try{
       
        $mes = $input['mes'];
        $year =$input['year'];
        $fecha = $input['fecha'];
        $avance = Avance::where('mes','=',$mes)->get();
        $entregable = Entregable::all();
        $programa = Programa::where('año','=',$year)->get();

     
        if(count($avance) != 0){
           
           foreach($programa as $p){
          
                foreach($entregable as $e){
                    if($e['fk_id_programa'] == $p['id_programa']){
                    foreach($avance as $a){
                if($e['id_entregable'] == $a['fk_id_entregable']){
                    $av = Avance::find($a['id_avance']);
                  
                    
                       $av = Avance::find($a['id_avance']);
                      $av->avance_finalizado = $fecha;
                       $av->save();
                       
                       $a++;
                       
                    }
                   
                   
                }
            
            }
        }
    }
    $message = 'Fecha Limite De Captura Actualizada';
    $class = 'blue';
    return $this->container->get('view')->render($response, 'calendario.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'año'=>$año]);
}else{
    $message = 'No se encontraron avances correspondientes al mes seleccionado';
    $class = 'red';
    return $this->container->get('view')->render($response, 'calendario.html', ['message'=>$message,'class'=>$class,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'año'=>$año]);
}

                
}catch(\PDOException $e){
    $this->logger->error($e->getMessage());
}
        }else{

            $msg = 'No se actualizó la fecha limite de captura. Asegurate de llenar todo el formulario e intenta guardar nuevamente.';
            $clase = 'red';

            return $this->container->get('view')->render($response, 'calendario.html', ['message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'rol'=>$rol,'año'=>$año]);
        }
    }
    }
}