<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $authMiddleware = function(Request $request, RequestHandler $handler ){
        if (empty($_SESSION['user'])) {
            $response = new \Slim\Psr7\Response();
            return $response->withHeader('Location', 'iniciarSesion')->withStatus(302);
        }

        return $handler->handle($request);
    };

///ROUTES PARA LOGIN, REGISTRO Y CERRAR SESION ///
    $app->get('/iniciarSesion', \App\Application\Controladores\usuarioController::class . ':mostrarIniciarSesion')->setName('iniciarSesion');
    $app->post('/iniciarSesion', \App\Application\Controladores\usuarioController::class . ':iniciarSesion');
    $app->get('/registro', \App\Application\Controladores\usuarioController::class . ':mostrarRegistro')->setName('registro')->add($authMiddleware);
    $app->post('/registro', \App\Application\Controladores\usuarioController::class . ':registrarUsuario')->add($authMiddleware);
    //CERRAR SESION
    $app->post('/cerrarSesion',\App\Application\Controladores\usuarioController::class . ':cerrarSesion')->setName('cerrarSesion');
    //recuperar contraseña
    $app->get('/recuperarContraseña', \App\Application\Controladores\usuarioController::class . ':mostrarRecoverPassword')->setName('recoverPassword');
    $app->post('/recuperarContraseña', \App\Application\Controladores\usuarioController::class . ':recoverPassword');
    $app->get('/mensajeEspera', \App\Application\Controladores\usuarioController::class . ':mostrarMensajeEspera')->setName('mensajeEspera');
    $app->get('/confirmarRecuperarContraseña/{token}', \App\Application\Controladores\usuarioController::class . ':confirmPasswordRecover')->setName('confirmPasswordRecover');
    $app->post('/confirmarRecuperarContraseña/{token}', \App\Application\Controladores\usuarioController::class . ':passwordRecover');
    
    //ROUTE PARA ASIGNAR FECHA LIMITE DE CAPTURA EN LAS DIFERENTES SECCIONES DE LA PLATAFORMA
    $app->get('/calendario', \App\Application\Controladores\calendarController::class . ':mostrarCalendario')->setName('verCalendario')->add($authMiddleware);
    $app->post('/actualizarInforme', \App\Application\Controladores\calendarController::class . ':asignarFechaInforme')->setName('asignarFechaInforme');
    $app->post('/actualizarAvance', \App\Application\Controladores\calendarController::class . ':asignarFechaAvance')->setName('asignarFechaAvance');

    
    ///ROUTES PAGINA INICIO Y PROGRAMA////
    $app->get('/inicio', \App\Application\Controladores\programasController::class . ':inicio')->setName('inicio')->add($authMiddleware);
    $app->post('/inicio',\App\Application\Controladores\programasController::class . ':buscarPrograma')->setName('BuscarPrograma');
    $app->get('/agregarPrograma', \App\Application\Controladores\programasController::class . ':mostrarAgregarPrograma')->add($authMiddleware);
    $app->post('/agregarPrograma',\App\Application\Controladores\programasController::class . ':agregarPrograma')->setName('agregarPrograma');
    $app->get('/agregarPrograma/{id}/editar', \App\Application\Controladores\programasController::class . ':mostrarEditarPrograma')->setName('editarPrograma')->add($authMiddleware);
    $app->put('/agregarPrograma/{id}/editar',\App\Application\Controladores\programasController::class . ':editarPrograma')->add($authMiddleware);
    //route para eliminar programa
   $app->get('/programa/eliminar/{id}',\App\Application\Controladores\programasController::class . ':eliminarPrograma')->setName('eliminarPrograma')->add($authMiddleware);

    ///ROUTES ENTREGABLE ///
    $app->get('/entregable/{id}', \App\Application\Controladores\entregableController::class . ':mostrarEntregable')->setName('/entregable/{id}')->add($authMiddleware);
    $app->get('/agregarEntregable/{id}', \App\Application\Controladores\entregableController::class . ':mostarAgregarEntregable')->setName('agregarEntregable/{id}')->add($authMiddleware);
    $app->post('/agregarEntregable/{id}', \App\Application\Controladores\entregableController::class . ':guardarEntregable')->setName('agregarEntregable/{id}');
    $app->get('/editarEntregable/{id_programa}/{id_entregable}', \App\Application\Controladores\entregableController::class . ':mostrarEditarEntregable')->setName('editarEntregable')->add($authMiddleware);
    $app->put('/editarEntregable/{id_programa}/{id_entregable}',\App\Application\Controladores\entregableController::class . ':guardarEditarEntregable');
    $app->get('/generarFICHA',\App\Application\Controladores\entregableController::class . ':mostrarFicha' )->setName('generarPDF');
     //route para eliminar entregable
   $app->get('/entregable/eliminar/{id}/{id_programa}',\App\Application\Controladores\entregableController::class . ':eliminarEntregable')->setName('eliminarEntregable');

    ////AVANCE ROUTES////
    $app->get('/avance/{id}',\App\Application\Controladores\avanceController::class . ':mostrarAvance')->setName('/avance/{id}')->add($authMiddleware);
    $app->post('/avance/{id}', \App\Application\Controladores\avanceController::class . ':guardarAvance')->setName('/avance/{id}');
    $app->get('/editarAvance/{id_entregable}/{id_avance}', \App\Application\Controladores\avanceController::class . ':mostrarEditarAvance')->setName('editarAvance')->add($authMiddleware);
    $app->put('/editarAvance/{id_entregable}/{id_avance}',\App\Application\Controladores\avanceController::class . ':editarAvance');
     //route para eliminar avance
   $app->get('/avance/eliminar/{id_avance}/{id_entregable}',\App\Application\Controladores\avanceController::class . ':eliminarAvance')->setName('eliminarAvance');
  
    ////INFORME ROUTES///
    $app->get('/informe/{id}', \App\Application\Controladores\informesController::class . ':mostrarInformes')->setName('informe/{id}')->add($authMiddleware);
    $app->post('/informe/{id}', \App\Application\Controladores\informesController::class . ':guardarInforme')->setName('/informe/{id}');

    //RUTASS INDICADORES//

    //ruta para ver pagina principal de indicadores
    $app->get('/indicadores', \App\Application\Controladores\indicadoresController::class . ':mostrarIndicadores')->setName('indicadores')->add($authMiddleware);
    //ver pagina agregar indicador
    $app->get('/agregarIndicador',\App\Application\Controladores\indicadoresController::class . ':verAgregarIndicador')->setName('agregarIndicador')->add($authMiddleware);   
    //agregarindicador
    $app->post('/agregarIndicador',\App\Application\Controladores\indicadoresController::class . ':agregarIndicador' );
    //editar indicador
    $app->get('/editarIndicador/{id_indicadores}/{id_indicador}',\App\Application\Controladores\indicadoresController::class . ':verEditarIndicador')->setName('editarIndicador')->add($authMiddleware);
    $app->put('/editarIndicador/{id_indicadores}/{id_indicador}',\App\Application\Controladores\indicadoresController::class . ':editarIndicador');
    $app->get('/eliminarDato/{id_indicadores}/{id_indicador}/{mes}',\App\Application\Controladores\indicadoresController::class . ':eliminarDatoIndicador')->setName('eliminarDato');
    //route para eliminar Indicadores
    $app->get('/indicador/eliminar/{id_indicadores}',\App\Application\Controladores\indicadoresController::class . ':eliminarIndicador')->setName('eliminarIndicador');
   //route para ver PDF de indicador
    $app->get('/ficha/{indicador}/{fecha}', \App\Application\Controladores\indicadoresController::class . ':verFicha')->setName('ficha')->add($authMiddleware);
    //route para agregar nuevo indicador 
    $app->get('/nuevoIndicador',\App\Application\Controladores\indicadoresController::class . ':verNuevoIndicador')->setName('nuevoIndicador')->add($authMiddleware);   
    $app->post('/nuevoIndicador',\App\Application\Controladores\indicadoresController::class . ':agregarNuevoIndicador');
    //RUTAS PARA DESCARGAR BASES DE DATOS EN EXCEL //
    $app->get('/basededatos/{id}', \App\Application\Controladores\baseProgramasController::class . ':mostrarDescargable')->setName('descargable')->add($authMiddleware);

    $app->post('/basededatos/{id}',\App\Application\Controladores\baseProgramasController::class . ':descargarBase')->setName('excelGlobal');

    $app->get('/basededatosInformes', \App\Application\Controladores\baseInformesController::class . ':mostrarBaseInformes')->setName('descargableinformes')->add($authMiddleware);
    $app->post('/basededatosInformes',\App\Application\Controladores\baseInformesController::class . ':descargarInformes')->setName('excelInformes');
   //RUTAS PARA SUBIR Y DESCARGAR DOCUMENTOS EN SECCION CATALOGOS
    $app->get('/baseCatalogos', \App\Application\Controladores\baseCatalogosController::class . ':mostrarCatalogos')->setName('descargableCatalogos')->add($authMiddleware);
    $app->post('/baseCatalogos',\App\Application\Controladores\baseCatalogosController::class . ':subirCatalogo')->setName('subirCatalogo')->add($authMiddleware);
    $app->post('/descargarCatalogo',\App\Application\Controladores\baseCatalogosController::class . ':descargarCatalogo')->setName('descargarCatalogo')->add($authMiddleware);
    
    /// ROUTES para MAPA /// 
    $app->get('/mapa',\App\Application\Controladores\mapaController::class . ':mostrarMapa')->setName('mapa')->add($authMiddleware);
    $app->post('/mapa',\App\Application\Controladores\mapaController::class . ':buscar')->setName('buscar')->add($authMiddleware);
};





