<?php
declare(strict_types=1);

namespace App\Application\Controladores;

use App\Application\Modelos\Avance;
use App\Application\Modelos\Entregable;
use App\Application\Modelos\ENTREGABLES\ProgramaEspecial;
use App\Application\Modelos\ENTREGABLES\Finanzas;
use App\Application\Modelos\PMP\EstrategiaPMP;
use App\Application\Modelos\PMP\LineaAccionPMP;
use App\Application\Modelos\PMP\ObjetivoPMP;
use App\Application\Modelos\PMP\Pmp;
use App\Application\Modelos\Programa;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Rules\Unique;

class mapaController{

    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
      
    }

    public function mostrarMapa(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    { 
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $id_user = $usuario[0]->usuario_id;
    
        $pEspecial = Pmp::all();
        $linea = LineaAccionPMP::all();
        $pmpEspecial = ProgramaEspecial::all();
     
        if($rol == 'Administrador'){
            $programa = Programa::get(['id_programa','nombre_programa']);
           
        }else if($rol== 'Admin SEMUJERES-GEPEA'){
            $programa = Programa::where('rol_usuario','=','Admin SEMUJERES-GEPEA')->orWhere('rol_usuario','=', 'Enlace GEPEA')->orWhere('rol_usuario','=','Enlace SEMUJERES')->get(['id_programa','nombre_programa']);
           
        }else if($rol == 'Enlace Externo'){
            $programa = Programa::where('rol_usuario','=','Enlace Externo')->where('fk_user','=',$id_user)->get(['id_programa','nombre_programa']);
        }else if($rol == 'Enlace SEMUJERES'){
            $programa = Programa::where('rol_usuario','=','Enlace SEMUJERES')->where('fk_user','=',$id_user)->get(['id_programa','nombre_programa']);
        }else if($rol == 'Enlace GEPEA'){
            $programa = Programa::where('rol_usuario','=','Enlace GEPEA')->where('fk_user','=',$id_user)->get(['id_programa','nombre_programa']);
        }     
        $entregable = Entregable::get(['id_entregable','nombre_entregable','fk_id_programa']);
     
        return $this->container->get('view')->render($response, 'mapa.html',['nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,
        'rol'=>$rol, 'pespecial'=>$pEspecial, 'linea'=>$linea, 'programa'=>$programa,'entregable'=>$entregable,'pmpEspecial'=>$pmpEspecial]);
    }
    }

    public function buscar(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    { 
        if(session_status() == PHP_SESSION_ACTIVE){
        $input = $request->getParsedBody();

        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $id_user = $usuario[0]->usuario_id;
        
        $pEspecial = Pmp::all();
        $linea = LineaAccionPMP::all();
        $pmpEspecial = ProgramaEspecial::all();
    
        if($rol == 'Administrador'){
            $programa = Programa::get(['id_programa','nombre_programa']);
           
        }else if($rol== 'Admin SEMUJERES-GEPEA'){
            $programa = Programa::where('rol_usuario','=','Admin SEMUJERES-GEPEA')->orWhere('rol_usuario','=', 'Enlace GEPEA')->orWhere('rol_usuario','=','Enlace SEMUJERES')->get(['id_programa','nombre_programa']);
           
        }else if($rol == 'Enlace Externo'){
            $programa = Programa::where('rol_usuario','=','Enlace Externo')->where('fk_user','=',$id_user)->get(['id_programa','nombre_programa']);
        }else if($rol == 'Enlace SEMUJERES'){
            $programa = Programa::where('rol_usuario','=','Enlace SEMUJERES')->where('fk_user','=',$id_user)->get(['id_programa','nombre_programa']);
        }else if($rol == 'Enlace GEPEA'){
            $programa = Programa::where('rol_usuario','=','Enlace GEPEA')->where('fk_user','=',$id_user)->get(['id_programa','nombre_programa']);
        } 
        $entregable = Entregable::all();

        
        $pe_programa = $input['p-especial'];
        $pe_linea = $input['linea-accion'];
        $p_programa = $input['programa'];
        $pe_entregable = $input['entregable'];

        $l= array();
    
        $resultado=array();

        $mTotal=0;
        $hTotal=0;
        $monto =0;
        $avanceTotal=0;
      if($pe_entregable !="0" && $pe_programa !="0" && $p_programa !="0" && $pe_linea != "0"){

          $b_programa = Programa::where('nombre_programa','=',$p_programa)->get();
         // $pmp = Pmp::where('id_pmp','=',$pe_programa)->get('tema');
         // $linea = LineaAccionPMP::where('id_linea_pmp','=',$pe_linea)->get('linea_pmp');
         // $b_proEspecial = ProgramaEspecial::where('programa','=',$pmp[0]->tema)->where('linea_accion','=',$linea[0]->linea_pmp)->get();
         $b_proEspecial = ProgramaEspecial::where('programa','=',$pe_programa)->where('linea_accion','=',$pe_linea)->get();
       
          $b_entregable =  Entregable::where('nombre_entregable','=',$pe_entregable)->get();
          $b_avance= Avance::all();
            $municipio = array();
            foreach($b_programa as $p){
                foreach($b_entregable as $e){
                      
                   if($p['id_programa'] == $e['fk_id_programa']){
                      if($b_proEspecial != ''){
                          foreach($b_proEspecial as $bpro){
                       if( $bpro['fk_id_entregable'] ==  $e['id_entregable'])                
                         foreach($b_avance as $v){
                             if($v['fk_id_entregable'] == $e['id_entregable'] ){    
                           
                                array_push($resultado, ["municipio"=>$v['municipio'], 'entregable'=>$v['fk_id_entregable']]);
                              array_push($municipio,["municipio"=>$v['municipio']]);
                                  }     
                            }
                        }
                        }else{
                              $resultado=[];
                                }
                            }
                       }  
                     }

                     $res= array_unique($resultado,SORT_REGULAR);
                     $resul = array_unique($municipio,SORT_REGULAR);
                     
                     foreach($resul  as $mun){
                        $mujeres =0;
                        $hombres=0;
                        $presupuesto=0;
                        $avance=0;
                     foreach($res as $r){
                     
                        if($mun['municipio'] == $r['municipio']){
                     $mujeres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_t1','m_ts','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres');
                     $hombres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('h_t1','h_ts','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres');
                     $presupuesto = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('monto');
                     $avance = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('avance_entregable');
                     $mTotal =$mTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_ts');
                     $hTotal =$hTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_ts');
                     $monto = $monto + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('monto');
                     $avanceTotal = $avanceTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('avance_entregable');
                     }
                   
                    }
                    $l[] = ['municipio'=>$mun['municipio'], 'mujeres'=>$mujeres,'hombres'=>$hombres,'presupuesto'=>$presupuesto,'avance'=>$avance];
                     }              
        }elseif($pe_entregable =="0" && $pe_programa !="0" && $p_programa =="0" && $pe_linea =="0"){
            $b_programa = Programa::All();
            $b_entregable =  Entregable::all();
          //  $pmp = Pmp::where('id_pmp','=',$pe_programa)->get('tema');
            $b_proEspecial = ProgramaEspecial::where('programa','=',$pe_programa)->get(['fk_id_entregable']);
            $b_avance= Avance::all();

              $municipio = array();
            foreach($b_avance as $v){
                foreach($b_entregable as $e){    
                      if($b_proEspecial != ''){
                             if($e['id_entregable'] == $v['fk_id_entregable']){  
                                foreach($b_proEspecial as $bpro){
                                    if( $bpro['fk_id_entregable'] ==  $e['id_entregable']){     
                             array_push( $resultado,["municipio"=>$v['municipio'],'entregable'=>$bpro['fk_id_entregable']]);
                            array_push($municipio,["municipio"=>$v['municipio']]);
                                  }     
                            }
                        }
                            }else{
                                $resultado=[];
                            }
                       }  
                     
                    }
                

                     $res= array_unique($resultado,SORT_REGULAR);
                   $resul = array_unique($municipio,SORT_REGULAR);
                   
                 
                   foreach($resul  as $mun){
                    $mujeres =0;
                    $hombres=0;
                    $presupuesto=0;
                    $avance=0;
                     foreach($res as $r){
                       
                        if($mun['municipio'] == $r['municipio']){
                     $mujeres = $mujeres+ Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_t1')+Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_ts');
                     $hombres = $hombres+ Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('h_t1')+Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('h_ts');
                     $presupuesto =$presupuesto+ Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('monto');
                     $avance = $avance +Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('avance_entregable');
                     $mTotal =$mTotal + (Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_ts'));
                     $hTotal =$hTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_ts');
                     $monto = $monto + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('monto');
                     $avanceTotal = $avanceTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('avance_entregable');    
                    }
                       
                    }
                    $l[] = ['municipio'=>$mun['municipio'], 'mujeres'=>$mujeres,'hombres'=>$hombres,'presupuesto'=>$presupuesto,'avance'=>$avance];

                   
                }

                


        }elseif($pe_entregable =="0" && $pe_programa =="0" && $p_programa !="0"&& $pe_linea == "0"){     

            $b_programa = Programa::where('nombre_programa','=',$p_programa)->get();
            $b_entregable =  Entregable::All();
            $b_avance= Avance::all();

            $municipio = array();
        
            if( $b_programa != ''){
            foreach($b_programa as $p){
                foreach($b_entregable as $e){     
                   if($p['id_programa'] == $e['fk_id_programa']){
                         foreach($b_avance as $v){
                             if($e['id_entregable'] == $v['fk_id_entregable']){    
                           
                              array_push($resultado, ["municipio"=>$v['municipio'], 'entregable'=>$v['fk_id_entregable']]);
                              array_push($municipio,["municipio"=>$v['municipio']]);
                              
                                  }     
                            }
                       
                            }
                       }  
                     }
                    }else{
                        $resultado=[];
                    }
                   $res= array_unique($resultado,SORT_REGULAR);
                    $resul = array_unique($municipio,SORT_REGULAR);

             
                   foreach($resul  as $mun){
                    $mujeres =0;
                    $hombres=0;
                    $presupuesto=0;
                    $avance=0;
   
                    foreach($res as $r){
                      
                        if($mun['municipio'] == $r['municipio']){
                    $mujeres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_t1') + Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_ts');
                    $hombres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('h_t1') + Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('h_ts');
                    $presupuesto = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('monto');
                    $avance = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('avance_entregable');
                    $mTotal =$mTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_t1') + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_ts');
                    $hTotal =$hTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_t1') + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_ts');
                    $monto = $monto + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('monto');
                    $avanceTotal = $avanceTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('avance_entregable');
                    }
                   
                        }
                    $l[] = ['municipio'=>$mun['municipio'], 'mujeres'=>$mujeres,'hombres'=>$hombres,'presupuesto'=>$presupuesto,'avance'=>$avance];
                }

        }elseif($pe_entregable !="0" && $pe_programa == "0" && $p_programa == "0" && $pe_linea == "0"){
          //  $b_programa = Programa::All();
            $b_entregable = Entregable::where('nombre_entregable','=',$pe_entregable)->get();
            /*$response->getBody()->write("$b_entregable");
            return $response;*/
            $b_avance= Avance::all();
            $municipio = array();
            if( $b_entregable != ''){
                foreach($b_entregable as $e){     
                         foreach($b_avance as $v){
                             if($e['id_entregable'] == $v['fk_id_entregable']){       
                                array_push($resultado, ["municipio"=>$v['municipio'], 'entregable'=>$v['fk_id_entregable']]);
                              array_push($municipio,["municipio"=>$v['municipio']]);
                                  }     
                            }         
                       }     
                    }else{
                        $resultado=[];
                    }

                    $res= array_unique($resultado,SORT_REGULAR);
                    $resul = array_unique($municipio,SORT_REGULAR);
                
                    foreach($resul  as $mun){
                        $mujeres =0;
                        $hombres=0;
                        $presupuesto=0;
                        $avance=0;    
   
       foreach($res as $r){
     
        if($mun['municipio'] == $r['municipio']){
       $mujeres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_t1','m_ts','m_10','m_15','m_ser','m_padres','ms_10','ms_15','ms_ser','ms_padres');
       $hombres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('h_t1','h_ts','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres');
       $presupuesto = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('monto');
       $avance = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('avance_entregable');
       $mTotal =$mTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_ts');
       $hTotal =$hTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_ts');
       $monto = $monto + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('monto');
       $avanceTotal = $avanceTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('avance_entregable');
            }
            
        }
        $l[] = ['municipio'=>$mun['municipio'], 'mujeres'=>$mujeres,'hombres'=>$hombres,'presupuesto'=>$presupuesto,'avance'=>$avance];
            }

            
           
        }elseif($pe_entregable =="0" && $pe_programa =="0" && $p_programa =="0" && $pe_linea != "0"){
           
          
            
         $b_avance= Avance::all();
           $b_programa = Programa::All();
            $b_entregable =  Entregable::all();
            $b_avance= Avance::all();
           // $linea = LineaAccionPMP::where('id_linea_pmp','=',$pe_linea)->get('linea_pmp');
          $b_proEspecial = ProgramaEspecial::where('linea_accion','=',$pe_linea)->get();
        $municipio = array();
             foreach($b_programa as $p){
                foreach($b_entregable as $e){
                      
                   if($p['id_programa'] == $e['fk_id_programa']){
                   
                    if($b_proEspecial != ''){   
                               
                        foreach($b_proEspecial as $pro){
                         foreach($b_avance as $v){
                             if($pro['fk_id_entregable'] == $v['fk_id_entregable']){         
                                array_push($resultado, ["municipio"=>$v['municipio'],"entregable"=>$v['fk_id_entregable']]);
                                     array_push($municipio,["municipio"=>$v['municipio']]);
                                     
                                  }     
                            }
                        }
                        
                            }else{
                                $resultado=[];
                                  }
                       }  
                   }
          
       }
      $res= array_unique($resultado,SORT_REGULAR);
      $resul = array_unique($municipio,SORT_REGULAR);
      $mTotal =0;

   
      foreach($resul  as $mun){
        $mujeres =0;
        $hombres=0;
        $presupuesto=0;
        $avance=0;    
       
   
       foreach($res as $r){
        
        if($mun['municipio'] == $r['municipio']){
       $mujeres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_t1')+Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('m_ts');
       $hombres = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('h_t1','h_ts','h_10','h_15','h_ser','h_padres','hs_10','hs_15','hs_ser','hs_padres');
       $presupuesto = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('monto');
       $avance = Avance::where('municipio','=',$r['municipio'])->where('fk_id_entregable','=',$r['entregable'])->sum('avance_entregable');
       $mTotal =$mTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('m_ts');
       $hTotal =$hTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_t1')+Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('h_ts');
       $monto = $monto + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('monto');
       $avanceTotal = $avanceTotal + Avance::where('fk_id_entregable','=',$r['entregable'])->where('municipio','=',$r['municipio'])->sum('avance_entregable');
       }

      
    }
  
    $l[] = ['municipio'=>$mun['municipio'], 'mujeres'=>$mujeres,'hombres'=>$hombres,'presupuesto'=>$presupuesto,'avance'=>$avance];
  
    }

   
              
} 

        $json = json_decode(json_encode($l), FALSE);

        return $this->container->get('view')->render($response, 'mapa.html',['resultado'=>$json,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,
        'rol'=>$rol, 'pespecial'=>$pEspecial, 'linea'=>$linea, 'pmpEspecial'=>$pmpEspecial,'programa'=>$programa,'entregable'=>$entregable,'mTotal'=>$mTotal,'hTotal'=>$hTotal,'monto'=>$monto,'avanceTotal'=>$avanceTotal]);
       
    }
}
}