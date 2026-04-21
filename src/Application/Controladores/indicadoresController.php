<?php
declare(strict_types=1);

namespace App\Application\Controladores;

use App\Application\Modelos\Ficha;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

use App\Application\Modelos\Indicadores;
use App\Application\Modelos\INDICADORES\BaseMeta;
use App\Application\Modelos\INDICADORES\Indicador;
use App\Application\Modelos\INDICADORES\Variables;
use App\Application\Modelos\INDICADORES\Pp;

class indicadoresController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarIndicadores(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $año = date("Y"); 
        if ($rol == 'Administrador'){
        $indicador = Indicador::all();
        $indicadores = Indicadores::all();
       $variable = Variables::all();
        return $this->container->get('view')->render($response, 'home_indicadores.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'indicador'=>$indicador, 'indicadores'=>$indicadores,'año'=>$año,'variable'=>$variable]);
        }else if($rol != 'Administrador' ){
            $indicador = Indicador::all();
            $variable = Variables::all();
        $indicadores = Indicadores::Where('fk_user',$usuario[0]->usuario_id)->get();
        return $this->container->get('view')->render($response, 'home_indicadores.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'indicador'=>$indicador, 'indicadores'=>$indicadores,'año'=>$año,'variable'=>$variable]);
        }
    }
    }

    public function verNuevoIndicador(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $año = date("Y"); 
       
        return $this->container->get('view')->render($response, 'new_indicador.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'año'=>$año]);
        }
    }

    public function agregarNuevoIndicador(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){

            if(!empty($input['numeroindicador']) && !empty($input['definicion']) && !empty($input['formula']) && !empty($input['pp'])){
                try{
        $input = $request->getParsedBody();


        $indicador = new Indicador();

        $indicador->numero = $input['numeroindicador'];
        $indicador->definicion = $input['definicion'];
        $indicador->formula = $input['formula'];
        $indicador->fk_pp = $input['pp'];

        $indicador->save();

        $bM = new BaseMeta();

        $bM->año=$input['año'];
        $bM->linea_base= $input['linea-base'];
        $bM->meta = $input['linea-meta'];
        $indicador->baseMeta()->save($bM);

        if($input['nombre-variable-a'] != ''){
        $variable = new Variables();
        $variable->variable = 'A';
        $variable->nombre = $input['nombre-variable-a'];
        $indicador->variables()->save($variable);
        }

        if($input['nombre-variable-b'] != ''){
            $variable = new Variables();
            $variable->variable = 'B';
            $variable->nombre = $input['nombre-variable-b'];
            $indicador->variables()->save($variable);
            }

            if($input['nombre-variable-c'] != ''){
                $variable = new Variables();
                $variable->variable = 'C';
                $variable->nombre = $input['nombre-variable-c'];
                $indicador->variables()->save($variable);
                }

                if($input['nombre-variable-d'] != ''){
                    $variable = new Variables();
                    $variable->variable = 'D';
                    $variable->nombre = $input['nombre-variable-d'];
                    $indicador->variables()->save($variable);
                    }

                    if(!empty($_FILES['ficha-indicador']['name'])){

                        if($_FILES['ficha-indicador']['type'] == "application/pdf"){
       
     
                            $directory = $this->container->get('fichas');
                            $uploadedFiles = $request->getUploadedFiles();
                            $uploadedFile = $uploadedFiles['ficha-indicador'];
                            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                           
                               $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $_FILES['ficha-indicador']['name']);
                            }
                
                        $ficha = new Ficha();

                        $ficha->numero_indicador = $input['numeroindicador'];
                        $ficha->año = $input['año'];
                        $ficha->ficha = $_FILES['ficha-indicador']['name'];
                        $ficha->save();
                        }

                    }

                    return $response->withHeader('Location',"nuevoIndicador")->withStatus(302);
                }catch(\PDOException $e){
                    $this->logger->error($e->getMessage());
                }
                }else{
                    $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $año = date("Y"); 

        $msg = 'No se guardó el indicador.Asegurate de llenar todo el formulario y haz clic en guardar nuevamente.';
        $clase = 'red';
       
        return $this->container->get('view')->render($response, 'new_indicador.html',['message'=>$msg,'class'=>$clase,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'año'=>$año]);
                }
                }  
    }
    public function verAgregarIndicador(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $año = date("Y"); 
        $pp = Pp::all();
        $indicador = Indicador::all();
        $variables = Variables::all();
        $indicadores = Indicadores::all();
        $base_meta = BaseMeta::all();
        return $this->container->get('view')->render($response, 'add_indicador.html',['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'pp'=>$pp, 'indicador'=>$indicador,'variables'=>$variables,'indicadores'=>$indicadores
                ,'baseMeta'=>$base_meta,'año'=>$año]);
        }
            }


    public function agregarIndicador(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){

        $input = $request->getParsedBody();
        $usuario = $_SESSION['user'];

  if(!empty($input['responsable-indicador']) && !empty($input['corresponsable-indicador']) && !empty($input['numeroindicador']) && !empty($input['mes-indicador']) && !empty($input['valor-b']) &&
        !empty($input['valor-c'])){
            try{
      $indicador = Indicador::where('id_indicador','=',$input['numeroindicador'])->get(['id_indicador','numero','formula']);
    
       $indicadores = new Indicadores();

       $indicadores->responsable = $input['responsable-indicador']; 
       $indicadores->corresponsable = $input['corresponsable-indicador'];
       $indicadores->indicador = $input['numeroindicador'];
       $indicadores->año =  $input['año-indicador'];

       if($input['mes-indicador'] == 'ENERO' ){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0 ){
            if($indicador[0]->formula == "(B/C)*100"  ){
                $indicadores->en_a=  ($input['valor-b']/$input['valor-c'])*100;       
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->en_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->en_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                    if($indicador[0]->numero == 22035){
                        $indicadores->en_a = ($input['valor-b']/$input['valor-c'])*100000;
                    }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                        $indicadores->en_a = ($input['valor-b']/$input['valor-c'])*100;
                    }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                        $indicadores->en_a = ($input['valor-b']/$input['valor-c'])*1000;
                    }
            }
        }
    }else{
        $indicadores->en_a= 0;
    }
        $indicadores->en_b= $input['valor-b'];
        $indicadores->en_c =$input['valor-c'];
    }else if($input['mes-indicador'] == 'FEBRERO'){
        if($input['valor-c'] != 0 ){
        if($indicador[0]->id_indicador!= 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->feb_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->feb_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->feb_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->feb_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->feb_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->feb_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
    }else{
        $indicadores->feb_a= 0;
    }
        $indicadores->feb_b = $input['valor-b'];
        $indicadores->feb_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'MARZO' && $input['valor-c'] != 0 ){
        if($indicador[0]->id_indicador!= 0 ){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->mar_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->mar_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->mar_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->mar_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->mar_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->mar_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
        else{
            $indicadores->mar_a= 0;
        }
        $indicadores->mar_b = $input['valor-b'];
        $indicadores->mar_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'ABRIL' ){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador!= 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->ab_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->ab_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->ab_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->ab_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->ab_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->ab_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        } 
        }else{
            $indicadores->ab_a= 0;
        }
        $indicadores->ab_b = $input['valor-b'];
        $indicadores->ab_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'MAYO'){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->may_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->may_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->may_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->may_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->may_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->may_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
        }else{
            $indicadores->may_a= 0;
        }
        $indicadores->may_b = $input['valor-b'];
        $indicadores->may_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'JUNIO' ){
    if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->jun_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->jun_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->jun_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->jun_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->jun_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->jun_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
     }else{
            $indicadores->jun_a= 0;
        }
        $indicadores->jun_b = $input['valor-b'];
        $indicadores->jun_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'JULIO' ){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->jul_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->jul_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->jul_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->jul_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->jul_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->jul_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
     }else{
            $indicadores->jul_a= 0;
        }
        $indicadores->jul_b = $input['valor-b'];
        $indicadores->jul_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'AGOSTO' ){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->ago_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->ago_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->ago_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->ago_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->ago_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->ago_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
    }else{
        $indicadores->ago_a= 0;
    }
        $indicadores->ago_b = $input['valor-b'];
        $indicadores->ago_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'SEPTIEMBRE' ){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->sep_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->sep_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->sep_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->sep_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->sep_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->sep_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
    }else{
        $indicadores->sep_a= 0;
    }
        $indicadores->sep_b = $input['valor-b'];
        $indicadores->sep_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'OCTUBRE'){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->oct_a= (($input['valor-b']/$input['valor-c'])*100);      
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->oct_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->oct_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->oct_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->oct_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->oct_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
    }else{
        $indicadores->oct_a= 0;
    }
        $indicadores->oct_b = $input['valor-b'];
        $indicadores->oct_c = $input['valor-c'];
    }else if($input['mes-indicador'] == 'NOVIEMBRE'){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador != 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->nov_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->nov_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->nov_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->nov_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->nov_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->nov_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
        }else{
            $indicadores->nov_a= 0;
        }
        $indicadores->nov_b = $input['valor-b'];
        $indicadores->nov_c = $input['valor-c'];
    
    }else if($input['mes-indicador'] == 'DICIEMBRE' ){
        if($input['valor-c'] != 0){
        if($indicador[0]->id_indicador!= 0){
            if($indicador[0]->formula == "(B/C)*100"){
                $indicadores->dic_a=  ($input['valor-b']/$input['valor-c'])*100;           
            }else if($indicador[0]->formula == "SUM B/C"){
                $indicadores->dic_a=  ($input['valor-b']/$input['valor-c']);
            }else if($indicador[0]->formula == "((B-C)/C)*100"){
                $indicadores->dic_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
            }else if($indicador[0]->formula == "(B/C)*D"){
                if($indicador[0]->numero == 22035){
                    $indicadores->dic_a = ($input['valor-b']/$input['valor-c'])*100000;
                }else if($indicador[0]->numero == 22036 || $indicador[0]->numero == 22037){
                    $indicadores->dic_a = ($input['valor-b']/$input['valor-c'])*100;
                }else if($indicador[0]->numero == 21472 || $indicador[0]->numero == 21476){
                    $indicadores->dic_a = ($input['valor-b']/$input['valor-c'])*1000;
                }
        }
        }
    }else{
        $indicadores->dic_a= 0;
    }
        $indicadores->dic_b = $input['valor-b'];
        $indicadores->dic_c = $input['valor-c'];
    }

      $indicadores->fk_user = $usuario[0]->usuario_id;

       $indicadores->save();
       
        return $response->withHeader('Location',"indicadores")->withStatus(302);
}catch(\PDOException $e){
    $this->logger->error($e->getMessage());
}
}else{
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol = $usuario[0]->rol;
    $año = date("Y"); 
    $pp = Pp::all();
    $indicador = Indicador::all();
    $variables = Variables::all();
    $indicadores = Indicadores::all();
    $base_meta = BaseMeta::all();

    $msg = 'El indicador no ha sido guardado. Asegurate de llenar todo el formulario y haz clic en guardar nuevamente.';
    $clase = 'red';

       $responsable = $input['responsable-indicador']; 
       $corresponsable = $input['corresponsable-indicador'];
    return $this->container->get('view')->render($response, 'add_indicador.html',['responsable'=>$responsable,'corresponsable'=>$corresponsable,'message'=>$msg,'class'=>$clase,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'pp'=>$pp, 'indicador'=>$indicador,'variables'=>$variables,'indicadores'=>$indicadores
            ,'baseMeta'=>$base_meta,'año'=>$año]);

}
}
    }

    public function verEditarIndicador(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
    $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol = $usuario[0]->rol;
    $id_indicadores = $args['id_indicadores'];
    $id_indicador =$args['id_indicador'];
    $indicador = Indicador::all();
    $indicadores = Indicadores::all();
    $variables = Variables::all();  
    $pp = Pp::all();
    $base_meta = BaseMeta::all();
    $año = date("Y"); 
 
        return $this->container->get('view')->render($response, 'edit_indicador.html',[
            'id_indicadores'=>$id_indicadores, 'id_indicador'=>$id_indicador,'pp'=>$pp,'indicador'=>$indicador,'indicadores'=>$indicadores,
        'variables'=>$variables,'rol'=>$rol,
        'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'baseMeta'=>$base_meta,'año'=>$año]);
        }
        }

    public function editarIndicador(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();

        if(!empty($input['responsable-indicador']) && !empty($input['corresponsable-indicador']) && !empty($input['mes-indicador']) && !empty($input['valor-b']) &&
        !empty($input['valor-c'])){
            try{
        
        $id_indicadores = $args['id_indicadores'];
        $id_indicador =$args['id_indicador'];
        
        $indicadores = Indicadores::find($id_indicadores);
        $indicador = Indicador::where('id_indicador','=',$id_indicador)->get(['id_indicador','formula']);

        $indicadores->responsable = $input['responsable-indicador']; 
        $indicadores->corresponsable = $input['corresponsable-indicador'];
        
        if($input['mes-indicador'] == 'ENERO' ){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->en_a=  ($input['valor-b']/$input['valor-c'])*100;       
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->en_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->en_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->en_a=  ($input['valor-b']/$input['valor-c'])*1000;       
                }
            }
        }else{
            $indicadores->en_a= 0;
        }
            $indicadores->en_b= $input['valor-b'];
            $indicadores->en_c =$input['valor-c'];
        }else if($input['mes-indicador'] == 'FEBRERO'){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador!= 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->feb_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->feb_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->feb_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->feb_a=  ($input['valor-b']/$input['valor-c'])*1000;    
                }
            }
        }else{
            $indicadores->feb_a= 0;
        }
            $indicadores->feb_b = $input['valor-b'];
            $indicadores->feb_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'MARZO' && $input['valor-c'] != 0 ){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->mar_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->mar_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->mar_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->mar_a=  ($input['valor-b']/$input['valor-c'])*1000;     
                }
            }
            else{
                $indicadores->mar_a= 0;
            }
            $indicadores->mar_b = $input['valor-b'];
            $indicadores->mar_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'ABRIL' ){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador!= 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->ab_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->ab_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->ab_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->ab_a=  ($input['valor-b']/$input['valor-c'])*1000; 
                }
            } 
            }else{
                $indicadores->ab_a= 0;
            }
            $indicadores->ab_b = $input['valor-b'];
            $indicadores->ab_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'MAYO'){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->may_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->may_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->may_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->may_a=  ($input['valor-b']/$input['valor-c'])*1000;    
                }
            }
            }else{
                $indicadores->may_a= 0;
            }
            $indicadores->may_b = $input['valor-b'];
            $indicadores->may_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'JUNIO' ){
        if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->jun_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->jun_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->jun_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->jun_a=  ($input['valor-b']/$input['valor-c'])*1000;  
                }
            }
         }else{
                $indicadores->jun_a= 0;
            }
            $indicadores->jun_b = $input['valor-b'];
            $indicadores->jun_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'JULIO' ){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->jul_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->jul_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->jul_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->jul_a=  ($input['valor-b']/$input['valor-c'])*1000;     
                }
            }
         }else{
                $indicadores->jul_a= 0;
            }
            $indicadores->jul_b = $input['valor-b'];
            $indicadores->jul_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'AGOSTO' ){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->ago_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->ago_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->ago_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->ago_a=  ($input['valor-b']/$input['valor-c'])*1000;  
                }
            }
        }else{
            $indicadores->ago_a= 0;
        }
            $indicadores->ago_b = $input['valor-b'];
            $indicadores->ago_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'SEPTIEMBRE' ){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->sep_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->sep_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->sep_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->sep_a=  ($input['valor-b']/$input['valor-c'])*1000; 
                }
            }
        }else{
            $indicadores->sep_a= 0;
        }
            $indicadores->sep_b = $input['valor-b'];
            $indicadores->sep_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'OCTUBRE'){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->oct_a= (($input['valor-b']/$input['valor-c'])*100);      
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->oct_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->oct_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->oct_a= (($input['valor-b']/$input['valor-c'])*1000); 
                }
            }
        }else{
            $indicadores->oct_a= 0;
        }
            $indicadores->oct_b = $input['valor-b'];
            $indicadores->oct_c = $input['valor-c'];
        }else if($input['mes-indicador'] == 'NOVIEMBRE'){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador != 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->nov_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->nov_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->nov_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->nov_a=  ($input['valor-b']/$input['valor-c'])*1000;  
                }
            }
            }else{
                $indicadores->nov_a= 0;
            }
            $indicadores->nov_b = $input['valor-b'];
            $indicadores->nov_c = $input['valor-c'];
        
        }else if($input['mes-indicador'] == 'DICIEMBRE' ){
            if($input['valor-c'] != 0){
            if($indicador[0]->id_indicador!= 0){
                if($indicador[0]->formula == "(B/C)*100"){
                    $indicadores->dic_a=  ($input['valor-b']/$input['valor-c'])*100;           
                }else if($indicador[0]->formula == "SUM B/C"){
                    $indicadores->dic_a=  ($input['valor-b']/$input['valor-c']);
                }else if($indicador[0]->formula == "((B-C)/C)*100"){
                    $indicadores->dic_a=  (($input['valor-b']-$input['valor-c'])/$input['valor-c'])*100;
                }else if($indicador[0]->formula == "(B/C)*D"){
                    $indicadores->dic_a=  ($input['valor-b']/$input['valor-c'])*1000;
                }
            }
        }else{
            $indicadores->dic_a= 0;
        }
            $indicadores->dic_b = $input['valor-b'];
            $indicadores->dic_c = $input['valor-c'];
        }

        $sum_b= $indicadores->en_b+$indicadores->feb_b+$indicadores->mar_b+$indicadores->ab_b+$indicadores->may_b+$indicadores->jun_b+$indicadores->jul_b+$indicadores->ago_b+$indicadores->sep_b+$indicadores->oct_b+$indicadores->nov_b+$indicadores->dic_b;
        $sum_c= $indicadores->en_c+$indicadores->feb_c+$indicadores->mar_c+$indicadores->ab_c+$indicadores->may_c+$indicadores->jun_c+$indicadores->jul_c+$indicadores->ago_c+$indicadores->sep_c+$indicadores->oct_c+$indicadores->nov_c+$indicadores->dic_c;

        if($sum_c != 0){
        if($indicador[0]->formula == "(B/C)*100"){
        $indicadores->anual_a = ($sum_b/$sum_c)*100;    
        }else if($indicador[0]->formula == "SUM B/C"){
            $indicadores->anual_a = ($sum_b/$sum_c);    
        }else if($indicador[0]->formula == "((B-C)/C)*100"){
            $indicadores->anual_a = (($sum_b-$sum_c)/$sum_c)*100; 
        }else if($indicador[0]->formula == "(B/C)*D"){
            $indicadores->anual_a = ($sum_b/$sum_c)*1000; 
        }
        }else{
            $indicadores->anual_a =0;
        }
        $indicadores->anual_b = $sum_b;
        $indicadores->anual_c = $sum_c;

        $indicadores->save();
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'editarIndicador';

        return $response->withHeader('Location',"http://$host$uri/$extra/$id_indicadores/$id_indicador")->withStatus(302);
    }catch(\PDOException $e){
        $this->logger->error($e->getMessage());
    }
}else{
    $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol = $usuario[0]->rol;
    $id_indicadores = $args['id_indicadores'];
    $id_indicador =$args['id_indicador'];
    $indicador = Indicador::all();
    $indicadores = Indicadores::all();
    $variables = Variables::all();  
    $pp = Pp::all();
    $base_meta = BaseMeta::all();
    $año = date("Y"); 

    $msg = 'El registro no ha sido guardado. Asegurate de llenar todo el formulario y haz clic en guardar nuevamente.';
    $clase = 'red';

 
        return $this->container->get('view')->render($response, 'edit_indicador.html',['message'=>$msg,'class'=>$clase,
            'id_indicadores'=>$id_indicadores, 'id_indicador'=>$id_indicador,'pp'=>$pp,'indicador'=>$indicador,'indicadores'=>$indicadores,
        'variables'=>$variables,'rol'=>$rol,
        'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'baseMeta'=>$base_meta,'año'=>$año]);
        
}
    }
        }

        public function eliminarIndicador(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
            if(session_status() == PHP_SESSION_ACTIVE){
        $id = $args['id_indicadores'];

        $indicador = Indicadores::find($id);
        
       
        if($indicador != null){
            $indicador->delete();
         
            }
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        return $response->withHeader('Location',"http://$host$uri/indicadores")->withStatus(302);
            }
        }


    public function verFicha(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $id_indicador = $args['indicador'];
        $año = $args['fecha'];

        $numero_indicador = Indicador::where('id_indicador','=',$id_indicador)->get('numero');
        $indicador = $numero_indicador[0]->numero;
        $file_search= Ficha::where('numero_indicador','=',$indicador)->where('año','=',$año)->get('ficha');
        
        $file = $file_search[0]->ficha;

        $directory = $this->container->get('fichas');
        $filepath = $directory.DIRECTORY_SEPARATOR.$file;
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.basename($filepath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
           $response->getBody()->write(readfile($filepath));
            return $response;
        }else{
            return $response->withHeader('Location','indicadores');
        }

        }     
    }

}