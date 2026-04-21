<?php
declare(strict_types=1);

namespace App\Application\Controladores;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

use App\Application\Modelos\Programa;
use App\Application\Modelos\Entregable;
use App\Application\Modelos\Avance;
use App\Application\Modelos\ENTREGABLES\Finanzas;
use App\Application\Modelos\ENTREGABLES\OdsEntregable;
use App\Application\Modelos\ENTREGABLES\ProgramaEspecial;
use App\Application\Modelos\Usuario;
use App\Application\Modelos\INDICADORES\Indicador;

use App\Application\Modelos\Indicadores;
use App\Application\Modelos\INDICADORES\Pp;
use App\Application\Modelos\INDICADORES\Variables;
use App\Application\Modelos\PED\Alineacionped;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class baseProgramasController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarDescargable(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    { 
        if(session_status() == PHP_SESSION_ACTIVE){
    $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol = $usuario[0]->rol;
    $año = date("Y"); 
    $tipo = $args['id'];

    if($tipo == 'programas' || $tipo == 'indicadores'){
    return $this->container->get('view')->render($response, 'baseprogramas.html', ['año'=>$año,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario, 'tipo'=>$tipo]);
    }
}   
    }

   

    public function descargarBase(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){

        $tipo = $args['id'];
        $usuario = $_SESSION['user'];
       
        $rol = $usuario[0]->rol; 
        $id = $usuario[0]->usuario_id;
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $input = $request->getParsedBody();
        $año = $input['años'];   
        $mes = $input['mes'];
       $user = Usuario::all();
        if($tipo == 'programas'){
            if($rol == 'Administrador'){
            $programa = Programa::Where('año','=',$año)->get();
            }else if($rol == 'Enlace Externo' || $rol == 'Enlace SEMUJERES'){
                $programa = Programa::Where('año','=',$año)->where('rol_usuario','=',$rol)->where('fk_user','=',$id)->get();
            }else if($rol == 'Enlace GEPEA'){
                $programa = Programa::Where('año','=',$año)->where('rol_usuario','=',$rol)->where('fk_user','=',$id)->get();
            }else if($rol == 'Admin SEMUJERES-GEPEA'){
                $programa = Programa::Where('año','=',$año)->where('rol_usuario','=',$rol)->orWhere('rol_usuario','=','Enlace SEMUJERES')->orWhere('rol_usuario','=','Enlace GEPEA')->get();
            }

          if($programa == "[]"){

            $message = 'No se encontraron registros. Intente nuevamente.';
            $class = 'red';
            return $this->container->get('view')->render($response, 'baseprogramas.html', ['class'=>$class,'message'=> $message,'año'=>$año,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario, 'tipo'=>$tipo]);
          }else{
            
        $entregables = Entregable::ALL();
        $avance = Avance::all();
        $pespecial = ProgramaEspecial::all();
        $finanzas = Finanzas::all();
        $alineacionped = Alineacionped::all();
        
       
        $excel = new Spreadsheet();
      
        $sheet = $excel->setActiveSheetIndex(0);
 
        $sheet->setCellValue('A3', "Año");
      
        $sheet->setCellValue('B2', "Alineación al P.E.D.");
        $sheet->setCellValue('C2', '');
        $sheet->setCellValue('D2', '');
        $sheet->setCellValue('E2', '');
        $sheet->setCellValue('F2', '');
        $sheet->setCellValue('G2', '');
       
        $sheet->setCellValue('B3', "Eje");
        $sheet->setCellValue('C3', "Política\nPública");
        $sheet->setCellValue('D3', 'Objetivo');
        $sheet->setCellValue('E3', "Estrategia");
        $sheet->setCellValue('F3', "Línea\nde\nAcción");
        $sheet->setCellValue('G3', "Compromiso");

        $sheet->setCellValue('H2', "Alineación Programas Especiales");
        $sheet->setCellValue('I2', '');
        $sheet->setCellValue('J2', '');
        $sheet->setCellValue('H3', "Línea\nde\nAcción 1");
        $sheet->setCellValue('I3', "Línea\nde\nAcción 2");
        $sheet->setCellValue('J3', "Línea\nde\nAcción 3");

        $sheet->setCellValue('K2', "Otras Alineaciones");
        $sheet->setCellValue('L2', '');
        $sheet->setCellValue('M2', '');
        $sheet->setCellValue('N2', '');
        $sheet->setCellValue('K3', "ODS");
        $sheet->setCellValue('L3', "AVG");
        $sheet->setCellValue('M3', "Actividad\nEstratégica\nSIGO");
        $sheet->setCellValue('N3', "Entregable\nSIGO");

        $sheet->setCellValue('O3', "Programa/\nActividad");
        $sheet->setCellValue('P3', "Entregable");
        $sheet->setCellValue('Q3', "Unidad\nde\nMedida");
        $sheet->setCellValue('R3', "Meta");    

        $sheet->setCellValue('S3', 'Mes');
        $sheet->setCellValue('T3', "Municipio");
        $sheet->setCellValue('U3', "Avance");
        $sheet->setCellValue('V3', "Institución\n/\nVínculo");
        $sheet->setCellValue('W3' , "Tipo\nde\nPoblación");
        $sheet->setCellValue('X1' , "Población Primera Vez");
        $sheet->setCellValue('Y1' , '');
        $sheet->setCellValue('Z1' , '');
        $sheet->setCellValue('AA1' , '');
        $sheet->setCellValue('AB1' , '');
        $sheet->setCellValue('AC1' , '');
        $sheet->setCellValue('X2' , "Total");
        $sheet->setCellValue('Y2' , '');
        $sheet->setCellValue('Z2' , "Discapacidad");
        $sheet->setCellValue('AA2' , '');
        $sheet->setCellValue('AB2' , "Habla Indígena");
        $sheet->setCellValue('AC2' , '');
        $sheet->setCellValue('X3' , "M");
        $sheet->setCellValue('Y3' , "H");
        $sheet->setCellValue('Z3' , "M");
        $sheet->setCellValue('AA3' , "H");
        $sheet->setCellValue('AB3' , "M");
        $sheet->setCellValue('AC3' , "H");

        $sheet->setCellValue('AD1' ,"Población de Seguimiento");
        $sheet->setCellValue('AE1' ,'');
        $sheet->setCellValue('AF1' , '');
        $sheet->setCellValue('AG1' , '');
        $sheet->setCellValue('AH1' , '');
        $sheet->setCellValue('AI1' , '');
        $sheet->setCellValue('AD2' ,"Total");
        $sheet->setCellValue('AE2' ,'');
        $sheet->setCellValue('AF2' ,"Discapacidad");
        $sheet->setCellValue('AG2' , '');
        $sheet->setCellValue('AH2' ,"Habla Indígena");
        $sheet->setCellValue('AI2' , '');
        $sheet->setCellValue('AD3' , "M");
        $sheet->setCellValue('AE3' , "H");
        $sheet->setCellValue('AF3' , "M");
        $sheet->setCellValue('AG3' , "H");
        $sheet->setCellValue('AH3' , "M");
        $sheet->setCellValue('AI3' , "H");
      
        $sheet->setCellValue('AJ3', "Monto\nEjercido");
        $sheet->setCellValue('AK3', "Fuente\nde\nFinanciamiento");
        $sheet->setCellValue('AL3', "Proyecto\nFederal");
        $sheet->setCellValue('AM3', "Porcentaje que\nRepresenta del\nTotal\nAsginado\na la UBP");
        $sheet->setCellValue('AN3', "Descripción");
    
        $rows=4;
        
        if($mes == 'seleccione'){
        foreach($avance as $a){ 
            foreach($programa as $p){  
            foreach($entregables as $e){
            if( $p['rol_usuario'] != 'Enlace GEPEA'){ 
               
                    if($p['id_programa'] == $e['fk_id_programa']){
                       
                        if($e['id_entregable'] == $a['fk_id_entregable'] ){ 
                                      
                          
                        $sheet->setCellValue('A' . $rows, $p['año']);
                        $plus = 0;
                            foreach($alineacionped as $pe){
                                if($p['id_programa'] == $pe['fk_id_programa']){
                        $sheet->setCellValue('B' . ($rows + $plus), $pe['eje']);
                        $sheet->setCellValue('C' . ($rows + $plus), $pe['politica']);
                        $sheet->setCellValue('D' . ($rows + $plus), $pe['objetivo']);
                        $sheet->setCellValue('E' . ($rows + $plus), $pe['estrategia']);
                        $sheet->setCellValue('F' . ($rows + $plus), $pe['linea']);
                        $plus++;
                                }
                              
                            }
                        $sheet->setCellValue('G' . $rows, $e['compromiso']);
                            
                        $contador = 1;
                        
                        foreach($pespecial as $pe){
                            if($contador == 4){
                                $contador = 1;
                            }
                            if( $a['fk_id_entregable']==$pe['fk_id_entregable'] ){
                                
                                if($contador == 1){
                                    if($pe['programa'] == "Programa Especial para Prevención del Embarazo en Adolescentes"){
                                        $pro= "PEPEA";
                                    }else if($pe['programa'] == "Programa Especial para Igualdad de Género, Oportunidades y no Discriminación"){
                                        $pro= "PEIGOND";
                                    }else if($pe['programa'] == "Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres"){
                                        $pro="PEPASEVM";
                                    }
                        $sheet->setCellValue('H' . $rows, $pro.$pe['linea_accion']);
                                }else if($contador == 2){

                                    if($pe['programa'] == "Programa Especial para Prevención del Embarazo en Adolescentes"){
                                        $pro= "PEPEA";
                                    }else if($pe['programa'] == "Programa Especial para Igualdad de Género, Oportunidades y no Discriminación"){
                                        $pro= "PEIGOND";
                                    }else if($pe['programa'] == "Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres"){
                                        $pro="PEPASEVM";
                                    }
                        $sheet->setCellValue('I' . $rows, $pro.$pe['linea_accion']);
                                }else if($contador == 3){

                                    if($pe['programa'] == "Programa Especial para Prevención del Embarazo en Adolescentes"){
                                        $pro= "PEPEA";
                                    }else if($pe['programa'] == "Programa Especial para Igualdad de Género, Oportunidades y no Discriminación"){
                                        $pro= "PEIGOND";
                                    }else if($pe['programa'] == "Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres"){
                                        $pro="PEPASEVM";
                                    }
                        $sheet->setCellValue('J' . $rows,$pro.$pe['linea_accion']);
                                }
                            }
                            $contador++;
                         
                        }

                        $sheet->setCellValue('K' . $rows, $e['ods']);  
                        $sheet->setCellValue('L' . $rows, $e['avg']);
                        $sheet->setCellValue('M' . $rows, $e['actividad_sigo']);
                        $sheet->setCellValue('N' . $rows, $e['entregable_sigo']);

                        $sheet->setCellValue('O' . $rows, $p['nombre_programa']);
                        $sheet->setCellValue('P' . $rows, $e['nombre_entregable']);
                        $sheet->setCellValue('Q' . $rows, $e['unidad_medida']);
                        $sheet->setCellValue('R' . $rows, $e['meta']);
                        $sheet->setCellValue('S' . $rows, $a['mes']);
                        $sheet->setCellValue('T' . $rows, $a['municipio']);
                        $sheet->setCellValue('U' . $rows, $a['avance_entregable']);
                        $sheet->setCellValue('V' . $rows, $a['institucion']);
                        $sheet->setCellValue('W' . $rows, (isset($a['poblacion']) ? $a['poblacion'] : 'No aplica'));


                        $sheet->setCellValue('x' . $rows, $a['m_t1']);
                        $sheet->setCellValue('y' . $rows, $a['h_t1']);
                        $sheet->setCellValue('z' . $rows, $a['m_d1']);
                        $sheet->setCellValue('AA' . $rows, $a['h_d1']);
                        $sheet->setCellValue('AB' . $rows, $a['m_i1']);
                        $sheet->setCellValue('AC' . $rows, $a['h_i1']);

                        $sheet->setCellValue('AD' . $rows, $a['m_ts']);
                        $sheet->setCellValue('AE' . $rows, $a['h_ts']);
                        $sheet->setCellValue('AF' . $rows, $a['m_ds']);
                        $sheet->setCellValue('AG' . $rows, $a['h_ds']);
                        $sheet->setCellValue('AH' . $rows, $a['m_is']);
                        $sheet->setCellValue('AI' . $rows, $a['h_is']);       

                        foreach($finanzas as $f){
                            if($f['fk_id_entregable'] == $e['id_entregable']){
                        $sheet->setCellValue('AJ' . $rows, $f['monto']);
                        $sheet->setCellValue('AK' . $rows, $f['fuente']);
                            }
                        }
                      
                       
                        $sheet->setCellValue('AL' . $rows, $a['proyecto']); 
                        $sheet->setCellValue('AM' . $rows, $e['porcentaje_ubp_total']);
                        $sheet->setCellValue('AN' . $rows, $a['descripcion']);
                        $rows = ($rows+ $plus); 
                    }
                    
                               
                            
                  }   
                
                 }
                
                } 
                
            }
            
        }
    }
         elseif($mes != 'seleccione'){
            foreach($avance as $a){
             
            foreach($entregables as $e){
                if($mes == $a['mes'] && $e['id_entregable'] == $a['fk_id_entregable'] ){ 
                foreach($programa as $p){  
                    if( $p['rol_usuario'] != 'Enlace GEPEA'){ 
               if($p['id_programa'] == $e['fk_id_programa']){
                $sheet->setCellValue('A' . $rows, $p['año']);
                        $sheet->setCellValue('B' . $rows, $p['ejeped']);
                        $sheet->setCellValue('C' . $rows, $p['politicaped']);
                        $sheet->setCellValue('D' . $rows, $p['objetivoped']);
                        $sheet->setCellValue('E' . $rows, $p['estrategiaped']);
                        $sheet->setCellValue('F' . $rows, $p['lineaped']);
                        $sheet->setCellValue('G' . $rows, $e['compromiso']);
                    
                        foreach($pespecial as $pe){
                            if( $a['fk_id_entregable']==$pe['fk_id_entregable'] ){
                        $sheet->setCellValue('H' . $rows, $pe['linea_accion']);
                        $sheet->setCellValue('I' . $rows, $pe['linea_accion']);
                        $sheet->setCellValue('J' . $rows, $pe['linea_accion']);
                       
                            }
                         
                        }

                        $sheet->setCellValue('K' . $rows, $e['ods']);  
                        $sheet->setCellValue('L' . $rows, $e['avg']);
                        $sheet->setCellValue('M' . $rows, $e['actividad_sigo']);
                        $sheet->setCellValue('N' . $rows, $e['entregable_sigo']);

                        $sheet->setCellValue('O' . $rows, $p['nombre_programa']);
                        $sheet->setCellValue('P' . $rows, $e['nombre_entregable']);
                        $sheet->setCellValue('Q' . $rows, $e['unidad_medida']);
                        $sheet->setCellValue('R' . $rows, $e['meta']);
                        $sheet->setCellValue('S' . $rows, $a['mes']);
                        $sheet->setCellValue('T' . $rows, $a['municipio']);
                        $sheet->setCellValue('U' . $rows, $a['avance_entregable']);
                        $sheet->setCellValue('V' . $rows, $a['institucion']);
                        $sheet->setCellValue('W' . $rows, (isset($a['poblacion']) ? $a['poblacion'] : 'No aplica'));

                        $sheet->setCellValue('X' . $rows, $a['m_t1']);
                        $sheet->setCellValue('Y' . $rows, $a['h_t1']);
                        $sheet->setCellValue('Z' . $rows, $a['m_d1']);
                        $sheet->setCellValue('AA' . $rows, $a['h_d1']);
                        $sheet->setCellValue('AB' . $rows, $a['m_i1']);
                        $sheet->setCellValue('AC' . $rows, $a['h_i1']);

                        $sheet->setCellValue('AD' . $rows, $a['m_ts']);
                        $sheet->setCellValue('AE' . $rows, $a['h_ts']);
                        $sheet->setCellValue('AF' . $rows, $a['m_ds']);
                        $sheet->setCellValue('AG' . $rows, $a['h_ds']);
                        $sheet->setCellValue('AH' . $rows, $a['m_is']);
                        $sheet->setCellValue('AI' . $rows, $a['h_is']);       


                      
                       
                        foreach($finanzas as $f){
                            if($f['fk_id_entregable'] == $e['id_entregable']){
                        $sheet->setCellValue('AJ' . $rows, $f['monto']);
                        $sheet->setCellValue('AK' . $rows, $f['fuente']);
                            }
                        }
                      
                       
                        $sheet->setCellValue('AL' . $rows, $a['proyecto']); 
                        $sheet->setCellValue('AM' . $rows, $e['porcentaje_ubp_total']);
                        $sheet->setCellValue('AN' . $rows, $a['descripcion']);
                        $rows++; 
                                     
                    }
                    }
                   
                }
            }         
            }        
        }    
        }   
        
        

    $styleArray = [
        'font' => [
            'bold' => true,
            'size'=>10,
            'color'=> [
               'argb'=> Color::COLOR_WHITE,]
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER_CONTINUOUS,
            'wrapText'=> true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'=>[
                   'argb'=> Color::COLOR_BLACK,]
            ],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'rotation' => 90,
            'startColor' => [
                'argb' => '006E368C',
            ]
        ],
    ];

    //ALINEACION AL PED
    $excel->getActiveSheet()->getStyle('B2:G2')->applyFromArray($styleArray);
    //PROGRAMA ESPECIAL
    $excel->getActiveSheet()->getStyle('H2:J2')->applyFromArray($styleArray);
    //OTRAS ALINEACIONES
    $excel->getActiveSheet()->getStyle('K2:N2')->applyFromArray($styleArray);
    //POBLACION PRIMERA VEZ
    $excel->getActiveSheet()->getStyle('X1:AC1')->applyFromArray($styleArray);
    //Poblacion seguimiento
    $excel->getActiveSheet()->getStyle('AD1:AI1')->applyFromArray($styleArray);

    $excel->getActiveSheet()->getStyle('A3:AN3')->applyFromArray($styleArray);
    $excel->getActiveSheet()->getStyle('X2:AI2')->applyFromArray($styleArray);
   
    $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('M')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('N')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('P')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('V')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
    $excel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(false);
    $excel->getActiveSheet()->getColumnDimension('AM')->setWidth(13);
   

     // Merge cells
     $excel->getActiveSheet()->mergeCells('B2:G2');
     $excel->getActiveSheet()->mergeCells('H2:J2');
     $excel->getActiveSheet()->mergeCells('K2:N2');
     $excel->getActiveSheet()->mergeCells('X1:AC1');
     $excel->getActiveSheet()->mergeCells('AD1:AI1');

     $excel->getActiveSheet()->mergeCells('X2:Y2');
     $excel->getActiveSheet()->mergeCells('Z2:AA2');
     $excel->getActiveSheet()->mergeCells('AB2:AC2');
      $excel->getActiveSheet()->mergeCells('AD2:AE2');
      $excel->getActiveSheet()->mergeCells('AF2:AG2');
      $excel->getActiveSheet()->mergeCells('AH2:AI2');
     

  
     $excel->getActiveSheet()->setTitle('Base de datos');
    $excel->setActiveSheetIndex(0);
   
        $excel->createSheet();

            // Add some data
        $excel->setActiveSheetIndex(1);
        $sheet = $excel->setActiveSheetIndex(1);
        $sheet->setCellValue('A3', "PROGRAMA\nACCION");
        $sheet->setCellValue('B3', "ENTREGABLE");
        $sheet->setCellValue('C3', "MES");
        $sheet->setCellValue('D3', "MUNICIPIO");
        $sheet->setCellValue('E3', "AVANCE\nDEL\nENTREGABLE");
        $sheet->setCellValue('F3', "¿LA ACTIVIDAD\nSE REALIZÓ CON\nOTRA INSTITUCIÓN?\nNOMBRE DE\nLA INSTITUCIÓN");
        $sheet->setCellValue('G3', "DESCRIPCION");
        $sheet->setCellValue('H1', "POBLACION PRIMERA VEZ");
        $sheet->setCellValue('I1', "");
        $sheet->setCellValue('J1', "");
        $sheet->setCellValue('K1', "");
        $sheet->setCellValue('L1', "");
        $sheet->setCellValue('M1', "");
        $sheet->setCellValue('N1', "");
        $sheet->setCellValue('O1', "");
        $sheet->setCellValue('P1', "POBLACION DE SEGUIMIENTO");
        $sheet->setCellValue('Q1', "");
        $sheet->setCellValue('R1', "");
        $sheet->setCellValue('S1', "");
        $sheet->setCellValue('T1', "");
        $sheet->setCellValue('U1', "");
        $sheet->setCellValue('V1', "");
        $sheet->setCellValue('W1', "");
        $sheet->setCellValue('H2', "POBLACION 10-14 AÑOS");
        $sheet->setCellValue('I2', "");
        $sheet->setCellValue('J2', "POBLACION 15-19 AÑOS");
        $sheet->setCellValue('K2', "");
        $sheet->setCellValue('L2', "SERVIDORAS\ES");
        $sheet->setCellValue('M2', "");
        $sheet->setCellValue('N2', "MADRES,PADRES Y/O TUTORES");
        $sheet->setCellValue('O2', "");
        $sheet->setCellValue('H3', "M");
        $sheet->setCellValue('I3', "H");
        $sheet->setCellValue('J3', "M");
        $sheet->setCellValue('K3', "H");
        $sheet->setCellValue('L3', "M");
        $sheet->setCellValue('M3', "H");
        $sheet->setCellValue('N3', "M");
        $sheet->setCellValue('O3', "H");
        $sheet->setCellValue('P2', "POBLACION 10-14 AÑOS");
        $sheet->setCellValue('Q2', "");
        $sheet->setCellValue('R2', "POBLACION 15-19 AÑOS");
        $sheet->setCellValue('S2', "");
        $sheet->setCellValue('T2', "SERVIDORAS\ES");
        $sheet->setCellValue('U2', "");
        $sheet->setCellValue('V2', "MADRES,PADRES Y/O TUTORES");
        $sheet->setCellValue('W2', "");
        $sheet->setCellValue('P3', "M");
        $sheet->setCellValue('Q3', "H");
        $sheet->setCellValue('R3', "M");
        $sheet->setCellValue('S3', "H");
        $sheet->setCellValue('T3', "M");
        $sheet->setCellValue('U3', "H");
        $sheet->setCellValue('V3', "M");
        $sheet->setCellValue('W3', "H");

       
        if($mes == 'seleccione'){
            $rows=4;
            foreach($avance as $a){
            foreach($entregables as $e){
                if($e['id_entregable'] == $a['fk_id_entregable'] ){  
        foreach($programa as $p){  
            foreach($user as $u){
            if($u['usuario_id'] == $p['fk_user'] && $p['rol_usuario'] == 'Enlace GEPEA'){ 
                    if($p['id_programa'] == $e['fk_id_programa']){
                $sheet->setCellValue('A' . $rows, $p['nombre_programa']);
                $sheet->setCellValue('B'. $rows, $e['nombre_entregable']);
                
                          
                        $sheet->setCellValue('C' . $rows, $a['mes']);
                        $sheet->setCellValue('D' . $rows, $a['municipio']);
                        $sheet->setCellValue('E'. $rows, $a['avance_entregable']);
                        $sheet->setCellValue('F'. $rows, $a['institucion']);
                        $sheet->setCellValue('G'. $rows, $a['descripcion']);
                        $sheet->setCellValue('H'. $rows, $a['m_10']);
                        $sheet->setCellValue('I'. $rows, $a['h_10']);
                        $sheet->setCellValue('J'. $rows, $a['m_15']);
                        $sheet->setCellValue('K'. $rows, $a['h_15']);
                        $sheet->setCellValue('L'. $rows, $a['m_ser']);
                        $sheet->setCellValue('M'. $rows, $a['h_ser']);
                        $sheet->setCellValue('N'. $rows, $a['m_padres']);
                        $sheet->setCellValue('O'. $rows, $a['h_padres']);
                        $sheet->setCellValue('P'. $rows, $a['ms_10']);
                        $sheet->setCellValue('Q'. $rows, $a['hs_10']);
                        $sheet->setCellValue('R'. $rows, $a['ms_15']);
                        $sheet->setCellValue('S'. $rows, $a['hs_15']);
                        $sheet->setCellValue('T'. $rows, $a['ms_ser']);
                        $sheet->setCellValue('U'. $rows, $a['hs_ser']);
                        $sheet->setCellValue('V'. $rows, $a['ms_padres']);
                        $sheet->setCellValue('W'. $rows, $a['hs_padres']);
                        $rows ++;
                    }
                    }
                    }
                    }
                }
            }
            
        }
    }elseif($mes != 'seleccione'){
        $rows=4;
                         foreach($avance as $a){
                            foreach($entregables as $e){
                             if($mes == $a['mes'] && $e['id_entregable'] == $a['fk_id_entregable'] ){ 
                                foreach($programa as $p){  
                                    foreach($user as $u){
                                    if($u['usuario_id'] == $p['fk_user'] && $p['rol_usuario'] == 'Enlace GEPEA'){ 
                                            if($p['id_programa'] == $e['fk_id_programa']){
                                $sheet->setCellValue('A' . $rows, $p['nombre_programa']);
                                $sheet->setCellValue('B'. $rows, $e['nombre_entregable']);
                                $sheet->setCellValue('C' . $rows, $a['mes']);
                                $sheet->setCellValue('D' . $rows, $a['municipio']);
                                $sheet->setCellValue('E'. $rows, $a['avance_entregable']);
                                $sheet->setCellValue('F'. $rows, $a['institucion']);
                                $sheet->setCellValue('G'. $rows, $a['descripcion']);
                                $sheet->setCellValue('H'. $rows, $a['m_10']);
                                $sheet->setCellValue('I'. $rows, $a['h_10']);
                                $sheet->setCellValue('J'. $rows, $a['m_15']);
                                $sheet->setCellValue('K'. $rows, $a['h_15']);
                                $sheet->setCellValue('L'. $rows, $a['m_ser']);
                                $sheet->setCellValue('M'. $rows, $a['h_ser']);
                                $sheet->setCellValue('N'. $rows, $a['m_padres']);
                                $sheet->setCellValue('O'. $rows, $a['h_padres']);
                                $sheet->setCellValue('P'. $rows, $a['ms_10']);
                                $sheet->setCellValue('Q'. $rows, $a['hs_10']);
                                $sheet->setCellValue('R'. $rows, $a['ms_15']);
                                $sheet->setCellValue('S'. $rows, $a['hs_15']);
                                $sheet->setCellValue('T'. $rows, $a['ms_ser']);
                                $sheet->setCellValue('U'. $rows, $a['hs_ser']);
                                $sheet->setCellValue('V'. $rows, $a['ms_padres']);
                                $sheet->setCellValue('W'. $rows, $a['hs_padres']);
                                $rows ++;
                             }
                            
                            }
                        }
                    }         
            }
          
        }
       
    }
   
        }


        // Rename worksheet
        $excel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleArray);
        $excel->getActiveSheet()->getStyle('H1:W1')->applyFromArray($styleArray);
        $excel->getActiveSheet()->getStyle('H2:W2')->applyFromArray($styleArray);
        $excel->getActiveSheet()->getStyle('H3:W3')->applyFromArray($styleArray);

        $excel->getActiveSheet()->mergeCells('H1:O1');
        $excel->getActiveSheet()->mergeCells('P1:W1');
        $excel->getActiveSheet()->mergeCells('H2:I2');
        $excel->getActiveSheet()->mergeCells('J2:K2');
        $excel->getActiveSheet()->mergeCells('L2:M2');
        $excel->getActiveSheet()->mergeCells('N2:O2');
        $excel->getActiveSheet()->mergeCells('P2:Q2');
        $excel->getActiveSheet()->mergeCells('R2:S2');
        $excel->getActiveSheet()->mergeCells('T2:U2');
        $excel->getActiveSheet()->mergeCells('V2:W2');

        $excel->getActiveSheet()->setTitle('GEPEA');

        $excel->setActiveSheetIndex(0);
    
        $excelWriter = new Xlsx($excel);
    
       // We have to create a real temp file here because the
        // save() method doesn't support in-memory streams.
        $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
        $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
        $excelWriter->save($tempFile);
    
        // For Excel2007 and above .xlsx files   
        $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response = $response->withHeader('Content-Disposition', 'attachment; filename="basededatos.xlsx"');
    
        $stream = fopen($tempFile, 'r+');
    
        $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
    
        return $response;
    }
    }
    
    else if ($tipo == 'indicadores'){

        $indicadores = Indicadores::where('año','=',$año)->get();
        $indicador= Indicador::all();
        $variables = Variables::all();
        $pp = Pp::all();
        if($indicadores == "[]"){

            $message = 'No se encontraron registros. Intente nuevamente.';
            $class = 'red';
            return $this->container->get('view')->render($response, 'baseprogramas.html', ['class'=>$class,'message'=> $message,'año'=>$año,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario, 'tipo'=>$tipo]);
          }else{
        $styleDrow =[
            'font' => [
                'bold'=>true,
                'size'=>10,
                'color'=> [
                   'argb'=> Color::COLOR_BLACK,]
            ]

        ];

        $styleAvar =[
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => '00EEECE1',
                ]
            ],

        ];

        $styleArray = [
            'font' => [
                'size'=>10,
                'color'=> [
                   'argb'=> Color::COLOR_BLACK,]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER_CONTINUOUS,
                'wrapText'=> true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'=>[
                       'argb'=> Color::COLOR_BLACK,]
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => '009ABCE6',
                ]
            ],
        ];

       

        $excel = new Spreadsheet();
      
        $sheet = $excel->setActiveSheetIndex(0);

        if($mes == 'seleccione'){

        $sheet->setCellValue('A1', "Pp");
        $sheet->setCellValue('B1', "Num");
        $sheet->setCellValue('C1', "V");
        $sheet->setCellValue('D1', "Nombre variables");
        $sheet->setCellValue('E1', "Enero");
        $sheet->setCellValue('F1', "Febrero");
        $sheet->setCellValue('G1', "Marzo");
        $sheet->setCellValue('H1', "Abril");
        $sheet->setCellValue('I1', "Mayo");
        $sheet->setCellValue('J1', "Junio");
        $sheet->setCellValue('K1', "Julio");
        $sheet->setCellValue('L1', "Agosto");
        $sheet->setCellValue('M1', "Septiembre");
        $sheet->setCellValue('N1', "Octubre");
        $sheet->setCellValue('O1', "Noviembre");
        $sheet->setCellValue('P1', "Diciembre");
        $sheet->setCellValue('Q1', "Anual");

        $rows=2;
        foreach($indicadores as $i){
            foreach($indicador as $n){
                if($i['indicador'] == $n['id_indicador']){
                    foreach($pp as $p){
                        if($p['id_pp'] == $n['fk_pp']){
                    foreach($variables as $v ){
                        if($n['id_indicador'] == $v['fk_indicador']){
                            
                            if($v['variable'] == 'A'){
                                $sheet->setCellValue('A' . $rows , $p['pp']);
                                $sheet->setCellValue('B' . $rows , $n['numero']);
                                $sheet->setCellValue('C' . $rows, $v['variable']);
                                $sheet->setCellValue('D' . $rows, $v['nombre'])->getStyle('D'.$rows)->applyFromArray($styleDrow);
                                $sheet->setCellValue('E' . $rows,$i['en_a'])->getStyle('E'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('F' . $rows,$i['feb_a'])->getStyle('F'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('G' . $rows,$i['mar_a'])->getStyle('G'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('H' . $rows,$i['ab_a'])->getStyle('H'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('I' . $rows,$i['may_a'])->getStyle('I'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('J' . $rows,$i['jun_a'])->getStyle('J'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('K' . $rows,$i['jul_a'])->getStyle('K'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('L' . $rows,$i['ago_a'])->getStyle('L'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('M' . $rows,$i['sep_a'])->getStyle('M'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('N' . $rows,$i['oct_a'])->getStyle('N'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('O' . $rows,$i['nov_a'])->getStyle('O'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('P' . $rows,$i['dic_a'])->getStyle('P'.$rows)->applyFromArray($styleAvar);
                                $sheet->setCellValue('Q' . $rows,$i['anual_a'])->getStyle('Q'.$rows)->applyFromArray($styleAvar);
                              
                            }
                            
                            if($v['variable'] == 'B'){
                                $sheet->setCellValue('A' . $rows , $p['pp']);
                                $sheet->setCellValue('B' . $rows , $n['numero']);
                                $sheet->setCellValue('C' . $rows, $v['variable']);
                                $sheet->setCellValue('D' . $rows, $v['nombre']);
                                $sheet->setCellValue('E' . $rows,$i['en_b']);
                                $sheet->setCellValue('F' . $rows,$i['feb_b']);
                                $sheet->setCellValue('G' . $rows,$i['mar_b']);
                                $sheet->setCellValue('H' . $rows,$i['ab_b']);
                                $sheet->setCellValue('I' . $rows,$i['may_b']);
                                $sheet->setCellValue('J' . $rows,$i['jun_b']);
                                $sheet->setCellValue('K' . $rows,$i['jul_b']);
                                $sheet->setCellValue('L' . $rows,$i['ago_b']);
                                $sheet->setCellValue('M' . $rows,$i['sep_b']);
                                $sheet->setCellValue('N' . $rows,$i['oct_b']);
                                $sheet->setCellValue('O' . $rows,$i['nov_b']);
                                $sheet->setCellValue('P' . $rows,$i['dic_b']);
                                $sheet->setCellValue('Q' . $rows,$i['anual_b'])->getStyle('Q'.$rows)->applyFromArray($styleAvar);
                            }
                            
                           
                            if($v['variable'] == 'C'){
                                $sheet->setCellValue('A' . $rows , $p['pp']);
                                $sheet->setCellValue('B' . $rows , $n['numero']);
                                $sheet->setCellValue('C' . $rows, $v['variable']);
                                $sheet->setCellValue('D' . $rows, $v['nombre']);
                                $sheet->setCellValue('E' . $rows,$i['en_c']);
                                $sheet->setCellValue('F' . $rows,$i['feb_c']);
                                $sheet->setCellValue('G' . $rows,$i['mar_c']);
                                $sheet->setCellValue('H' . $rows,$i['ab_c']);
                                $sheet->setCellValue('I' . $rows,$i['may_c']);
                                $sheet->setCellValue('J' . $rows,$i['jun_c']);
                                $sheet->setCellValue('K' . $rows,$i['jul_c']);
                                $sheet->setCellValue('L' . $rows,$i['ago_c']);
                                $sheet->setCellValue('M' . $rows,$i['sep_c']);
                                $sheet->setCellValue('N' . $rows,$i['oct_c']);
                                $sheet->setCellValue('O' . $rows,$i['nov_c']);
                                $sheet->setCellValue('P' . $rows,$i['dic_c']);
                                $sheet->setCellValue('Q' . $rows,$i['anual_c'])->getStyle('Q'.$rows)->applyFromArray($styleAvar);
                               
                        }
                        $rows ++;  

                        }
                    }
                }
                }
                }
            }
           
        } 


            
            
            $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
            $excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(false);
            $excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
            $excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(false);
            $excel->getActiveSheet()->getColumnDimension('O')->setWidth(10);

            
            $excel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);

        } else if($mes != 'seleccione'){
            
            $sheet->setCellValue('A1', "Pp");
            $sheet->setCellValue('B1', "Num");
            $sheet->setCellValue('C1', "V");
            $sheet->setCellValue('D1', "Nombre variables");
            $sheet->setCellValue('E1', $mes);

            $rows=2;
        foreach($indicadores as $i){
            foreach($indicador as $n){
                if($i['indicador'] == $n['id_indicador']){
                    foreach($pp as $p){
                        if($p['id_pp'] == $n['fk_pp']){
                    foreach($variables as $v ){
                        if($n['id_indicador'] == $v['fk_indicador']){
                         
                            if($v['variable'] == 'A'){

                                $sheet->setCellValue('A' . $rows , $p['pp']);
                                $sheet->setCellValue('B' . $rows , $n['numero']);
                                $sheet->setCellValue('C' . $rows, $v['variable']);
                                $sheet->setCellValue('D' . $rows, $v['nombre'])->getStyle('D'.$rows)->applyFromArray($styleDrow);
                                $sheet->setCellValue('E' . $rows, ($mes == 'ENERO' ? $i['en_a'] : ( $mes == 'FEBRERO' ? $i['feb_a'] :($mes == 'MARZO' ? $i['mar_a'] :
                                 ($mes == 'ABRIL' ? $i['ab_a']:($mes == 'MAYO' ? $i['may_a'] : ($mes == 'JUNIO' ? $i['jun_a'] : ($mes == 'JULIO' ? $i['jul_a'] :
                                 ($mes == 'AGOSTO' ? $i['ago_a']: ($mes == 'SEPTIEMBRE' ? $i['sep_a']: ($mes == 'OCTUBRE' ? $i['oct_a']: ( $mes == 'NOVIEMBRE' ? $i['nov_a']:
                                ( $mes == 'DICIEMBRE' ? $i['dic_a'] : 0 )))))))))))))->getStyle('E'.$rows)->applyFromArray($styleAvar);
                            }

                            if($v['variable'] == 'B'){

                                $sheet->setCellValue('A' . $rows , $p['pp']);
                                $sheet->setCellValue('B' . $rows , $n['numero']);
                                $sheet->setCellValue('C' . $rows, $v['variable']);
                                $sheet->setCellValue('D' . $rows, $v['nombre']);
                                $sheet->setCellValue('E' . $rows, ($mes == 'ENERO' ? $i['en_b'] : ( $mes == 'FEBRERO' ? $i['feb_b'] :($mes == 'MARZO' ? $i['mar_b'] :
                                 ($mes == 'ABRIL' ? $i['ab_b']:($mes == 'MAYO' ? $i['may_b'] : ($mes == 'JUNIO' ? $i['jun_b'] : ($mes == 'JULIO' ? $i['jul_b'] :
                                 ($mes == 'AGOSTO' ? $i['ago_b']: ($mes == 'SEPTIEMBRE' ? $i['sep_b']: ($mes == 'OCTUBRE' ? $i['oct_b']: ( $mes == 'NOVIEMBRE' ? $i['nov_b']:
                                ( $mes == 'DICIEMBRE' ? $i['dic_b'] : 0 )))))))))))));
                            }

                            if($v['variable'] == 'C'){

                                $sheet->setCellValue('A' . $rows , $p['pp']);
                                $sheet->setCellValue('B' . $rows , $n['numero']);
                                $sheet->setCellValue('C' . $rows, $v['variable']);
                                $sheet->setCellValue('D' . $rows, $v['nombre']);
                                $sheet->setCellValue('E' . $rows, ($mes == 'ENERO' ? $i['en_c'] : ( $mes == 'FEBRERO' ? $i['feb_c'] :($mes == 'MARZO' ? $i['mar_c'] :
                                 ($mes == 'ABRIL' ? $i['ab_c']:($mes == 'MAYO' ? $i['may_c'] : ($mes == 'JUNIO' ? $i['jun_c'] : ($mes == 'JULIO' ? $i['jul_c'] :
                                 ($mes == 'AGOSTO' ? $i['ago_c']: ($mes == 'SEPTIEMBRE' ? $i['sep_c']: ($mes == 'OCTUBRE' ? $i['oct_c']: ( $mes == 'NOVIEMBRE' ? $i['nov_c']:
                                ( $mes == 'DICIEMBRE' ? $i['dic_c'] : 0 )))))))))))));
                            }

                            $rows ++; 
                        
                    }
                }
            }
        }
    }
}
        }
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);

        $excel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
        }

        $excel->getActiveSheet()->setTitle('INDICADORES');
        $excel->setActiveSheetIndex(0);
    
        $excelWriter = new Xlsx($excel);
    
       // We have to create a real temp file here because the
        // save() method doesn't support in-memory streams.
        $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
        $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
        $excelWriter->save($tempFile);
    
        // For Excel2007 and above .xlsx files   
        $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response = $response->withHeader('Content-Disposition', 'attachment; filename="basededatos.xlsx"');
    
        $stream = fopen($tempFile, 'r+');
    
        $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
    
        return $response;
    }
    }
 }



    }    
}