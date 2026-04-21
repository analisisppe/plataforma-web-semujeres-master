<?php
declare(strict_types=1);

namespace App\Application\Controladores;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use App\Application\Modelos\Entregable;
use App\Application\Modelos\Municipios;
use App\Application\Modelos\Avance;
use App\Application\Modelos\Programa;
use NumberFormatter;

class avanceController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarAvance(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $id = $args['id'];
        
       
        $entregable = Entregable::All();
        
        $programa = Programa::All();

        $municipios = Municipios::All();

        $avance = Avance::All();

      
        $sumaavance= Avance::where('fk_id_entregable','=',$id)->sum('avance_entregable');
    
        $sumamonto = Avance::where('fk_id_entregable','=',$id)->sum('monto');
                
        if($rol == 'Enlace Externo'){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
            $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Enlace SEMUJERES' ){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =0;
            $suma15=0;
            $sumaPadres= 0;
            $sumaServidores=0;
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Administrador'){
            foreach($entregable as $e){
                   if($e['id_entregable'] == $id){
                    foreach($programa as $p){
                        if($p['id_programa'] == $e['fk_id_programa']){
                            if($p['rol_usuario'] == 'Enlace GEPEA'){
                                $mBeneficiados= 0;
                                $hBeneficiados = 0;
                                $mDiscapacidad = 0;
                                $hDiscapacidad = 0;
                                $mMaya = 0;
                                $hMaya = 0;
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                               
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace Externo' || $p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Administrador'){
                               $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }
                        }
                    }
                   }
            }
        
        }else if($rol == 'Admin SEMUJERES-GEPEA'){
            foreach($entregable as $e){
                if($e['id_entregable'] == $id){
                 foreach($programa as $p){
                     if($p['id_programa'] == $e['fk_id_programa']){
                         if($p['rol_usuario'] == 'Enlace GEPEA'){
                            $mBeneficiados= 0;
                            $hBeneficiados = 0;
                            $mDiscapacidad = 0;
                            $hDiscapacidad = 0;
                            $mMaya = 0;
                            $hMaya = 0;
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                             $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                             $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                             $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                             $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);    
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }
                     }
                 }
                }
         }

        }
        else if($rol == 'Enlace GEPEA'){
            $mensual=Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
            $suma10=Avance::where('fk_id_entregable','=',$id)->sum('m_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=(Avance::where('fk_id_entregable','=',$id)->sum('m_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('h_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('ms_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('hs_15'));
            $sumaPadres=Avance::where('fk_id_entregable','=',$id)->sum('m_padres')+Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=Avance::where('fk_id_entregable','=',$id)->sum('m_ser')+Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
            $mBeneficiados= 0;
            $hBeneficiados = 0;
            $mDiscapacidad = 0;
            $hDiscapacidad = 0;
            $mMaya = 0;
            $hMaya = 0;
        }

        return $this->container->get('view')->render($response, 'avance.html',['nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,
        'rol'=>$rol,'avance'=>$avance, 'entregable'=>$entregable, 'programa'=>$programa,'identregable'=>$id, 'municipios'=>$municipios, 'monto'=>$sumamonto, 'sumaavance'=>$sumaavance,'mensual'=>$mensual,'sum10'=>$suma10,'sum15'=>$suma15,'sumPadres'=>$sumaPadres,'sumServidores'=>$sumaServidores, 'mBeneficiados'=>$mBeneficiados,'hBeneficiados'=>$hBeneficiados,'mDiscapacidad'=>$mDiscapacidad,'hDiscapacidad'=>$hDiscapacidad,'mMaya'=>$mMaya,'hMaya'=>$hMaya]);
    }
    }
    public function guardarAvance(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
       
        $id = $args['id'];
        $usuario = $_SESSION['user'];
        $rol = $usuario[0]->rol;
        if(!empty($input['mes'])){
        $prueba = Avance::where('mes','=', $input['mes'])->first();

        if(empty($prueba) == false){
    
            
        if($prueba->avance_finalizado != null){
        $fechaActual = date("Y-m-d H:i:s"); 
        if($fechaActual>$prueba->avance_finalizado){

            $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $entregable = Entregable::All();
        
        $entregable = Entregable::All();
        
        $programa = Programa::All();

        $municipios = Municipios::All();

        $avance = Avance::All();

      
        $sumaavance= Avance::where('fk_id_entregable','=',$id)->sum('avance_entregable');
    
        $sumamonto = Avance::where('fk_id_entregable','=',$id)->sum('monto');
                
        if($rol == 'Enlace Externo'){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
            $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Enlace SEMUJERES' ){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =0;
            $suma15=0;
            $sumaPadres= 0;
            $sumaServidores=0;
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Administrador'){
            foreach($entregable as $e){
                   if($e['id_entregable'] == $id){
                    foreach($programa as $p){
                        if($p['id_programa'] == $e['fk_id_programa']){
                            if($p['rol_usuario'] == 'Enlace GEPEA'){
                                $mBeneficiados= 0;
                                $hBeneficiados = 0;
                                $mDiscapacidad = 0;
                                $hDiscapacidad = 0;
                                $mMaya = 0;
                                $hMaya = 0;
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                               
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace Externo' || $p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Administrador'){
                               $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }
                        }
                    }
                   }
            }
        
        }else if($rol == 'Admin SEMUJERES-GEPEA'){
            foreach($entregable as $e){
                if($e['id_entregable'] == $id){
                 foreach($programa as $p){
                     if($p['id_programa'] == $e['fk_id_programa']){
                         if($p['rol_usuario'] == 'Enlace GEPEA'){
                            $mBeneficiados= 0;
                            $hBeneficiados = 0;
                            $mDiscapacidad = 0;
                            $hDiscapacidad = 0;
                            $mMaya = 0;
                            $hMaya = 0;
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                             $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                             $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                             $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                             $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);    
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }
                     }
                 }
                }
         }

        }
        else if($rol == 'Enlace GEPEA'){
            $mensual=Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
            $suma10=Avance::where('fk_id_entregable','=',$id)->sum('m_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=(Avance::where('fk_id_entregable','=',$id)->sum('m_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('h_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('ms_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('hs_15'));
            $sumaPadres=Avance::where('fk_id_entregable','=',$id)->sum('m_padres')+Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=Avance::where('fk_id_entregable','=',$id)->sum('m_ser')+Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
            $mBeneficiados= 0;
            $hBeneficiados = 0;
            $mDiscapacidad = 0;
            $hDiscapacidad = 0;
            $mMaya = 0;
            $hMaya = 0;
        }

        $message = 'FECHA LIMITE DE CAPTURA EXCEDIDA';
        $class = 'red';
        
    
    return $this->container->get('view')->render($response, 'avance.html',['class'=>$class,'message'=>$message,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,
    'rol'=>$rol,'avance'=>$avance, 'entregable'=>$entregable, 'programa'=>$programa,'identregable'=>$id, 'municipios'=>$municipios, 'monto'=>$sumamonto, 'sumaavance'=>$sumaavance,'mensual'=>$mensual,'sum10'=>$suma10,'sum15'=>$suma15,'sumPadres'=>$sumaPadres,'sumServidores'=>$sumaServidores, 'mBeneficiados'=>$mBeneficiados,'hBeneficiados'=>$hBeneficiados,'mDiscapacidad'=>$mDiscapacidad,'hDiscapacidad'=>$hDiscapacidad,'mMaya'=>$mMaya,'hMaya'=>$hMaya]);
            }else{

                $en = Entregable::where('id_entregable','=',$id)->first();
                $id_programa = $en->fk_id_programa;
                $relacion = Programa::where('id_programa','=',$id_programa)->get('rol_usuario');

                if( $rol == 'Enlace Externo'){
                try{
                     if($input['poblacion']!=[]){
                    
                        for($mt=0;$mt<count($input['m_t1']); $mt++){
                            for($ht=0; $ht<count($input['h_t1']); $ht++){
                                for($md=0;$md<count($input['m_d1']); $md++){
                                    for($hd=0; $hd<count($input['h_d1']); $hd++){
                                        for($mi=0; $mi<count($input['m_i1']);$mi++){
                                            for($hi=0; $hi<count($input['h_i1']);$hi++){
                                                for($po=0; $po<count($input['poblacion']); $po++){
                                                    if($po == $mt && $po == $ht && $po == $md && $po == $hd && $po == $mi && $po == $hi){
                                                        
                                                        $avance= new Avance();
                                                        $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
                                                        $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
                                                        $avance->avance_entregable = ((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
                                                        $avance->monto = 0;
                                                        $avance->proyecto = 'No aplica';
                                                        $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
                                                        $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
                                                        $avance->avance_finalizado = $prueba->avance_finalizado;
                                                        $avance->fk_id_entregable= $id;
                                                        $avance->poblacion = $input['poblacion'][$po];
                                                        $avance->m_t1 = $input['m_t1'][$mt];
                                                        $avance->m_d1 = $input['m_d1'][$md];
                                                        $avance->m_i1 = $input['m_i1'][$mi];
                                                        $avance->h_t1 = $input['h_t1'][$ht];
                                                        $avance->h_d1 = $input['h_d1'][$hd];
                                                        $avance->h_i1 = $input['h_i1'][$hi];
                                                        $avance->m_ts = 0;
                                                        $avance->m_ds = 0;
                                                        $avance->m_is = 0;
                                                        $avance->h_ts = 0;
                                                        $avance->h_ds = 0;
                                                        $avance->h_is = 0;
                                                        //GEPEA
                                                        $avance->m_10 = 0;
                                                        $avance->h_10 = 0;
                                                        $avance->m_15= 0;
                                                        $avance->h_15= 0;
                                                        $avance->m_ser= 0;
                                                        $avance->h_ser= 0;
                                                        $avance->m_padres=0; 
                                                        $avance->h_padres= 0;
                                                        $avance->ms_10= 0;
                                                        $avance->hs_10= 0;
                                                        $avance->ms_15= 0;
                                                        $avance->hs_15= 0;
                                                        $avance->ms_ser= 0;
                                                        $avance->hs_ser= 0;
                                                        $avance->ms_padres=0; 
                                                        $avance-> hs_padres= 0;
                                                        $avance->save();
                                                    }
                                        
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
    
                }catch(\PDOException $e){
                    $this->logger->error($e->getMessage());
                }

                }else{
                   
                if($relacion[0]->rol_usuario == 'Enlace GEPEA'){
                    $proy = 'No aplica';
                    $money = 0;
                }else{
                    $proy = $input['proyecto'];
                    $money = (isset($input['monto']) ? $input['monto'] : 0) ;
                }
                try{
                
                $avance= new Avance();
               
                $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
                $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
                $avance->avance_entregable = ((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
                $avance->monto = $money;

               

                if(isset($input['proyecto'])){
                    if($input['proyecto'] != 'Seleccione...' ){
                        $avance->proyecto = $proy;
                    }else{
                        $avance->proyecto = 'No aplica';
                    }
                }else{
                    $avance->proyecto = 'No aplica';
                }
                $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
                $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
                $avance->avance_finalizado = $prueba->avance_finalizado;
                $avance->fk_id_entregable= $id;
                $avance->poblacion = (isset($input['poblacion']) ? $input['poblacion'] : 'No aplica');
                $avance->m_t1 = (isset($input['m_t1']) ? $input['m_t1'] : 0 );
                $avance->m_d1 = (isset($input['m_d1']) ? $input['m_d1'] : 0);
                $avance->m_i1 = (isset($input['m_i1']) ? $input['m_i1'] : 0);
                $avance->h_t1 = (isset($input['h_t1']) ? $input['h_t1'] : 0);
                $avance->h_d1 = (isset($input['h_d1']) ? $input['h_d1'] : 0);
                $avance->h_i1 = (isset($input['h_i1']) ? $input['h_i1'] : 0);
                $avance->m_ts = (isset($input['m_ts']) ? $input['m_ts'] : 0);
                $avance->m_ds = (isset($input['m_ds']) ? $input['m_ds'] : 0);
                $avance->m_is = (isset($input['m_is']) ? $input['m_is'] : 0);
                $avance->h_ts = (isset($input['h_ts']) ? $input['h_ts'] : 0);
                $avance->h_ds = (isset($input['h_ds']) ? $input['h_ds'] : 0);
                $avance->h_is = (isset($input['h_is']) ? $input['h_is'] : 0);
                //GEPEA
                $avance->m_10 = (isset($input['m_10']) ? $input['m_10'] : 0);//mujeres entre 10-14 años primera vez GPA
                $avance->h_10 = (isset($input['h_10']) ? $input['h_10'] : 0);//hombres entre 10-14 años primera vez GPA
                $avance->m_15= (isset($input['m_15']) ? $input['m_15'] : 0);//mujeres entre 15-19 años primera vez GPA
                $avance->h_15= (isset($input['h_15']) ? $input['h_15'] : 0);//hombres entre 15-19 años primera vez GPA
                $avance->m_ser= (isset($input['m_ser']) ? $input['m_ser'] : 0);//mujeres servidores primera vez GPA
                $avance->h_ser= (isset($input['h_ser']) ? $input['h_ser'] : 0);//hombres servidores primera vez GPA
                $avance->m_padres= (isset($input['m_padres']) ? $input['m_padres'] : 0);//mujeres padres primera vez GPA
                $avance->h_padres= (isset($input['h_padres']) ? $input['h_padres'] : 0); //hombres padres primera vez GPA
                $avance->ms_10= (isset($input['ms_10']) ? $input['ms_10'] : 0);//mujeres entre 10-14 años seguimiento GPA
                $avance->hs_10= (isset($input['hs_10']) ? $input['hs_10'] : 0);//hombres entre 10-14 años seguimiento GPA
                $avance->ms_15= (isset($input['ms_15']) ? $input['ms_15'] : 0);//mujeres entre 15-19 años segumiento GPA
                $avance->hs_15= (isset($input['hs_15']) ? $input['hs_15'] : 0); //mujeres entre 15-10 años seguimiento GPA
                $avance->ms_ser= (isset($input['ms_ser']) ? $input['ms_ser'] : 0);//mujeres servidorees segumiento GPA
                $avance->hs_ser= (isset($input['hs_ser']) ? $input['hs_ser'] : 0);//hombres servidores segumiento GPA
                $avance->ms_padres= (isset($input['ms_padres']) ? $input['ms_padres'] : 0);//mujeres padres seguimiento GPA
                $avance-> hs_padres= (isset($input['hs_padres']) ? $input['hs_padres'] : 0);//hombres padres seguimiento GPA 
                $avance->save();
            }catch(\PDOException $e){
                    $this->logger->error($e->getMessage());
                }
            }
        
                $host  = $_SERVER['HTTP_HOST'];
                $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
               return $response->withHeader('Location',"http://$host$uri/avance/$id")->withStatus(302);
            
            }
        } else{
            $en = Entregable::where('id_entregable','=',$id)->first();
                $id_programa = $en->fk_id_programa;
                $relacion = Programa::where('id_programa','=',$id_programa)->get('rol_usuario');


                if( $relacion[0]->rol_usuario == 'Enlace Externo' || $rol == 'Enlace Externo'){
                   try{
                     if($input['poblacion']!=[]){
                         
                      
                       
                            for($mt=0;$mt<count($input['m_t1']); $mt++){
                                for($ht=0; $ht<count($input['h_t1']); $ht++){
                                    for($md=0;$md<count($input['m_d1']); $md++){
                                        for($hd=0; $hd<count($input['h_d1']); $hd++){
                                            for($mi=0; $mi<count($input['m_i1']);$mi++){
                                                for($hi=0; $hi<count($input['h_i1']);$hi++){
                                                    for($po=0; $po<count($input['poblacion']); $po++){
                                                    if($po == $mt && $po == $ht && $po == $md && $po == $hd && $po == $mi && $po == $hi){
                                                    
                                                        $avance= new Avance();
                                                        $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
                                                        $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
                                                        $avance->avance_entregable = ((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
                                                        $avance->monto = 0;
                                                        $avance->proyecto = 'No aplica';
                                                        $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
                                                        $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
                                                        $avance->fk_id_entregable= $id;
                                                        $avance->poblacion = $input['poblacion'][$po];
                                                        $avance->m_t1 = $input['m_t1'][$mt];
                                                        $avance->m_d1 = $input['m_d1'][$md];
                                                        $avance->m_i1 = $input['m_i1'][$mi];
                                                        $avance->h_t1 = $input['h_t1'][$ht];
                                                        $avance->h_d1 = $input['h_d1'][$hd];
                                                        $avance->h_i1 = $input['h_i1'][$hi];
                                                        $avance->m_ts = 0;
                                                        $avance->m_ds = 0;
                                                        $avance->m_is = 0;
                                                        $avance->h_ts = 0;
                                                        $avance->h_ds = 0;
                                                        $avance->h_is = 0;
                                                        //GEPEA
                                                        $avance->m_10 = 0;
                                                        $avance->h_10 = 0;
                                                        $avance->m_15= 0;
                                                        $avance->h_15= 0;
                                                        $avance->m_ser= 0;
                                                        $avance->h_ser= 0;
                                                        $avance->m_padres=0; 
                                                        $avance->h_padres= 0;
                                                        $avance->ms_10= 0;
                                                        $avance->hs_10= 0;
                                                        $avance->ms_15= 0;
                                                        $avance->hs_15= 0;
                                                        $avance->ms_ser= 0;
                                                        $avance->hs_ser= 0;
                                                        $avance->ms_padres=0; 
                                                        $avance-> hs_padres= 0;
                                                        $avance->save();
                                                    }
                                        
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
    
                   
                } catch(\PDOException $e){
                    $this->logger->error($e->getMessage());
                }
                }else{



                if($relacion[0]->rol_usuario == 'Enlace GEPEA'){
                    $proy = 'No aplica';
                    $money = 0;
                }else{
                    $proy = $input['proyecto'];
                    $money =(isset($input['monto']) ? $input['monto'] : 0);
                }
                try{
            $avance= new Avance();
           
            $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
            $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
            $avance->avance_entregable =((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
            $avance->monto =$money;
            if(isset($input['proyecto'])){
            if($input['proyecto'] != 'Seleccione...' ){
                $avance->proyecto = $proy;
            }else{
                $avance->proyecto = 'No aplica';
            }
        }else{
            $avance->proyecto = 'No aplica';
        }
    
        $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
        $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
            $avance->fk_id_entregable= $id;
            $avance->poblacion = (isset($input['poblacion']) ? $input['poblacion'] : 'No aplica');
            $avance->m_t1 = (isset($input['m_t1']) ? $input['m_t1'] : 0 );
                $avance->m_d1 = (isset($input['m_d1']) ? $input['m_d1'] : 0);
                $avance->m_i1 = (isset($input['m_i1']) ? $input['m_i1'] : 0);
                $avance->h_t1 = (isset($input['h_t1']) ? $input['h_t1'] : 0);
                $avance->h_d1 = (isset($input['h_d1']) ? $input['h_d1'] : 0);
                $avance->h_i1 = (isset($input['h_i1']) ? $input['h_i1'] : 0);
                $avance->m_ts = (isset($input['m_ts']) ? $input['m_ts'] : 0);
                $avance->m_ds = (isset($input['m_ds']) ? $input['m_ds'] : 0);
                $avance->m_is = (isset($input['m_is']) ? $input['m_is'] : 0);
                $avance->h_ts = (isset($input['h_ts']) ? $input['h_ts'] : 0);
                $avance->h_ds = (isset($input['h_ds']) ? $input['h_ds'] : 0);
                $avance->h_is = (isset($input['h_is']) ? $input['h_is'] : 0);
                //GEPEA
                $avance->m_10 = (isset($input['m_10']) ? $input['m_10'] : 0);//mujeres entre 10-14 años primera vez GPA
                $avance->h_10 = (isset($input['h_10']) ? $input['h_10'] : 0);//hombres entre 10-14 años primera vez GPA
                $avance->m_15= (isset($input['m_15']) ? $input['m_15'] : 0);//mujeres entre 15-19 años primera vez GPA
                $avance->h_15= (isset($input['h_15']) ? $input['h_15'] : 0);//hombres entre 15-19 años primera vez GPA
                $avance->m_ser= (isset($input['m_ser']) ? $input['m_ser'] : 0);//mujeres servidores primera vez GPA
                $avance->h_ser= (isset($input['h_ser']) ? $input['h_ser'] : 0);//hombres servidores primera vez GPA
                $avance->m_padres= (isset($input['m_padres']) ? $input['m_padres'] : 0);//mujeres padres primera vez GPA
                $avance->h_padres= (isset($input['h_padres']) ? $input['h_padres'] : 0); //hombres padres primera vez GPA
                $avance->ms_10= (isset($input['ms_10']) ? $input['ms_10'] : 0);//mujeres entre 10-14 años seguimiento GPA
                $avance->hs_10= (isset($input['hs_10']) ? $input['hs_10'] : 0);//hombres entre 10-14 años seguimiento GPA
                $avance->ms_15= (isset($input['ms_15']) ? $input['ms_15'] : 0);//mujeres entre 15-19 años segumiento GPA
                $avance->hs_15= (isset($input['hs_15']) ? $input['hs_15'] : 0); //mujeres entre 15-10 años seguimiento GPA
                $avance->ms_ser= (isset($input['ms_ser']) ? $input['ms_ser'] : 0);//mujeres servidorees segumiento GPA
                $avance->hs_ser= (isset($input['hs_ser']) ? $input['hs_ser'] : 0);//hombres servidores segumiento GPA
                $avance->ms_padres= (isset($input['ms_padres']) ? $input['ms_padres'] : 0);//mujeres padres seguimiento GPA
                $avance-> hs_padres= (isset($input['hs_padres']) ? $input['hs_padres'] : 0);//hombres padres seguimiento GPA 
            $avance->save();
        } catch(\PDOException $e){
            $this->logger->error($e->getMessage());
        }
        }
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
           return $response->withHeader('Location',"http://$host$uri/avance/$id")->withStatus(302);
         
        }
    }else{

        $en = Entregable::where('id_entregable','=',$id)->first();
                $id_programa = $en->fk_id_programa;
                $relacion = Programa::where('id_programa','=',$id_programa)->get('rol_usuario');

                if( $relacion[0]->rol_usuario == 'Enlace Externo'){
                        try{
                     if($input['poblacion']!=[]){
                        for($mt=0;$mt<count($input['m_t1']); $mt++){
                            for($ht=0; $ht<count($input['h_t1']); $ht++){
                                for($md=0;$md<count($input['m_d1']); $md++){
                                    for($hd=0; $hd<count($input['h_d1']); $hd++){
                                        for($mi=0; $mi<count($input['m_i1']);$mi++){
                                            for($hi=0; $hi<count($input['h_i1']);$hi++){
                                                for($po=0; $po<count($input['poblacion']); $po++){
                                                    if($po == $mt && $po == $ht && $po == $md && $po == $hd && $po == $mi && $po == $hi){
                                                      
                                                        $avance= new Avance();
                                                        $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
                                                        $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
                                                        $avance->avance_entregable = ((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
                                                        $avance->monto = 0;
                                                           $avance->proyecto = 'No aplica';
                                                        $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
                                                        $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
                                                        $avance->fk_id_entregable= $id;
                                                        $avance->poblacion = $input['poblacion'][$po];
                                                        $avance->m_t1 = $input['m_t1'][$mt];
                                                        $avance->m_d1 = $input['m_d1'][$md];
                                                        $avance->m_i1 = $input['m_i1'][$mi];
                                                        $avance->h_t1 = $input['h_t1'][$ht];
                                                        $avance->h_d1 = $input['h_d1'][$hd];
                                                        $avance->h_i1 = $input['h_i1'][$hi];
                                                        $avance->m_ts = 0;
                                                        $avance->m_ds = 0;
                                                        $avance->m_is = 0;
                                                        $avance->h_ts = 0;
                                                        $avance->h_ds = 0;
                                                        $avance->h_is = 0;
                                                        //GEPEA
                                                        $avance->m_10 = 0;
                                                        $avance->h_10 = 0;
                                                        $avance->m_15= 0;
                                                        $avance->h_15= 0;
                                                        $avance->m_ser= 0;
                                                        $avance->h_ser= 0;
                                                        $avance->m_padres=0; 
                                                        $avance->h_padres= 0;
                                                        $avance->ms_10= 0;
                                                        $avance->hs_10= 0;
                                                        $avance->ms_15= 0;
                                                        $avance->hs_15= 0;
                                                        $avance->ms_ser= 0;
                                                        $avance->hs_ser= 0;
                                                        $avance->ms_padres=0; 
                                                        $avance-> hs_padres= 0;
                                                        $avance->save();
                                                    }
                                        
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
    
                   
                 } catch(\PDOException $e){
                        $this->logger->error($e->getMessage());
                    }
                }else{

                if($relacion[0]->rol_usuario == 'Enlace GEPEA'){
                    $proy = 'No aplica';
                    $money = 0;
                }else{
                    $proy = $input['proyecto'];
                    $money = (isset($input['monto']) ? $input['monto'] : 0);
                }

                try{
        $avance= new Avance();
       
        $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
        $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
        $avance->avance_entregable =((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
        $avance->monto = $money;
        if(isset($input['proyecto'])){
            if($input['proyecto'] != 'Seleccione...' ){
                $avance->proyecto = $proy;
            }else{
                $avance->proyecto = 'No aplica';
            }
        }else{
            $avance->proyecto = 'No aplica';
        }
        $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
        $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
        $avance->fk_id_entregable= $id;
        $avance->poblacion = (isset($input['poblacion']) ? $input['poblacion'] : 'No aplica');
        $avance->m_t1 = (isset($input['m_t1']) ? $input['m_t1'] : 0 );
        $avance->m_d1 = (isset($input['m_d1']) ? $input['m_d1'] : 0);
        $avance->m_i1 = (isset($input['m_i1']) ? $input['m_i1'] : 0);
        $avance->h_t1 = (isset($input['h_t1']) ? $input['h_t1'] : 0);
        $avance->h_d1 = (isset($input['h_d1']) ? $input['h_d1'] : 0);
        $avance->h_i1 = (isset($input['h_i1']) ? $input['h_i1'] : 0);
        $avance->m_ts = (isset($input['m_ts']) ? $input['m_ts'] : 0);
        $avance->m_ds = (isset($input['m_ds']) ? $input['m_ds'] : 0);
        $avance->m_is = (isset($input['m_is']) ? $input['m_is'] : 0);
        $avance->h_ts = (isset($input['h_ts']) ? $input['h_ts'] : 0);
        $avance->h_ds = (isset($input['h_ds']) ? $input['h_ds'] : 0);
        $avance->h_is = (isset($input['h_is']) ? $input['h_is'] : 0);
        //GEPEA
        $avance->m_10 = (isset($input['m_10']) ? $input['m_10'] : 0);//mujeres entre 10-14 años primera vez GPA
        $avance->h_10 = (isset($input['h_10']) ? $input['h_10'] : 0);//hombres entre 10-14 años primera vez GPA
        $avance->m_15= (isset($input['m_15']) ? $input['m_15'] : 0);//mujeres entre 15-19 años primera vez GPA
        $avance->h_15= (isset($input['h_15']) ? $input['h_15'] : 0);//hombres entre 15-19 años primera vez GPA
        $avance->m_ser= (isset($input['m_ser']) ? $input['m_ser'] : 0);//mujeres servidores primera vez GPA
        $avance->h_ser= (isset($input['h_ser']) ? $input['h_ser'] : 0);//hombres servidores primera vez GPA
        $avance->m_padres= (isset($input['m_padres']) ? $input['m_padres'] : 0);//mujeres padres primera vez GPA
        $avance->h_padres= (isset($input['h_padres']) ? $input['h_padres'] : 0); //hombres padres primera vez GPA
        $avance->ms_10= (isset($input['ms_10']) ? $input['ms_10'] : 0);//mujeres entre 10-14 años seguimiento GPA
        $avance->hs_10= (isset($input['hs_10']) ? $input['hs_10'] : 0);//hombres entre 10-14 años seguimiento GPA
        $avance->ms_15= (isset($input['ms_15']) ? $input['ms_15'] : 0);//mujeres entre 15-19 años segumiento GPA
        $avance->hs_15= (isset($input['hs_15']) ? $input['hs_15'] : 0); //mujeres entre 15-10 años seguimiento GPA
        $avance->ms_ser= (isset($input['ms_ser']) ? $input['ms_ser'] : 0);//mujeres servidorees segumiento GPA
        $avance->hs_ser= (isset($input['hs_ser']) ? $input['hs_ser'] : 0);//hombres servidores segumiento GPA
        $avance->ms_padres= (isset($input['ms_padres']) ? $input['ms_padres'] : 0);//mujeres padres seguimiento GPA
        $avance-> hs_padres= (isset($input['hs_padres']) ? $input['hs_padres'] : 0);//hombres padres seguimiento GPA 
        
        $avance->save();
    }catch(\PDOException $e){
            $this->logger->error($e->getMessage());
        }
    }

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
       return $response->withHeader('Location',"http://$host$uri/avance/$id")->withStatus(302);
     
    }
}else{
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $id = $args['id'];
     
    $entregable = Entregable::All();
    
    $programa = Programa::All();

    $municipios = Municipios::All();

    $avance = Avance::All();

    $sumaavance= Avance::where('fk_id_entregable','=',$id)->sum('avance_entregable');

    $sumamonto = Avance::where('fk_id_entregable','=',$id)->sum('monto');
            
    if($rol == 'Enlace Externo'){
        $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
        $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
        $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
        $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
        $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
         $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                            $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                            $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                            $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
    }else if($rol == 'Enlace SEMUJERES' ){
        $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
        $suma10 =0;
        $suma15=0;
        $sumaPadres= 0;
        $sumaServidores=0;
         $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                            $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                            $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                            $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
    }else if($rol == 'Administrador'){
        foreach($entregable as $e){
               if($e['id_entregable'] == $id){
                foreach($programa as $p){
                    if($p['id_programa'] == $e['fk_id_programa']){
                        if($p['rol_usuario'] == 'Enlace GEPEA'){
                            $mBeneficiados= 0;
                            $hBeneficiados = 0;
                            $mDiscapacidad = 0;
                            $hDiscapacidad = 0;
                            $mMaya = 0;
                            $hMaya = 0;
                            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                           
                            $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                            $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                            $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                            $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                        }else if($p['rol_usuario'] == 'Enlace Externo' || $p['rol_usuario'] == 'Enlace SEMUJERES'){
                             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                            $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                            $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                            $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                         
                            $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                            $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                            $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                            $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                        }else if($p['rol_usuario'] == 'Administrador'){
                           $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                            $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                            $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                            $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                            $suma10 =0;
                            $suma15=0;
                            $sumaPadres= 0;
                            $sumaServidores=0;
                        }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                            $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                            $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                            $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                            $suma10 =0;
                            $suma15=0;
                            $sumaPadres= 0;
                            $sumaServidores=0;
                        }
                    }
                }
               }
        }
    
    }else if($rol == 'Admin SEMUJERES-GEPEA'){
        foreach($entregable as $e){
            if($e['id_entregable'] == $id){
             foreach($programa as $p){
                 if($p['id_programa'] == $e['fk_id_programa']){
                     if($p['rol_usuario'] == 'Enlace GEPEA'){
                        $mBeneficiados= 0;
                        $hBeneficiados = 0;
                        $mDiscapacidad = 0;
                        $hDiscapacidad = 0;
                        $mMaya = 0;
                        $hMaya = 0;
                         $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                         $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                         $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                         $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                         $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                        }else if($p['rol_usuario'] == 'Enlace SEMUJERES'){
                             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                            $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                            $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                            $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                         $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);    
                         $suma10 =0;
                         $suma15=0;
                         $sumaPadres= 0;
                         $sumaServidores=0;
                        }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                            $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                            $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                            $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                            $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                            $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                         $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                         $suma10 =0;
                         $suma15=0;
                         $sumaPadres= 0;
                         $sumaServidores=0;
                        }
                 }
             }
            }
     }

    }
    else if($rol == 'Enlace GEPEA'){
        $mensual=Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
        $suma10=Avance::where('fk_id_entregable','=',$id)->sum('m_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
        $suma15=(Avance::where('fk_id_entregable','=',$id)->sum('m_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('h_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('ms_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('hs_15'));
        $sumaPadres=Avance::where('fk_id_entregable','=',$id)->sum('m_padres')+Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
        $sumaServidores=Avance::where('fk_id_entregable','=',$id)->sum('m_ser')+Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
        $mBeneficiados= 0;
        $hBeneficiados = 0;
        $mDiscapacidad = 0;
        $hDiscapacidad = 0;
        $mMaya = 0;
        $hMaya = 0;
    }

    $msg = 'No se guardó el avance, asegurate de llenar todos los campos y haz clic en guardar nuevamente.';
    $clase = 'red';

    return $this->container->get('view')->render($response, 'avance.html',['message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,
    'rol'=>$rol,'avance'=>$avance, 'entregable'=>$entregable, 'programa'=>$programa,'identregable'=>$id, 'municipios'=>$municipios, 'monto'=>$sumamonto, 'sumaavance'=>$sumaavance,'mensual'=>$mensual,'sum10'=>$suma10,'sum15'=>$suma15,'sumPadres'=>$sumaPadres,'sumServidores'=>$sumaServidores, 'mBeneficiados'=>$mBeneficiados,'hBeneficiados'=>$hBeneficiados,'mDiscapacidad'=>$mDiscapacidad,'hDiscapacidad'=>$hDiscapacidad,'mMaya'=>$mMaya,'hMaya'=>$hMaya]);
}
}
    }

    public function mostrarEditarAvance(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $id = $args['id_entregable'];
        $id_avance = $args['id_avance'];
        $entregable = Entregable::All();
        
        $programa = Programa::All();

        $municipios = Municipios::All();

        $avance = Avance::All();

        $sumaavance= Avance::where('fk_id_entregable','=',$id)->sum('avance_entregable');
        $sumamonto = Avance::where('fk_id_entregable','=',$id)->sum('monto');
                
        
        $totaldiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+ Avance::where('fk_id_entregable','=',$id)->sum('h_d1')+ Avance::where('fk_id_entregable','=',$id)->sum('m_ds') + Avance::where('fk_id_entregable','=',$id)->sum('h_ds');
        $totalmaya= Avance::where('fk_id_entregable','=',$id)->sum('m_i1')+ Avance::where('fk_id_entregable','=',$id)->sum('h_i1')+ Avance::where('fk_id_entregable','=',$id)->sum('m_is')+ Avance::where('fk_id_entregable','=',$id)->sum('h_is');

        if($rol == 'Enlace Externo'){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
            $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Enlace SEMUJERES' ){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =0;
            $suma15=0;
            $sumaPadres= 0;
            $sumaServidores=0;
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Administrador'){
            foreach($entregable as $e){
                   if($e['id_entregable'] == $id){
                    foreach($programa as $p){
                        if($p['id_programa'] == $e['fk_id_programa']){
                            if($p['rol_usuario'] == 'Enlace GEPEA'){
                                $mBeneficiados= 0;
                                $hBeneficiados = 0;
                                $mDiscapacidad = 0;
                                $hDiscapacidad = 0;
                                $mMaya = 0;
                                $hMaya = 0;
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                               
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace Externo' || $p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Administrador'){
                               $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }
                        }
                    }
                   }
            }
        
        }else if($rol == 'Admin SEMUJERES-GEPEA'){
            foreach($entregable as $e){
                if($e['id_entregable'] == $id){
                 foreach($programa as $p){
                     if($p['id_programa'] == $e['fk_id_programa']){
                         if($p['rol_usuario'] == 'Enlace GEPEA'){
                            $mBeneficiados= 0;
                            $hBeneficiados = 0;
                            $mDiscapacidad = 0;
                            $hDiscapacidad = 0;
                            $mMaya = 0;
                            $hMaya = 0;
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                             $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                             $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                             $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                             $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);    
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }
                     }
                 }
                }
         }

        }
        else if($rol == 'Enlace GEPEA'){
            $mensual=Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
            $suma10=Avance::where('fk_id_entregable','=',$id)->sum('m_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=(Avance::where('fk_id_entregable','=',$id)->sum('m_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('h_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('ms_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('hs_15'));
            $sumaPadres=Avance::where('fk_id_entregable','=',$id)->sum('m_padres')+Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=Avance::where('fk_id_entregable','=',$id)->sum('m_ser')+Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
            $mBeneficiados= 0;
            $hBeneficiados = 0;
            $mDiscapacidad = 0;
            $hDiscapacidad = 0;
            $mMaya = 0;
            $hMaya = 0;
        }

        

    return $this->container->get('view')->render($response, 'avance.html',['nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'id_avance'=>$id_avance,
        'rol'=>$rol,'avance'=>$avance, 'entregable'=>$entregable, 'programa'=>$programa,'identregable'=>$id, 'municipios'=>$municipios, 'monto'=>$sumamonto, 'sumaavance'=>$sumaavance,
    'totaldiscapacidad'=>$totaldiscapacidad, 'totalmaya'=>$totalmaya,
    'mensual'=>$mensual,'sum10'=>$suma10,'sum15'=>$suma15,'sumPadres'=>$sumaPadres,'sumServidores'=>$sumaServidores, 'mBeneficiados'=>$mBeneficiados,'hBeneficiados'=>$hBeneficiados,'mDiscapacidad'=>$mDiscapacidad,'hDiscapacidad'=>$hDiscapacidad,'mMaya'=>$mMaya,'hMaya'=>$hMaya]);
    }
    }
    public function editarAvance(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();
        
        $id_entregable = $args['id_entregable'];
        $id_avance = $args['id_avance'];

      
        $usuario = $_SESSION['user'];
        $rol = $usuario[0]->rol;
        if(!empty($input['mes'])){
        $prueba = Avance::where('id_avance','=',$id_avance)->where('mes','=',$input['mes'])->where('fk_id_entregable','=',$id_entregable)->get();
        if(empty($prueba) == false){

       
            $fechaActual = date("Y-m-d H:i:s"); 
            $fechaFin = $prueba[0]->avance_finalizado;
            if($fechaFin != null){
            if($fechaActual > $fechaFin){
               
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
     
        $entregable = Entregable::All();
        
        $programa = Programa::All();

        $municipios = Municipios::All();

        $avance = Avance::All();

      
        $sumaavance= Avance::where('fk_id_entregable','=',$id_entregable)->sum('avance_entregable');
    
        $sumamonto = Avance::where('fk_id_entregable','=',$id_entregable)->sum('monto');
                
        if($rol == 'Enlace Externo'){
            $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_10') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_10');
            $suma15=Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_15') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_15');
            $sumaPadres= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_padres');
            $sumaServidores=  Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_ser');
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_is');
        }else if($rol == 'Enlace SEMUJERES' ){
            $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =0;
            $suma15=0;
            $sumaPadres= 0;
            $sumaServidores=0;
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_is');
        }else if($rol == 'Administrador'){
            foreach($entregable as $e){
                   if($e['id_entregable'] == $id_entregable){
                    foreach($programa as $p){
                        if($p['id_programa'] == $e['fk_id_programa']){
                            if($p['rol_usuario'] == 'Enlace GEPEA'){
                                $mBeneficiados= 0;
                                $hBeneficiados = 0;
                                $mDiscapacidad = 0;
                                $hDiscapacidad = 0;
                                $mMaya = 0;
                                $hMaya = 0;
                                $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                               
                                $suma10 =Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_10') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_15') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace Externo' || $p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             
                                $suma10 =Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_10') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_15') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Administrador'){
                               $mBeneficiados= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }
                        }
                    }
                   }
            }
        
        }else if($rol == 'Admin SEMUJERES-GEPEA'){
            foreach($entregable as $e){
                if($e['id_entregable'] == $id_entregable){
                 foreach($programa as $p){
                     if($p['id_programa'] == $e['fk_id_programa']){
                         if($p['rol_usuario'] == 'Enlace GEPEA'){
                            $mBeneficiados= 0;
                            $hBeneficiados = 0;
                            $mDiscapacidad = 0;
                            $hDiscapacidad = 0;
                            $mMaya = 0;
                            $hMaya = 0;
                             $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                             $suma10 =Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_10') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_10');
                             $suma15=Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_15') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_15')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_15');
                             $sumaPadres= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_padres');
                             $sumaServidores=  Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);    
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                $mBeneficiados= Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }
                     }
                 }
                }
         }

        }
        else if($rol == 'Enlace GEPEA'){
            $mensual=Avance::where('fk_id_entregable','=',$id_entregable)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
            $suma10=Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_10');
            $suma15=(Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_15'))+(Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_15'))+(Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_15'))+(Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_15'));
            $sumaPadres=Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_padres');
            $sumaServidores=Avance::where('fk_id_entregable','=',$id_entregable)->sum('m_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id_entregable)->sum('hs_ser');
            $mBeneficiados= 0;
            $hBeneficiados = 0;
            $mDiscapacidad = 0;
            $hDiscapacidad = 0;
            $mMaya = 0;
            $hMaya = 0;
        }
        $message = 'FECHA LIMITE DE CAPTURA EXCEDIDA';
        $class = 'red';
        

    return $this->container->get('view')->render($response, 'avance.html',['class'=>$class,'message'=>$message,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,
    'rol'=>$rol,'avance'=>$avance, 'entregable'=>$entregable, 'programa'=>$programa,'identregable'=>$id_entregable,'id_avance'=>$id_avance, 'municipios'=>$municipios, 'monto'=>$sumamonto, 'sumaavance'=>$sumaavance,'mensual'=>$mensual,'sum10'=>$suma10,'sum15'=>$suma15,'sumPadres'=>$sumaPadres,'sumServidores'=>$sumaServidores, 'mBeneficiados'=>$mBeneficiados,'hBeneficiados'=>$hBeneficiados,'mDiscapacidad'=>$mDiscapacidad,'hDiscapacidad'=>$hDiscapacidad,'mMaya'=>$mMaya,'hMaya'=>$hMaya]);
            }
            else{
                try{
                $avance= Avance::find($id_avance);
               
                $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
                $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
                $avance->avance_entregable = ((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
                $avance->monto = (isset($input['monto']) ? $input['monto'] : 'No aplica');
                $avance->proyecto = (isset( $input['proyecto'] ) ? $input['proyecto'] : 'No Aplica');
                $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
                $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
                $avance->avance_finalizado = $fechaFin;
                $avance->fk_id_entregable= $id_entregable;
               
                $avance->m_t1 = (isset($input['m_t1']) ? $input['m_t1'] : 0 );
                $avance->m_d1 = (isset($input['m_d1']) ? $input['m_d1'] : 0);
                $avance->m_i1 = (isset($input['m_i1']) ? $input['m_i1'] : 0);
                $avance->h_t1 = (isset($input['h_t1']) ? $input['h_t1'] : 0);
                $avance->h_d1 = (isset($input['h_d1']) ? $input['h_d1'] : 0);
                $avance->h_i1 = (isset($input['h_i1']) ? $input['h_i1'] : 0);
                $avance->m_ts = (isset($input['m_ts']) ? $input['m_ts'] : 0);
                $avance->m_ds = (isset($input['m_ds']) ? $input['m_ds'] : 0);
                $avance->m_is = (isset($input['m_is']) ? $input['m_is'] : 0);
                $avance->h_ts = (isset($input['h_ts']) ? $input['h_ts'] : 0);
                $avance->h_ds = (isset($input['h_ds']) ? $input['h_ds'] : 0);
                $avance->h_is = (isset($input['h_is']) ? $input['h_is'] : 0);
                //GEPEA
                $avance->m_10 = (isset($input['m_10']) ? $input['m_10'] : 0);//mujeres entre 10-14 años primera vez GPA
                $avance->h_10 = (isset($input['h_10']) ? $input['h_10'] : 0);//hombres entre 10-14 años primera vez GPA
                $avance->m_15= (isset($input['m_15']) ? $input['m_15'] : 0);//mujeres entre 15-19 años primera vez GPA
                $avance->h_15= (isset($input['h_15']) ? $input['h_15'] : 0);//hombres entre 15-19 años primera vez GPA
                $avance->m_ser= (isset($input['m_ser']) ? $input['m_ser'] : 0);//mujeres servidores primera vez GPA
                $avance->h_ser= (isset($input['h_ser']) ? $input['h_ser'] : 0);//hombres servidores primera vez GPA
                $avance->m_padres= (isset($input['m_padres']) ? $input['m_padres'] : 0);//mujeres padres primera vez GPA
                $avance->h_padres= (isset($input['h_padres']) ? $input['h_padres'] : 0); //hombres padres primera vez GPA
                $avance->ms_10= (isset($input['ms_10']) ? $input['ms_10'] : 0);//mujeres entre 10-14 años seguimiento GPA
                $avance->hs_10= (isset($input['hs_10']) ? $input['hs_10'] : 0);//hombres entre 10-14 años seguimiento GPA
                $avance->ms_15= (isset($input['ms_15']) ? $input['ms_15'] : 0);//mujeres entre 15-19 años segumiento GPA
                $avance->hs_15= (isset($input['hs_15']) ? $input['hs_15'] : 0); //mujeres entre 15-10 años seguimiento GPA
                $avance->ms_ser= (isset($input['ms_ser']) ? $input['ms_ser'] : 0);//mujeres servidorees segumiento GPA
                $avance->hs_ser= (isset($input['hs_ser']) ? $input['hs_ser'] : 0);//hombres servidores segumiento GPA
                $avance->ms_padres= (isset($input['ms_padres']) ? $input['ms_padres'] : 0);//mujeres padres seguimiento GPA
                $avance->hs_padres= (isset($input['hs_padres']) ? $input['hs_padres'] : 0);//hombres padres seguimiento GPA 
                
        
                $avance->fk_id_entregable= $id_entregable;
                $avance->save();
        
                $host  = $_SERVER['HTTP_HOST'];
                $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                return $response->withHeader('Location',"http://$host$uri/avance/$id_entregable")->withStatus(302);
                }catch(\PDOException $e){
                    $this->logger->error($e->getMessage());
                }
                }
        }else{
                try{
                $avance= Avance::find($id_avance);
               
                $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
                $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
                $avance->avance_entregable = ((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
                $avance->monto = (isset($input['monto']) ? $input['monto'] : 'No aplica');
                $avance->proyecto = (isset( $input['proyecto'] ) ? $input['proyecto'] : 'No Aplica');
                $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica') ;
                $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica') ;
                $avance->fk_id_entregable= $id_entregable;
               
                $avance->m_t1 = (isset($input['m_t1']) ? $input['m_t1'] : 0 );
                $avance->m_d1 = (isset($input['m_d1']) ? $input['m_d1'] : 0);
                $avance->m_i1 = (isset($input['m_i1']) ? $input['m_i1'] : 0);
                $avance->h_t1 = (isset($input['h_t1']) ? $input['h_t1'] : 0);
                $avance->h_d1 = (isset($input['h_d1']) ? $input['h_d1'] : 0);
                $avance->h_i1 = (isset($input['h_i1']) ? $input['h_i1'] : 0);
                $avance->m_ts = (isset($input['m_ts']) ? $input['m_ts'] : 0);
                $avance->m_ds = (isset($input['m_ds']) ? $input['m_ds'] : 0);
                $avance->m_is = (isset($input['m_is']) ? $input['m_is'] : 0);
                $avance->h_ts = (isset($input['h_ts']) ? $input['h_ts'] : 0);
                $avance->h_ds = (isset($input['h_ds']) ? $input['h_ds'] : 0);
                $avance->h_is = (isset($input['h_is']) ? $input['h_is'] : 0);
                //GEPEA
                $avance->m_10 = (isset($input['m_10']) ? $input['m_10'] : 0);//mujeres entre 10-14 años primera vez GPA
                $avance->h_10 = (isset($input['h_10']) ? $input['h_10'] : 0);//hombres entre 10-14 años primera vez GPA
                $avance->m_15= (isset($input['m_15']) ? $input['m_15'] : 0);//mujeres entre 15-19 años primera vez GPA
                $avance->h_15= (isset($input['h_15']) ? $input['h_15'] : 0);//hombres entre 15-19 años primera vez GPA
                $avance->m_ser= (isset($input['m_ser']) ? $input['m_ser'] : 0);//mujeres servidores primera vez GPA
                $avance->h_ser= (isset($input['h_ser']) ? $input['h_ser'] : 0);//hombres servidores primera vez GPA
                $avance->m_padres= (isset($input['m_padres']) ? $input['m_padres'] : 0);//mujeres padres primera vez GPA
                $avance->h_padres= (isset($input['h_padres']) ? $input['h_padres'] : 0); //hombres padres primera vez GPA
                $avance->ms_10= (isset($input['ms_10']) ? $input['ms_10'] : 0);//mujeres entre 10-14 años seguimiento GPA
                $avance->hs_10= (isset($input['hs_10']) ? $input['hs_10'] : 0);//hombres entre 10-14 años seguimiento GPA
                $avance->ms_15= (isset($input['ms_15']) ? $input['ms_15'] : 0);//mujeres entre 15-19 años segumiento GPA
                $avance->hs_15= (isset($input['hs_15']) ? $input['hs_15'] : 0); //mujeres entre 15-10 años seguimiento GPA
                $avance->ms_ser= (isset($input['ms_ser']) ? $input['ms_ser'] : 0);//mujeres servidorees segumiento GPA
                $avance->hs_ser= (isset($input['hs_ser']) ? $input['hs_ser'] : 0);//hombres servidores segumiento GPA
                $avance->ms_padres= (isset($input['ms_padres']) ? $input['ms_padres'] : 0);//mujeres padres seguimiento GPA
                $avance->hs_padres= (isset($input['hs_padres']) ? $input['hs_padres'] : 0);//hombres padres seguimiento GPA 
                
        
                $avance->fk_id_entregable= $id_entregable;
                $avance->save();
        
                $host  = $_SERVER['HTTP_HOST'];
                $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                return $response->withHeader('Location',"http://$host$uri/avance/$id_entregable")->withStatus(302);
                }catch(\PDOException $e){
                    $this->logger->error($e->getMessage());
                }
                }
        }else{

            try{
        $avance= Avance::find($id_avance);
       
        $avance->mes = (isset($input['mes']) ? $input['mes'] : 'No aplica') ;
        $avance->municipio = (isset($input['municipio']) ? $input['municipio'] : 'No aplica');
        $avance->avance_entregable = ((isset($input['avance_entregable']) && $input['avance_entregable'] != '') ? $input['avance_entregable'] : 0) ;
        $avance->monto = (isset($input['monto']) ? $input['monto'] : 'No aplica');
        $avance->proyecto = (isset( $input['proyecto'] ) ? $input['proyecto'] : 'No Aplica');
        $avance->descripcion = (isset($input['descripcion']) ? $input['descripcion'] : 'No aplica' );
        $avance->institucion = (isset($input['institucion']) ? $input['institucion'] : 'No aplica' );
        $avance->fk_id_entregable= $id_entregable;
       
        $avance->m_t1 = (isset($input['m_t1']) ? $input['m_t1'] : 0 );
        $avance->m_d1 = (isset($input['m_d1']) ? $input['m_d1'] : 0);
        $avance->m_i1 = (isset($input['m_i1']) ? $input['m_i1'] : 0);
        $avance->h_t1 = (isset($input['h_t1']) ? $input['h_t1'] : 0);
        $avance->h_d1 = (isset($input['h_d1']) ? $input['h_d1'] : 0);
        $avance->h_i1 = (isset($input['h_i1']) ? $input['h_i1'] : 0);
        $avance->m_ts = (isset($input['m_ts']) ? $input['m_ts'] : 0);
        $avance->m_ds = (isset($input['m_ds']) ? $input['m_ds'] : 0);
        $avance->m_is = (isset($input['m_is']) ? $input['m_is'] : 0);
        $avance->h_ts = (isset($input['h_ts']) ? $input['h_ts'] : 0);
        $avance->h_ds = (isset($input['h_ds']) ? $input['h_ds'] : 0);
        $avance->h_is = (isset($input['h_is']) ? $input['h_is'] : 0);
        //GEPEA
        $avance->m_10 = (isset($input['m_10']) ? $input['m_10'] : 0);//mujeres entre 10-14 años primera vez GPA
        $avance->h_10 = (isset($input['h_10']) ? $input['h_10'] : 0);//hombres entre 10-14 años primera vez GPA
        $avance->m_15= (isset($input['m_15']) ? $input['m_15'] : 0);//mujeres entre 15-19 años primera vez GPA
        $avance->h_15= (isset($input['h_15']) ? $input['h_15'] : 0);//hombres entre 15-19 años primera vez GPA
        $avance->m_ser= (isset($input['m_ser']) ? $input['m_ser'] : 0);//mujeres servidores primera vez GPA
        $avance->h_ser= (isset($input['h_ser']) ? $input['h_ser'] : 0);//hombres servidores primera vez GPA
        $avance->m_padres= (isset($input['m_padres']) ? $input['m_padres'] : 0);//mujeres padres primera vez GPA
        $avance->h_padres= (isset($input['h_padres']) ? $input['h_padres'] : 0); //hombres padres primera vez GPA
        $avance->ms_10= (isset($input['ms_10']) ? $input['ms_10'] : 0);//mujeres entre 10-14 años seguimiento GPA
        $avance->hs_10= (isset($input['hs_10']) ? $input['hs_10'] : 0);//hombres entre 10-14 años seguimiento GPA
        $avance->ms_15= (isset($input['ms_15']) ? $input['ms_15'] : 0);//mujeres entre 15-19 años segumiento GPA
        $avance->hs_15= (isset($input['hs_15']) ? $input['hs_15'] : 0); //mujeres entre 15-10 años seguimiento GPA
        $avance->ms_ser= (isset($input['ms_ser']) ? $input['ms_ser'] : 0);//mujeres servidorees segumiento GPA
        $avance->hs_ser= (isset($input['hs_ser']) ? $input['hs_ser'] : 0);//hombres servidores segumiento GPA
        $avance->ms_padres= (isset($input['ms_padres']) ? $input['ms_padres'] : 0);//mujeres padres seguimiento GPA
        $avance->hs_padres= (isset($input['hs_padres']) ? $input['hs_padres'] : 0);//hombres padres seguimiento GPA 
        

        $avance->fk_id_entregable= $id_entregable;
        $avance->save();

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        return $response->withHeader('Location',"http://$host$uri/avance/$id_entregable")->withStatus(302);
            }catch(\PDOException $e){
                $this->logger->error($e->getMessage());
            }
        }
    }else{
       
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $id = $args['id_entregable'];
        $id_avance = $args['id_avance'];
        $entregable = Entregable::All();
        
        $programa = Programa::All();

        $municipios = Municipios::All();

        $avance = Avance::All();

        $sumaavance= Avance::where('fk_id_entregable','=',$id)->sum('avance_entregable');
        $sumamonto = Avance::where('fk_id_entregable','=',$id)->sum('monto');
                
        
        $totaldiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+ Avance::where('fk_id_entregable','=',$id)->sum('h_d1')+ Avance::where('fk_id_entregable','=',$id)->sum('m_ds') + Avance::where('fk_id_entregable','=',$id)->sum('h_ds');
        $totalmaya= Avance::where('fk_id_entregable','=',$id)->sum('m_i1')+ Avance::where('fk_id_entregable','=',$id)->sum('h_i1')+ Avance::where('fk_id_entregable','=',$id)->sum('m_is')+ Avance::where('fk_id_entregable','=',$id)->sum('h_is');

        if($rol == 'Enlace Externo'){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
            $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Enlace SEMUJERES' ){
            $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
            $suma10 =0;
            $suma15=0;
            $sumaPadres= 0;
            $sumaServidores=0;
             $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
        }else if($rol == 'Administrador'){
            foreach($entregable as $e){
                   if($e['id_entregable'] == $id){
                    foreach($programa as $p){
                        if($p['id_programa'] == $e['fk_id_programa']){
                            if($p['rol_usuario'] == 'Enlace GEPEA'){
                                $mBeneficiados= 0;
                                $hBeneficiados = 0;
                                $mDiscapacidad = 0;
                                $hDiscapacidad = 0;
                                $mMaya = 0;
                                $hMaya = 0;
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                               
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace Externo' || $p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             
                                $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                                $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                                $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                                $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Administrador'){
                               $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                                $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                                $suma10 =0;
                                $suma15=0;
                                $sumaPadres= 0;
                                $sumaServidores=0;
                            }
                        }
                    }
                   }
            }
        
        }else if($rol == 'Admin SEMUJERES-GEPEA'){
            foreach($entregable as $e){
                if($e['id_entregable'] == $id){
                 foreach($programa as $p){
                     if($p['id_programa'] == $e['fk_id_programa']){
                         if($p['rol_usuario'] == 'Enlace GEPEA'){
                            $mBeneficiados= 0;
                            $hBeneficiados = 0;
                            $mDiscapacidad = 0;
                            $hDiscapacidad = 0;
                            $mMaya = 0;
                            $hMaya = 0;
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
                             $suma10 =Avance::where('fk_id_entregable','=',$id)->sum('m_10') +Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
                             $suma15=Avance::where('fk_id_entregable','=',$id)->sum('m_15') +Avance::where('fk_id_entregable','=',$id)->sum('ms_15')+Avance::where('fk_id_entregable','=',$id)->sum('h_15')+Avance::where('fk_id_entregable','=',$id)->sum('hs_15');
                             $sumaPadres= Avance::where('fk_id_entregable','=',$id)->sum('m_padres') +Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
                             $sumaServidores=  Avance::where('fk_id_entregable','=',$id)->sum('m_ser') +Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
                            }else if($p['rol_usuario'] == 'Enlace SEMUJERES'){
                                 $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);    
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }else if($p['rol_usuario'] == 'Admin SEMUJERES-GEPEA'){
                                $mBeneficiados= Avance::where('fk_id_entregable','=',$id)->sum('m_t1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ts');
                                $hBeneficiados = Avance::where('fk_id_entregable','=',$id)->sum('h_t1')+Avance::where('fk_id_entregable','=',$id)->sum('h_ts');
                                $mDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $hDiscapacidad = Avance::where('fk_id_entregable','=',$id)->sum('m_d1')+Avance::where('fk_id_entregable','=',$id)->sum('m_ds');
                                $mMaya = Avance::where('fk_id_entregable','=',$id)->sum('m_i1') + Avance::where('fk_id_entregable','=',$id)->sum('m_is');
                                $hMaya = Avance::where('fk_id_entregable','=',$id)->sum('h_i1') + Avance::where('fk_id_entregable','=',$id)->sum('h_is');
                             $mensual = Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','monto','m_t1','m_ts','h_t1','h_ts','m_d1','m_ds','h_d1','h_ds','m_i1','m_is','h_i1','h_is']);
                             $suma10 =0;
                             $suma15=0;
                             $sumaPadres= 0;
                             $sumaServidores=0;
                            }
                     }
                 }
                }
         }

        }
        else if($rol == 'Enlace GEPEA'){
            $mensual=Avance::where('fk_id_entregable','=',$id)->get(['mes','avance_entregable','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres']);
            $suma10=Avance::where('fk_id_entregable','=',$id)->sum('m_10')+Avance::where('fk_id_entregable','=',$id)->sum('h_10')+Avance::where('fk_id_entregable','=',$id)->sum('ms_10')+Avance::where('fk_id_entregable','=',$id)->sum('hs_10');
            $suma15=(Avance::where('fk_id_entregable','=',$id)->sum('m_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('h_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('ms_15'))+(Avance::where('fk_id_entregable','=',$id)->sum('hs_15'));
            $sumaPadres=Avance::where('fk_id_entregable','=',$id)->sum('m_padres')+Avance::where('fk_id_entregable','=',$id)->sum('ms_padres')+Avance::where('fk_id_entregable','=',$id)->sum('h_padres')+Avance::where('fk_id_entregable','=',$id)->sum('hs_padres');
            $sumaServidores=Avance::where('fk_id_entregable','=',$id)->sum('m_ser')+Avance::where('fk_id_entregable','=',$id)->sum('ms_ser')+Avance::where('fk_id_entregable','=',$id)->sum('h_ser')+Avance::where('fk_id_entregable','=',$id)->sum('hs_ser');
            $mBeneficiados= 0;
            $hBeneficiados = 0;
            $mDiscapacidad = 0;
            $hDiscapacidad = 0;
            $mMaya = 0;
            $hMaya = 0;
        }

        $msg = 'No se ha guardado el avance, llena todos los campos e intenta guardar nuevamente';
        $clase = 'red';

    return $this->container->get('view')->render($response, 'avance.html',['message'=>$msg,'class'=>$clase,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,'id_avance'=>$id_avance,
        'rol'=>$rol,'avance'=>$avance, 'entregable'=>$entregable, 'programa'=>$programa,'identregable'=>$id, 'municipios'=>$municipios, 'monto'=>$sumamonto, 'sumaavance'=>$sumaavance,
    'totaldiscapacidad'=>$totaldiscapacidad, 'totalmaya'=>$totalmaya,
    'mensual'=>$mensual,'sum10'=>$suma10,'sum15'=>$suma15,'sumPadres'=>$sumaPadres,'sumServidores'=>$sumaServidores, 'mBeneficiados'=>$mBeneficiados,'hBeneficiados'=>$hBeneficiados,'mDiscapacidad'=>$mDiscapacidad,'hDiscapacidad'=>$hDiscapacidad,'mMaya'=>$mMaya,'hMaya'=>$hMaya]);
    }
    }
    }

    public function eliminarAvance(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
    $id_entregable = $args['id_entregable'];
    $id_avance = $args['id_avance'];
    
    $avance = Avance::find($id_avance);
    $avance->delete();
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  
    return $response->withHeader('Location',"http://$host$uri/avance/$id_entregable")->withStatus(302);
    }
}
}
