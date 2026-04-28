<?php
declare(strict_types=1);

namespace App\Application\Controladores;

use App\Application\Modelos\Avance;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use App\Application\Modelos\Entregable;
use App\Application\Modelos\Programa;
use App\Application\Modelos\AVG\AVG;
use App\Application\Modelos\ODS\ODS;
use App\Application\Modelos\PMP\EstrategiaPMP;
use App\Application\Modelos\PMP\LineaAccionPMP;
use App\Application\Modelos\PMP\ObjetivoPMP;
use App\Application\Modelos\Compromiso;
use App\Application\Modelos\ENTREGABLES\Finanzas;
use App\Application\Modelos\ENTREGABLES\OdsEntregable;
use App\Application\Modelos\ENTREGABLES\ProgramaEspecial;
use App\Application\Modelos\PMP\Pmp;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use Slim\Psr7\Factory\StreamFactory as StreamFactory;


class entregableController
{
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mostrarEntregable(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $usuario = $_SESSION['user'];
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $dependencia_usuario = $usuario[0]->dependencia;
            $rol = $usuario[0]->rol;
            $id = $args['id'];
            $año = date("Y");
            $programa = Programa::where('id_programa', '=', $id)->get(['nombre_programa', 'objetivoped']);
            $entregable = Entregable::all();
            $avance = Avance::all();
            $pespecial = ProgramaEspecial::all();
            return $this->container->get('view')->
            render($response, 'home_entregable.html', [
                'nombre' => $nombre_usuario,
                'dependencia' => $dependencia_usuario,
                'rol' => $rol,
                'programa' => $programa,
                'entregable' => $entregable,
                'avance' => $avance,
                'idprograma' => $id,
                'pespecial' => $pespecial,
                'año' => $año
            ]);
        }
    }

    public function mostarAgregarEntregable(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $usuario = $_SESSION['user'];
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $dependencia_usuario = $usuario[0]->dependencia;
            $rol = $usuario[0]->rol;
            $id = $args['id'];
            $dataUser = Programa::where('id_programa', '=', $id)->get('rol_usuario');
            $ods = ODS::All();
            $avg = AVG::all();
            $pmp = Pmp::all();
            $objetivopmp = ObjetivoPMP::all();
            $lineaccionpmp = LineaAccionPMP::all();
            $estrategiapmp = EstrategiaPMP::all();
            $compromisos = Compromiso::all();
            return $this->container->get('view')->
            render($response, 'add_entregable.html', [
                'nombre' => $nombre_usuario,
                'dependencia' => $dependencia_usuario,
                'rol' => $rol,
                'id' => $id,
                'ods' => $ods,
                'avg' => $avg,
                'compromisos' => $compromisos,
                'pmp' => $pmp,
                'objetivopmp' => $objetivopmp,
                'lineaccionpmp' => $lineaccionpmp,
                'estrategiapmp' => $estrategiapmp,
                'rolUser' => $dataUser[0]->rol_usuario
            ]);
        }
    }

    public function guardarEntregable(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $input = $request->getParsedBody();

            $id = $args['id'];

            if (!empty($input['nombre_entregable'])) {
                try {
                    $user = Programa::where('id_programa', '=', $id)->get('rol_usuario');


                    $userRol = $user[0]->rol_usuario;
                    if ($userRol != 'Enlace Externo') {
                        if (isset($input['monto-total'])) {
                            $monto = str_replace("$", "", $input['monto-total']);
                            $montodos = str_replace(",", "", $monto);
                            $montoInt = floatval($montodos);
                            $cantidad_total = $montoInt;
                        } else {
                            $cantidad_total = 0;
                        }
                        $porcentaje_total = (isset($input['porcentaje-ubp-total']) ? $input['porcentaje-ubp-total'] : 0);
                    } else {
                        $cantidad_total = 'NULL';
                        $porcentaje_total = 0;
                    }

                    $entregable = new Entregable();

                    $entregable->nombre_entregable = $input['nombre_entregable'];
                    $entregable->periodicidad = (isset($input['periodicidad']) ? $input['periodicidad'] : 'No aplica');
                    $entregable->unidad_medida = (isset($input['unidad_medida']) ? $input['unidad_medida'] : 'No aplica');
                    $entregable->meta = (isset($input['meta']) ? $input['meta'] : 0);
                    $entregable->municipalizable = (isset($input['municipalizable']) ? $input['municipalizable'] : 0);
                    $entregable->compromiso = (isset($input['compromiso']) ? $input['compromiso'] : 'No aplica');
                    $entregable->ods = (isset($input['ods']) ? $input['ods'] : 'No aplica');
                    $entregable->actividad_sigo = (isset($input['act-sigo']) ? $input['act-sigo'] : 'No aplica');
                    $entregable->entregable_sigo = (isset($input['entregable-sigo']) ? $input['entregable-sigo'] : 'No aplica');
                    $entregable->avg = (isset($input['avg']) ? $input['avg'] : 'No aplica');
                    $entregable->monto_total = $cantidad_total;
                    $entregable->porcentaje_ubp_total = $porcentaje_total;
                    $entregable->fk_id_programa = $id;

                    $entregable->save();


                    //GUARDANDO INPUTS PROGRAMA ESPECIAL
                    if ($input['pmp'] != []) {
                        for ($p = 0; $p < count($input['pmp']); $p++) {
                            for ($o = 0; $o < count($input['objpmp']); $o++) {
                                for ($e = 0; $e < count($input['estrategiapmp']); $e++) {
                                    for ($l = 0; $l < count($input['lineapmp']); $l++) {
                                        if ($p == $o && $p == $e && $p == $l) {
                                            $pEspecial = new ProgramaEspecial();
                                            $pEspecial->programa = $input['pmp'][$p];
                                            $pEspecial->objetivo = $input['objpmp'][$o];
                                            $pEspecial->estrategia = $input['estrategiapmp'][$e];
                                            $pEspecial->linea_accion = $input['lineapmp'][$l];
                                            $entregable->programaespecial()->save($pEspecial);

                                        }
                                    }
                                }
                            }
                        }
                    }
                    //GUARDANDO INPUTS MONTO 
                    if ($userRol == 'Administrador' || $userRol == 'Enlace SEMUJERES' || $userRol == 'Enlace GEPEA' || $userRol == 'Admin SEMUJERES-GEPEA') {
                        if (isset($input['rowfuente'])) {
                            for ($x = 0; $x < count($input['rowfuente']); $x++) {
                                for ($y = 0; $y < count($input['rowmonto']); $y++) {
                                    if ($x == $y) {

                                        $monto = str_replace("$", "", $input['rowmonto'][$y]);
                                        $montodos = str_replace(",", "", $monto);
                                        $montoInt = floatval($montodos);

                                        $finanzas = new Finanzas();
                                        $finanzas->fuente = $input['rowfuente'][$x];
                                        $finanzas->monto = $montoInt;
                                        $finanzas->porcentaje_ubp = 0;
                                        $entregable->finanzas()->save($finanzas);
                                    }
                                }

                            }
                        }
                    } elseif ($userRol == 'Enlace Externo') {
                        if (isset($input['rowmonto-externo'])) {
                            for ($e = 0; $e < count($input['rowmonto-externo']); $e++) {
                                for ($p = 0; $p < count($input['rowporcentaje']); $p++) {
                                    if ($e == $p) {
                                        $monto = str_replace("$", "", $input['rowmonto-externo'][$e]);
                                        $montodos = str_replace(",", "", $monto);
                                        $montoInt = floatval($montodos);
                                        $finanzas = new Finanzas();
                                        $finanzas->fuente = 'No Aplica';
                                        $finanzas->monto = $montoInt;
                                        $finanzas->porcentaje_ubp = $input['rowporcentaje'][$p];
                                        $entregable->finanzas()->save($finanzas);
                                    }
                                }

                            }
                        }
                    }
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    return $response->withHeader('Location', "http://$host$uri/entregable/$id")->withStatus(302);
                } catch (\PDOException $e) {
                    $this->logger->error($e->getMessage());
                }
            } else {
                $msg = 'Tu entregable no ha sido guardado porque no le has asignado un nombre, ingresa un nombre y haz clic en guardar nuevamente.';
                $class = 'red';

                $usuario = $_SESSION['user'];
                $nombre_usuario = $usuario[0]->nombre_usuario;
                $dependencia_usuario = $usuario[0]->dependencia;
                $rol = $usuario[0]->rol;
                $id = $args['id'];
                $dataUser = Programa::where('id_programa', '=', $id)->get('rol_usuario');
                $ods = ODS::All();
                $avg = AVG::all();
                $pmp = Pmp::all();
                $objetivopmp = ObjetivoPMP::all();
                $lineaccionpmp = LineaAccionPMP::all();
                $estrategiapmp = EstrategiaPMP::all();
                $compromisos = Compromiso::all();

                $nombreentregable = $input['nombre_entregable'];
                $periodicidad = $input['periodicidad'];
                $unidadmedida = $input['unidad_medida'];
                $meta = $input['meta'];
                $municipalizable = $input['municipalizable'];
                $compromiso = $input['compromiso'];
                $odsinput = $input['ods'];
                $actividad_sigo = $input['act-sigo'];
                $entregable_sigo = $input['entregable-sigo'];
                $avginput = $input['avg'];
                return $this->container->get('view')->render($response, 'add_entregable.html', [
                    'nombreentregable' => $nombreentregable,
                    'periodo' => $periodicidad,
                    'medida' => $unidadmedida,
                    'meta' => $meta,
                    'municipalizable' => $municipalizable,
                    'compromiso' => $compromiso,
                    'odsinput' => $odsinput,
                    'actsigo' => $actividad_sigo,
                    'entregablesigo' => $entregable_sigo,
                    'avginput' => $avginput,
                    'message' => $msg,
                    'class' => $class,
                    'nombre' => $nombre_usuario,
                    'dependencia' => $dependencia_usuario,
                    'rol' => $rol,
                    'id' => $id,
                    'ods' => $ods,
                    'avg' => $avg,
                    'compromisos' => $compromisos,
                    'pmp' => $pmp,
                    'objetivopmp' => $objetivopmp,
                    'lineaccionpmp' => $lineaccionpmp,
                    'estrategiapmp' => $estrategiapmp,
                    'rolUser' => $dataUser[0]->rol_usuario
                ]);
            }
        }
    }

    public function mostrarEditarEntregable(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $usuario = $_SESSION['user'];
            $nombre_usuario = $usuario[0]->nombre_usuario;
            $rol = $usuario[0]->rol;
            $dependencia_usuario = $usuario[0]->dependencia;
            $id_programa = $args['id_programa'];
            $id_entregable = $args['id_entregable'];
            $dataUser = Programa::where('id_programa', '=', $id_programa)->get('rol_usuario');
            $entregable = Entregable::all();
            $finanzas = Finanzas::where('fk_id_entregable', '=', $id_entregable)->get();
            $dinero = array();
            foreach ($finanzas as $f) {
                $monto = floatval($f['monto']);

                $change = number_format($monto, 2);
                $dinero[] = ['id' => $f['id'], 'fuente' => $f['fuente'], 'monto' => "$" . $change, 'porcentaje_ubp' => $f['porcentaje_ubp']];

            }

            $json = json_decode(json_encode($dinero), FALSE);
            $pEspecial = ProgramaEspecial::where('fk_id_entregable', '=', $id_entregable)->get();
            $ods = ODS::All();
            $pmp = Pmp::all();
            $objetivopmp = ObjetivoPMP::all();
            $lineaccionpmp = LineaAccionPMP::all();
            $estrategiapmp = EstrategiaPMP::all();
            $compromisos = Compromiso::all();
            return $this->container->get('view')->render($response, 'edit_entregable.html', [
                'nombre' => $nombre_usuario,
                'dependencia' => $dependencia_usuario,
                'rol' => $rol,
                'id_programa' => $id_programa,
                'id_entregable' => $id_entregable,
                'entregable' => $entregable,
                'compromisos' => $compromisos,
                'pmp' => $pmp,
                'objetivopmp' => $objetivopmp,
                'lineaccionpmp' => $lineaccionpmp,
                'estrategiapmp' => $estrategiapmp,
                'finanzas' => $json,
                'pespecial' => $pEspecial,
                'rolUser' => $dataUser[0]->rol_usuario,
                'ods' => $ods
            ]);
        }
    }

    public function guardarEditarEntregable(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $input = $request->getParsedBody();

            $id_programa = $args['id_programa'];
            $id_entregable = $args['id_entregable'];

            if (!empty($input['nombre_entregable'])) {
                try {
                    $user = Programa::where('id_programa', '=', $id_programa)->get('rol_usuario');
                    $userRol = $user[0]->rol_usuario;

                    $userRol = $user[0]->rol_usuario;
                    if ($userRol != 'Enlace Externo') {
                        if (isset($input['monto-total'])) {
                            $monto = str_replace("$", "", $input['monto-total']);
                            $montodos = str_replace(",", "", $monto);
                            $montoInt = floatval($montodos);
                            $cantidad_total = $montoInt;
                        } else {
                            $cantidad_total = 0;
                        }
                        $porcentaje_total = (isset($input['porcentaje-ubp-total']) ? $input['porcentaje-ubp-total'] : 0);
                    } else {
                        $cantidad_total = 'NULL';
                        $porcentaje_total = 0;
                    }


                    $entregable = Entregable::find($id_entregable);
                    $entregable->nombre_entregable = $input['nombre_entregable'];
                    $entregable->periodicidad = (isset($input['periodicidad']) ? $input['periodicidad'] : 'No aplica');
                    $entregable->unidad_medida = (isset($input['unidad_medida']) ? $input['unidad_medida'] : 'No aplica');
                    $entregable->meta = (isset($input['meta']) ? $input['meta'] : 0);
                    $entregable->municipalizable = (isset($input['municipalizable']) ? $input['municipalizable'] : 0);
                    $entregable->compromiso = (isset($input['compromiso']) ? $input['compromiso'] : 'No aplica');
                    $entregable->ods = (isset($input['ods']) ? $input['ods'] : 'No aplica');
                    $entregable->actividad_sigo = (isset($input['act-sigo']) ? $input['act-sigo'] : 'No aplica');
                    $entregable->entregable_sigo = (isset($input['entregable-sigo']) ? $input['entregable-sigo'] : 'No aplica');
                    $entregable->avg = (isset($input['avg']) ? $input['avg'] : 'No aplica');
                    $entregable->monto_total = $cantidad_total;
                    $entregable->porcentaje_ubp_total = $porcentaje_total;
                    $entregable->fk_id_programa = $id_programa;

                    $entregable->save();


                    //GUARDANDO INPUTS PROGRAMA ESPECIAL
                    if ($input['pmp'] != []) {
                        $entregable->programaespecial()->delete();
                        for ($p = 0; $p < count($input['pmp']); $p++) {
                            for ($o = 0; $o < count($input['objpmp']); $o++) {
                                for ($e = 0; $e < count($input['estrategiapmp']); $e++) {
                                    for ($l = 0; $l < count($input['lineapmp']); $l++) {
                                        if ($p == $o && $p == $e && $p == $l) {
                                            $pEspecial = new ProgramaEspecial();
                                            $pEspecial->programa = $input['pmp'][$p];
                                            $pEspecial->objetivo = $input['objpmp'][$o];
                                            $pEspecial->estrategia = $input['estrategiapmp'][$e];
                                            $pEspecial->linea_accion = $input['lineapmp'][$l];
                                            $entregable->programaespecial()->save($pEspecial);

                                        }
                                    }
                                }
                            }
                        }
                    }
                    //GUARDANDO INPUTS MONTO 
                    if ($userRol == 'Administrador' || $userRol == 'Enlace SEMUJERES' || $userRol == 'Enlace GEPEA' || $userRol == 'Admin SEMUJERES-GEPEA') {
                        if (isset($input['rowfuente'])) {
                            $entregable->finanzas()->delete();
                            for ($x = 0; $x < count($input['rowfuente']); $x++) {
                                for ($y = 0; $y < count($input['rowmonto']); $y++) {
                                    if ($x == $y) {
                                        $monto = str_replace("$", "", $input['rowmonto'][$y]);
                                        $montodos = str_replace(",", "", $monto);
                                        $montoInt = floatval($montodos);
                                        $finanzas = new Finanzas();
                                        $finanzas->fuente = $input['rowfuente'][$x];
                                        $finanzas->monto = $montoInt;
                                        $finanzas->porcentaje_ubp = 0;
                                        $entregable->finanzas()->save($finanzas);
                                    }
                                }

                            }
                        }
                    } elseif ($userRol == 'Enlace Externo') {
                        if (isset($input['rowmonto-externo'])) {
                            $entregable->finanzas()->delete();
                            for ($e = 0; $e < count($input['rowmonto-externo']); $e++) {
                                for ($p = 0; $p < count($input['rowporcentaje']); $p++) {
                                    if ($e == $p) {
                                        $monto = str_replace("$", "", $input['rowmonto-externo'][$e]);
                                        $montodos = str_replace(",", "", $monto);
                                        $montoInt = floatval($montodos);
                                        $finanzas = new Finanzas();
                                        $finanzas->fuente = 'No Aplica';
                                        $finanzas->monto = $montoInt;
                                        $finanzas->porcentaje_ubp = $input['rowporcentaje'][$p];
                                        $entregable->finanzas()->save($finanzas);
                                    }
                                }

                            }
                        }


                    }
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

                    return $response->withHeader('Location', "http://$host$uri/entregable/$id_programa")->withStatus(302);
                } catch (\PDOException $e) {
                    $this->logger->error($e->getMessage());
                }
            } else {
                $msg = 'Tu entregable no ha sido guardado porque no le has asignado un nombre, ingresa un nombre y haz clic en guardar nuevamente.';
                $class = 'red';
                $usuario = $_SESSION['user'];
                $nombre_usuario = $usuario[0]->nombre_usuario;
                $rol = $usuario[0]->rol;
                $dependencia_usuario = $usuario[0]->dependencia;
                $id_programa = $args['id_programa'];
                $id_entregable = $args['id_entregable'];
                $dataUser = Programa::where('id_programa', '=', $id_programa)->get('rol_usuario');
                $entregable = Entregable::all();
                $finanzas = Finanzas::where('fk_id_entregable', '=', $id_entregable)->get();
                $dinero = array();
                foreach ($finanzas as $f) {
                    $monto = floatval($f['monto']);

                    $change = number_format($monto, 2);
                    $dinero[] = ['id' => $f['id'], 'fuente' => $f['fuente'], 'monto' => "$" . $change, 'porcentaje_ubp' => $f['porcentaje_ubp']];

                }

                $json = json_decode(json_encode($dinero), FALSE);
                $pEspecial = ProgramaEspecial::where('fk_id_entregable', '=', $id_entregable)->get();
                $ods = ODS::All();
                $pmp = Pmp::all();
                $objetivopmp = ObjetivoPMP::all();
                $lineaccionpmp = LineaAccionPMP::all();
                $estrategiapmp = EstrategiaPMP::all();
                $compromisos = Compromiso::all();
                return $this->container->get('view')->render($response, 'edit_entregable.html', [
                    'message' => $msg,
                    'class' => $class,
                    'nombre' => $nombre_usuario,
                    'dependencia' => $dependencia_usuario,
                    'rol' => $rol,
                    'id_programa' => $id_programa,
                    'id_entregable' => $id_entregable,
                    'entregable' => $entregable,
                    'compromisos' => $compromisos,
                    'pmp' => $pmp,
                    'objetivopmp' => $objetivopmp,
                    'lineaccionpmp' => $lineaccionpmp,
                    'estrategiapmp' => $estrategiapmp,
                    'finanzas' => $json,
                    'pespecial' => $pEspecial,
                    'rolUser' => $dataUser[0]->rol_usuario,
                    'ods' => $ods
                ]);
            }
        }
    }

    public function eliminarEntregable(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $id = $args['id'];
            $id_programa = $args['id_programa'];
            $entregable = Entregable::find($id);
            if ($entregable != null) {
                $entregable->avances()->delete();
                $entregable->informe()->delete();
                $entregable->finanzas()->delete();
                $entregable->programaespecial()->delete();
                $entregable->delete();

            }

            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            return $response->withHeader('Location', "http://$host$uri/entregable/$id_programa")->withStatus(302);
        }
    }


}