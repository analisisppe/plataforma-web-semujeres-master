<?php
declare(strict_types=1);

namespace App\Application\Controladores;

use App\Application\Modelos\Entregable;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Application\Modelos\Usuario;
use App\Application\Modelos\Programa;
use App\Application\Modelos\PED\Eje;
use App\Application\Modelos\PED\Politica;
use App\Application\Modelos\PED\EstrategiaPED;
use App\Application\Modelos\PED\LineaAccionPED;
use App\Application\Modelos\PED\ObjetivoPED;
use App\Application\Modelos\PED\Alineacionped;

class programasController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function inicio(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
         //$programa= array('prueba','para','ver','si','funciona');
         $usuario = $_SESSION['user'];
         $año = date("Y"); 
         if ($usuario[0]->rol == 'Administrador'){
         $user = Usuario::all(['usuario_id','dependencia']);
         $programas = Programa::All();
         $pro= array();
         foreach($programas as $p){
             foreach($user as $u){
                    if( $p['fk_user']==$u['usuario_id']){
                        $pro[]= ['id_programa'=>$p['id_programa'],'nombre_programa'=>$p['nombre_programa'],'dependencia'=>$u['dependencia']];
                      
                    }
             }
           
         }
         $json = json_decode(json_encode($pro), FALSE);
        
         $nombre_usuario = $usuario[0]->nombre_usuario;
         $dependencia_usuario = $usuario[0]->dependencia;
         $rol = $usuario[0]->rol; 
     // $response->getBody()->write(json_encode($programa));
        return $this->container->get('view')->render($response, 'home.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'programa'=>$json,'año'=>$año]);
        //return $response;
         }else if($usuario[0]->rol == 'Admin SEMUJERES-GEPEA'){
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $dependencia_usuario = $usuario[0]->dependencia;
            $rol = $usuario[0]->rol; 

            $user = Usuario::all(['usuario_id','dependencia']);
                $programa = Programa::where('rol_usuario','=','Enlace SEMUJERES')->orWhere('rol_usuario','=','Enlace GEPEA')->orWhere('rol_usuario','=','Admin SEMUJERES-GEPEA')->get();

                $pro= array();
         foreach($programa as $p){
             foreach($user as $u){
                    if( $p['fk_user']==$u['usuario_id']){
                        $pro[]= ['id_programa'=>$p['id_programa'],'nombre_programa'=>$p['nombre_programa'],'dependencia'=>$u['dependencia']];
                      
                    }
             }
           
         }
         $json = json_decode(json_encode($pro), FALSE);

                return $this->container->get('view')->render($response, 'home.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'programa'=>$json,'año'=>$año]);
         }else{
             $programa = Programa::Where('fk_user',$usuario[0]->usuario_id)->get();
             $nombre_usuario = $usuario[0]->nombre_usuario;
             $dependencia_usuario = $usuario[0]->dependencia;
             $rol = $usuario[0]->rol; 
             $user = Usuario::all(['usuario_id','dependencia']);
             $pro= array();
             foreach($programa as $p){
                 foreach($user as $u){
                        if( $p['fk_user']==$u['usuario_id']){
                            $pro[]= ['id_programa'=>$p['id_programa'],'nombre_programa'=>$p['nombre_programa'],'dependencia'=>$u['dependencia']];
                          
                        }
                 }
               
             }
             $json = json_decode(json_encode($pro), FALSE);
             // $response->getBody()->write(json_encode($programa));
                return $this->container->get('view')->render($response, 'home.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'programa'=>$json]);
                //return $response;
         }
        }
    }

    public function buscarPrograma(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        
        $año=$input['año'];
        $nombre_programa= $input['buscar-programa'];
        
        $usuario = $_SESSION['user'];
        if ($usuario[0]->rol == 'Administrador'){
         
        $programa =$año != "" && $nombre_programa != "" ? Programa::where('año','=',$año)->where('nombre_programa','=',$nombre_programa)->get():(
                   $año != "" && $nombre_programa == "" ? Programa::where('año','=', $año)->get() :(
                    $año == "" && $nombre_programa != "" ? Programa::where('nombre_programa','=',$nombre_programa)->get():Programa::All()));
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol; 
    
       return $this->container->get('view')->render($response, 'home.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'programa'=>$programa]);
      
        }else{
            $programa = Programa::Where('fk_user',$usuario[0]->usuario_id)->get();
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $dependencia_usuario = $usuario[0]->dependencia;
            $rol = $usuario[0]->rol; 
            
               return $this->container->get('view')->render($response, 'home.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'programa'=>$programa]);
             
        }
    }
    }
    public function mostrarAgregarPrograma(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol; 
        $eje = Eje::All();
        $politica = Politica::all();
        $objetivoped = ObjetivoPED::all();
        $estrategiaped = EstrategiaPED::all();
        $lineaccion = LineaAccionPED::all();
        $año = date("Y"); 
        return $this->container->get('view')->render($response, 'add_program.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'eje'=>$eje, 'politica'=>$politica, 'objetivoped'=>$objetivoped, 'estrategiaped'=>$estrategiaped,
        'lineaccion'=>$lineaccion,'año'=>$año]);
        }
    }

   

    public function agregarPrograma(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
     
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        $usuario = $_SESSION['user'];
       
       if(!empty($input['nombre_programa']) && !empty( $input['año']) && !empty( $input['objetivo']) && !empty($input['descripcion']) && !empty($input['nombre_responsable']) && !empty( $input['cargo_responsable']) 
       && !empty($input['correo_responsable']) && !empty($input['tel_responsable'])){
           try{
        $programa = new Programa();

        $programa->nombre_programa= $input['nombre_programa'];
        $programa->año = $input['año'];
        $programa->objetivo = $input['objetivo'];
        $programa->descripcion = $input['descripcion'];
        $programa->nombre_responsable =$input['nombre_responsable'];
        $programa->cargo_responsable = $input['cargo_responsable'];
        $programa->correo_responsable= $input['correo_responsable'];
        $programa->tel_responsable = $input['tel_responsable'];
        $programa->brecha_genero= (isset($input['brecha_genero']) ? $input['brecha_genero'] : 0);
        $programa->fk_user=$usuario[0]->usuario_id;
        $programa->rol_usuario=$usuario[0]->rol;

        $programa->save();

        //GUARDANDO INPUTS PED
        if(!empty($input['textareaideje'])){
            for($p=0; $p<count($input['textareaideje']);$p++){
                for($o=0; $o<count($input['textareapolitica']);$o++){
                    for($e=0; $e<count($input['textareaobjetivo']);$e++){
                        for($l=0; $l<count($input['textareaestrategia']);$l++){
                            for($s=0; $s<count($input['textarealinea']);$s++){
                            if($p == $o && $p == $e && $p==$l && $p==$s){
                                $ped = new Alineacionped();
                                $ped->eje = $input['textareaideje'][$p];
                                $ped->politica = $input['textareapolitica'][$o];
                                $ped->objetivo = $input['textareaobjetivo'][$e];
                                $ped->estrategia = $input['textareaestrategia'][$l];
                                $ped->linea = $input['textarealinea'][$s];
                                $programa->alineacionped()->save($ped);
    
                            }
                        }
                    }
                }
            }
        }
    }
   

       return $response->withHeader('Location',"inicio")->withStatus(302);
}catch(\PDOException $e){
    $this->logger->error($e->getMessage());
}
}else{
    $msg = 'Tu programa no ha sido guardado porque existen uno o más campos vacios. Llene todos los campos y haga clic en guardar nuevamente.';
    $class = 'red';

    $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol; 
        $eje = Eje::All();
        $politica = Politica::all();
        $objetivoped = ObjetivoPED::all();
        $estrategiaped = EstrategiaPED::all();
        $lineaccion = LineaAccionPED::all();
       
        
        $programa= $input['nombre_programa'];
        $año = $input['año'];
        $objetivo = $input['objetivo'];
        $descripcion = $input['descripcion'];
        $nombre_responsable =$input['nombre_responsable'];
        $cargo_responsable = $input['cargo_responsable'];
        $correo= $input['correo_responsable'];
        $tel_= $input['tel_responsable'];
        return $this->container->get('view')->render($response, 'add_program.html',['programa'=>$programa,'objetivo'=>$objetivo,'descripcion'=>$descripcion,'nombrer'=>$nombre_responsable,'cargo'=>$cargo_responsable,'correo'=>$correo,'tel'=>$tel_,'message'=>$msg,'class'=>$class,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'eje'=>$eje, 'politica'=>$politica, 'objetivoped'=>$objetivoped, 'estrategiaped'=>$estrategiaped,
        'lineaccion'=>$lineaccion,'año'=>$año]);
}
}
    }

    public function mostrarEditarPrograma(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol =$usuario[0]->rol;
            $id = $args['id'];
            $programa = Programa::all();
            $eje = Eje::All();
        $politica = Politica::all();
        $objetivoped = ObjetivoPED::all();
        $estrategiaped = EstrategiaPED::all();
        $lineaccion = LineaAccionPED::all();
        $alineacion_ped = Alineacionped::all();

            return $this->container->get('view')->render($response, 'edit_program.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'id_programa'=>$id, 'programa'=>$programa,'eje'=>$eje, 'politica'=>$politica, 'objetivoped'=>$objetivoped, 'estrategiaped'=>$estrategiaped,
            'lineaccion'=>$lineaccion,'alineacionped'=>$alineacion_ped]);
        }
    }

    public function editarPrograma(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        
        $id= $args['id'];
        if(!empty($input['nombre_programa']) && !empty( $input['año']) && !empty( $input['objetivo']) && !empty($input['descripcion']) && !empty($input['nombre_responsable']) && !empty( $input['cargo_responsable']) 
        && !empty($input['correo_responsable']) && !empty($input['tel_responsable'])){
            try{
       $programa = Programa::find($id);
       
       $programa->nombre_programa = $input['nombre_programa'];
       $programa->año = $input['año'];
       $programa->objetivo = $input['objetivo'];
       $programa->descripcion = $input['descripcion'];
       $programa->nombre_responsable = $input['nombre_responsable'];
       $programa->cargo_responsable = $input['cargo_responsable'];
       $programa->correo_responsable= $input['correo_responsable'];
       $programa->tel_responsable = $input['tel_responsable'];
       $programa->brecha_genero = (isset($input['brecha_genero']) ? $input['brecha_genero'] : 0);
        
       $programa->save();

       //GUARDANDO INPUTS PED
       if(!empty($input['textareaideje'])){
        $programa->alineacionped()->delete();
        for($p=0; $p<count($input['textareaideje']);$p++){
            for($o=0; $o<count($input['textareapolitica']);$o++){
                for($e=0; $e<count($input['textareaobjetivo']);$e++){
                    for($l=0; $l<count($input['textareaestrategia']);$l++){
                        for($s=0; $s<count($input['textarealinea']);$s++){
                        if($p == $o && $p == $e && $p==$l && $p==$s){
                            $ped = new Alineacionped();
                            $ped->eje = $input['textareaideje'][$p];
                            $ped->politica = $input['textareapolitica'][$o];
                            $ped->objetivo = $input['textareaobjetivo'][$e];
                            $ped->estrategia = $input['textareaestrategia'][$l];
                            $ped->linea = $input['textarealinea'][$s];
                          
                            $programa->alineacionped()->save($ped);

                        }
                    }
                }
            }
        }
    }
}

       $host  = $_SERVER['HTTP_HOST'];
       $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
       return $response->withHeader('Location',"http://$host$uri/inicio")->withStatus(302);
}catch(\PDOException $e){
    $this->logger->error($e->getMessage());
}
}
else{
    $msg = 'Tu programa no ha sido guardado porque existen uno o más campos vacios. Llene todos los campos y haga clic en guardar nuevamente.';
    $class = 'red';

    $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol =$usuario[0]->rol;
        $programa = Programa::all();
        $eje = Eje::All();
    $politica = Politica::all();
    $objetivoped = ObjetivoPED::all();
    $estrategiaped = EstrategiaPED::all();
    $lineaccion = LineaAccionPED::all();
    $alineacion_ped = Alineacionped::all();

        return $this->container->get('view')->render($response, 'edit_program.html',['message'=>$msg,'class'=>$class,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'id_programa'=>$id, 'programa'=>$programa,'eje'=>$eje, 'politica'=>$politica, 'objetivoped'=>$objetivoped, 'estrategiaped'=>$estrategiaped,
        'lineaccion'=>$lineaccion,'alineacionped'=>$alineacion_ped]);
}
}
    }

    public function eliminarPrograma(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $id = $args['id'];
       
        $programa = Programa::find($id);
        if($programa != null){
        $programa->delete();
     
        }
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        return $response->withHeader('Location',"http://$host$uri/inicio")->withStatus(302);
    }
    }

   
}
