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

use App\Application\Modelos\Informe;

use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class baseInformesController {
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarBaseInformes(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if(session_status() == PHP_SESSION_ACTIVE){
        $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol = $usuario[0]->rol;
    $id = $usuario[0]->usuario_id;

    if($rol == 'Administrador'){
        $programa= Programa::get('nombre_programa');
     
            }else if($rol == 'Admin SEMUJERES-GEPEA'){
             $programa = Programa::where('rol_usuario','=',$rol)->orWhere('rol_usuario','=','Enlace SEMUJERES')->orWhere('rol_usuario','=','Enlace GEPEA')->get();  
            
            }else if($rol == 'Enlace Externo' || $rol == 'Enlace SEMUJERES' || $rol == 'Enlace GEPEA'){
             $programa = Programa::where('rol_usuario','=',$rol)->where('fk_user','=',$id)->get();  
            
            }
   
    $año = date("Y"); 

    
        return $this->container->get('view')->render($response, 'baseinformes.html', ['rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario, 'programa'=>$programa,'año'=>$año,'usuario'
    =>$id]);
    
        }
    }

    public function descargarInformes(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if(session_status() == PHP_SESSION_ACTIVE){
        $input= $request->getParsedBody();
       
       $año = $input['año'];
       $actividad = $input['programa'];
       $tri = $input['trimestre'];
       $styleContent=[
        'font' => [
            'size'=>10,
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_TOP,
            'wrapText'=> true,
        ],
        
    ];

    $styleCabecera = [
        'font' => [
            'bold' => true,
            'size'=>11,
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
                'argb' => '000062AE',
            ]
        ],
    ];

       if($actividad == 'seleccione' && $tri == 'seleccione'){
           
       $programa = Programa::where('año',$año)->get();  
       $entregable = Entregable::all();
       $informe = Informe::all();   
         
       if($programa == "[]"){

        $usuario = $_SESSION['user'];
    $nombre_usuario = $usuario[0]->nombre_usuario;
    $dependencia_usuario = $usuario[0]->dependencia;
    $rol = $usuario[0]->rol;
    $id = $usuario[0]->usuario_id;

    if($rol == 'Administrador'){
        $programa= Programa::get('nombre_programa');
     
            }else if($rol == 'Admin SEMUJERES-GEPEA'){
             $programa = Programa::where('rol_usuario','=',$rol)->orWhere('rol_usuario','=','Enlace SEMUJERES')->orWhere('rol_usuario','=','Enlace GEPEA')->get();  
            
            }else if($rol == 'Enlace Externo' || $rol == 'Enlace SEMUJERES' || $rol == 'Enlace GEPEA'){
             $programa = Programa::where('rol_usuario','=',$rol)->where('fk_user','=',$id)->get();  
            
            }
   
    $año = date("Y"); 

    $message = 'No se encontraron registros. Intente nuevamente.';
    $class = 'red';
        return $this->container->get('view')->render($response, 'baseinformes.html', ['class'=>$class,'message'=> $message,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario, 'programa'=>$programa,'año'=>$año,'usuario'
    =>$id]);
    
        
      }else{
       $excel = new Spreadsheet();

    $sheet = $excel->setActiveSheetIndex(0);
   
    $sheet->setCellValue('A1', "PROGRAMA/\nACCION");
    $sheet->setCellValue('B1', 'ENTREGABLE');
    $sheet->setCellValue('C1', 'DESCRIPCION');
    $sheet->setCellValue('D1', 'TRIMESTRE');
    $sheet->setCellValue('E1', "DESCRIPCIÓN\nCUALITATIVA");

    $rows=2;
        foreach($programa as $p){
                foreach($entregable as $e){
                    if($e['fk_id_programa'] == $p['id_programa']){
                  
                        foreach($informe as $i){
                            if($i['fk_id_entregable'] == $e['id_entregable']){
                                $sheet->setCellValue('A' . $rows, $p['nombre_programa'])->getStyle('A'.$rows)->applyFromArray($styleContent);
                                $sheet->setCellValue('B' . $rows, $e['nombre_entregable'])->getStyle('B'.$rows)->applyFromArray($styleContent);
                                $sheet->setCellValue('C' . $rows, $i['periodo'].$i['accion'].$i['personas'].$i['municipios'].$i['objetivo'])->getStyle('C'.$rows)->applyFromArray($styleContent);
                                $sheet->setCellValue('D' . $rows, ucfirst($i['trimestre']))->getStyle('D'.$rows)->applyFromArray($styleContent);
                                $sheet->setCellValue('E' . $rows, $i['descripcion'])->getStyle('E'.$rows)->applyFromArray($styleContent);
            $rows++;
                            }
                        }
                    }
                }
                
        }

   
    $excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(18);
    $excel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleCabecera);
    $excel->setActiveSheetIndex(0);

   /* $excelWriter = new Xlsx($excel);
    
    
        // We have to create a real temp file here because the
        // save() method doesn't support in-memory streams.
        $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
        $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
        $excelWriter->save($tempFile);
    
        // For Excel2007 and above .xlsx files   
        $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response = $response->withHeader('Content-Disposition', 'attachment; filename="Informe.xlsx"');
    
        $stream = fopen($tempFile, 'r+');

        $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));

        return $response;*/
    }
       }else if($actividad != 'seleccione' && $tri != 'seleccione'){
        $programa = Programa::where('año',$año)->where('nombre_programa','=',$actividad)->get(); 
        $entregable = Entregable::all();
        $informe = Informe::where('trimestre',$tri)->get();   

        if($programa == "[]"){

            $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $id = $usuario[0]->usuario_id;
    
        if($rol == 'Administrador'){
            $programa= Programa::get('nombre_programa');
         
                }else if($rol == 'Admin SEMUJERES-GEPEA'){
                 $programa = Programa::where('rol_usuario','=',$rol)->orWhere('rol_usuario','=','Enlace SEMUJERES')->orWhere('rol_usuario','=','Enlace GEPEA')->get();  
                
                }else if($rol == 'Enlace Externo' || $rol == 'Enlace SEMUJERES' || $rol == 'Enlace GEPEA'){
                 $programa = Programa::where('rol_usuario','=',$rol)->where('fk_user','=',$id)->get();  
                
                }
       
        $año = date("Y"); 
    
        $message = 'No se encontraron registros. Intente nuevamente.';
        $class = 'red';
            return $this->container->get('view')->render($response, 'baseinformes.html', ['class'=>$class,'message'=> $message,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario, 'programa'=>$programa,'año'=>$año,'usuario'
        =>$id]);
        
            
          }else{

        $excel = new Spreadsheet();

        $sheet = $excel->setActiveSheetIndex(0);
       
        $sheet->setCellValue('A1', 'PROGRAMA');
        $sheet->setCellValue('B1', 'ENTREGABLE');
        $sheet->setCellValue('C1', 'DESCRIPCION');
        $sheet->setCellValue('D1', 'TRIMESTRE');

    
        $rows=2;
        foreach($programa as $p){
                foreach($entregable as $e){
                    if($e['fk_id_programa'] == $p['id_programa']){
                        foreach($informe as $i){
                            if($i['fk_id_entregable'] == $e['id_entregable']){
            $sheet->setCellValue('A' . $rows, $p['nombre_programa'])->getStyle('A'.$rows)->applyFromArray($styleContent);
            $sheet->setCellValue('B' . $rows, $e['nombre_entregable'])->getStyle('B'.$rows)->applyFromArray($styleContent);
            $sheet->setCellValue('C' . $rows, $i['periodo'].','.$i['accion'].','.$i['personas'].','.$i['municipios'].','.$i['objetivo'].'.'.$i['descripcion'])->getStyle('C'.$rows)->applyFromArray($styleContent);
            $sheet->setCellValue('D' . $rows, ucfirst($i['trimestre']))->getStyle('D'.$rows)->applyFromArray($styleContent);
            $rows++;
                            }
                        }
                    }
                } 
        }
    
       
        $excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(18);
        $excel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleCabecera);
        $excel->getActiveSheet()->setTitle('Informes');
        $excel->setActiveSheetIndex(0);
    
    }
       }else if($actividad != 'seleccione' && $tri == 'seleccione'){

        $programa = Programa::where('año',$año)->where('nombre_programa','=',$actividad)->get();  
        $entregable = Entregable::all();
        $informe = Informe::all();   
        if($programa == "[]"){

            $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol;
        $id = $usuario[0]->usuario_id;
    
        if($rol == 'Administrador'){
            $programa= Programa::get('nombre_programa');
         
                }else if($rol == 'Admin SEMUJERES-GEPEA'){
                 $programa = Programa::where('rol_usuario','=',$rol)->orWhere('rol_usuario','=','Enlace SEMUJERES')->orWhere('rol_usuario','=','Enlace GEPEA')->get();  
                
                }else if($rol == 'Enlace Externo' || $rol == 'Enlace SEMUJERES' || $rol == 'Enlace GEPEA'){
                 $programa = Programa::where('rol_usuario','=',$rol)->where('fk_user','=',$id)->get();  
                
                }
       
        $año = date("Y"); 
    
        $message = 'No se encontraron registros. Intente nuevamente.';
        $class = 'red';
            return $this->container->get('view')->render($response, 'baseinformes.html', ['class'=>$class,'message'=> $message,'rol'=>$rol,'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario, 'programa'=>$programa,'año'=>$año,'usuario'
        =>$id]);
        
            
          }else{
        $excel = new Spreadsheet();
 
     $sheet = $excel->setActiveSheetIndex(0);
    
     $sheet->setCellValue('A1', "PROGRAMA/\nACCION");
     $sheet->setCellValue('B1', 'ENTREGABLE');
     $sheet->setCellValue('C1', 'DESCRIPCION');
     $sheet->setCellValue('D1', 'TRIMESTRE');
     $sheet->setCellValue('E1', "DESCRIPCIÓN\nCUALITATIVA");
 
     $rows=2;
         foreach($programa as $p){
                 foreach($entregable as $e){
                     if($e['fk_id_programa'] == $p['id_programa']){
                   
                         foreach($informe as $i){
                             if($i['fk_id_entregable'] == $e['id_entregable']){
                                 $sheet->setCellValue('A' . $rows, $p['nombre_programa'])->getStyle('A'.$rows)->applyFromArray($styleContent);
                                 $sheet->setCellValue('B' . $rows, $e['nombre_entregable'])->getStyle('B'.$rows)->applyFromArray($styleContent);
                                 $sheet->setCellValue('C' . $rows, $i['periodo'].$i['accion'].$i['personas'].$i['municipios'].$i['objetivo'])->getStyle('C'.$rows)->applyFromArray($styleContent);
                                 $sheet->setCellValue('D' . $rows, ucfirst($i['trimestre']))->getStyle('D'.$rows)->applyFromArray($styleContent);
                                 $sheet->setCellValue('E' . $rows, $i['descripcion'])->getStyle('E'.$rows)->applyFromArray($styleContent);
             $rows++;
                             }
                         }
                     }
                 }
                 
         }
 
    
     $excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(18);
     $excel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleCabecera);
     $excel->setActiveSheetIndex(0);
        }
       }


       $excelWriter = new Xlsx($excel);
        
        
       // We have to create a real temp file here because the
       // save() method doesn't support in-memory streams.
       $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
       $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
       $excelWriter->save($tempFile);
   
       // For Excel2007 and above .xlsx files   
       $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       $response = $response->withHeader('Content-Disposition', 'attachment; filename="Informe.xlsx"');
   
       $stream = fopen($tempFile, 'r+');

       $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));

       return $response;
       }
      
    


    }
      
}