<?php
declare(strict_types=1);

namespace App\Application\Controladores;

use App\Application\Modelos\Dependencias;
use App\Application\Modelos\RecuperacionContraseña;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Application\Modelos\Usuario;
use Error;
use Respect\Validation\Rules\Length;
use Psr\Http\Message\UploadedFileInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class usuarioController{

    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function mostrariniciarSesion(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        return $this->container->get('view')->render($response, 'login_usuario.html');
     }
 
     public function iniciarSesion (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        try{
         $input = $request->getParsedBody();
        
         //tomar datos ingresados en login
            $correo = $input['correo'];
            $pass = $input['pass'];
 
            //encontrar usuario
            $user = Usuario::where('correo','=',$correo)->get();
         
          if($user != '[]'){
           
            $userpass = $user[0]->clave_acceso;
                //verificar que las contraseñas sean correctas
                if(password_verify($pass,$userpass)){
                 //$session = $request->getAttribute('session');
                 //$session['user']= $user;
                 $_SESSION["user"] = $user;
                 //si se cumple la condicion, llevar a pagina inicio
               
                return $response->withHeader('Location',"inicio")->withStatus(302);
                }else{
                 //si no se cumple, regresar mensaje de error
             return $this->container->get('view')->render($response, 'login_usuario.html',['message'=>'Usuario O contraseña Incorrectos.']);
                }
            }else{
                return $this->container->get('view')->render($response, 'login_usuario.html',['message'=>'Usuario O contraseña Incorrectos.']);
            }
           
      
        }catch(\PDOException $e) {
         $this->logger->error($e->getMessage());
     }
 
    
     }
 
     public function mostrarRegistro (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $usuario = $_SESSION['user'];
        $nombre_usuario = $usuario[0]->nombre_usuario;
        $dependencia_usuario = $usuario[0]->dependencia;
        $rol = $usuario[0]->rol; 

        $dependencias = Dependencias::all();
        
        return $this->container->get('view')->render($response, 'registro_usuario.html',[ 'message' => '', 'nombre'=>$nombre_usuario,'dependencia'=>$dependencia_usuario,
        'rol'=>$rol,'dependencias'=>$dependencias]);
     }
 
     

     public function registrarUsuario (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
         
         $tplVars = [
             'message' => '',
             'form' => [
                 'login' => ''
             ]
         ];

         $usuario = $_SESSION['user'];
         $nombre= $usuario[0]->nombre_usuario;
         $dependencia= $usuario[0]->dependencia;
         $rol= $usuario[0]->rol; 
         $institutos = Dependencias::all();
 
          $input = $request->getParsedBody();

          $directory = $this->container->get('storageImg');
          $uploadedFiles = $request->getUploadedFiles();

          $uploadedFile = $uploadedFiles['foto_perfil'];
          if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $this->moveUploadedFile($directory, $uploadedFile);
             // $response->getBody()->write('Uploaded: ' . $filename . '<br/>');
          }

          if(!empty($input['correo'] && !empty($input['clave_acceso1']) && !empty($input['clave_acceso1'])) && !empty($input['rol'])){
              $email = Usuario::where('correo','=',$input['correo'])->first();
              if(empty($email) == true){
             if($input['clave_acceso1']== $input['clave_acceso2']){
                 try{
                     $pass = password_hash($input['clave_acceso1'],PASSWORD_DEFAULT);

                     $user = new Usuario();
         
                     $user->correo = $input['correo'];
                     $user->clave_acceso = $pass;
                     $user->nombre_usuario = $input['nombre_usuario'];
                     $user->apellido_usuario = $input['apellido_usuario'];
                     $user->dependencia = $input['dependencia'];
                     $user->unidad_admin=$input['unidad_admin'];
                     $user->foto_perfil=$_FILES['foto_perfil']['name'];
                     $user->rol = $input['rol'];
             
                     $user->save();
                     $tplVars= '¡Usuario Registrado Exitosamente!';
                     $class='blue';
                    
                     return $this->container->get('view')->render($response, "registro_usuario.html",['message'=>$tplVars,'class'=>$class,'nombre'=>$nombre,'dependencia'=>$dependencia,
                    'rol'=>$rol,'dependencias'=>$institutos]);
                    // return $response->withHeader('Location', "iniciarSesion");
         
                 }catch(\PDOException $e){
                     $this->logger->error($e->getMessage());
                     $tplVars['message']= 'DATABASE ERROR';
                     $tplVars['form']=$input;
 
                 }
             }else{
                $tplVars= "Las contraseñas no coinciden. Por favor intente de nuevo.";
                 $class='red';
                     $correo = $input['correo'];
                     $nombre_usuario = $input['nombre_usuario'];
                     $apellido_usuario = $input['apellido_usuario'];
                     $dependenciainput = $input['dependencia'];
                     $unidad_admin=$input['unidad_admin'];
                     $rolinput = $input['rol'];
             }
            }else{
                $tplVars= "Ya existe un usuario registrado con el correo ingresado.Ingrese otro correo e intente de nuevo.";
                 $class='red';
                 $correo = $input['correo'];
                     $nombre_usuario = $input['nombre_usuario'];
                     $apellido_usuario = $input['apellido_usuario'];
                     $dependenciainput = $input['dependencia'];
                     $unidad_admin=$input['unidad_admin'];
                     $rolinput = $input['rol'];
            }
 
          }else{
            $tplVars= "Usuario no guardado porque hay campos vacios. Llene el formulario e intente nuevamente.";
            $class='red';
            $correo = $input['correo'];
            $nombre_usuario = $input['nombre_usuario'];
            $apellido_usuario = $input['apellido_usuario'];
            $dependenciainput = $input['dependencia'];
            $unidad_admin=$input['unidad_admin'];
            $rolinput = $input['rol'];
          }
 
           return $this->container->get('view')->render($response, "registro_usuario.html", ['message'=>$tplVars,'class'=>$class,
           'correo'=>$correo,'usuario'=>$nombre_usuario,'apellido'=>$apellido_usuario,'depen'=>$dependenciainput,'unidad'=>$unidad_admin,
            'rolinput'=>$rolinput ,'nombre'=>$nombre,'dependencia'=>$dependencia,
           'rol'=>$rol,'dependencias'=>$institutos]);   
          
     }
 
     /**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory The directory to which the file is moved
 * @param UploadedFileInterface $uploadedFile The file uploaded file to move
 *
 * @return string The filename of moved file
 */
function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    // see http://php.net/manual/en/function.random-bytes.php
    $basename = bin2hex(random_bytes(8));
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}


public function mostrarRecoverPassword (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
    return $this->container->get('view')->render($response, 'recuperar_contraseña.html');

}

public function mostrarMensajeEspera (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
    return $this->container->get('view')->render($response, 'wait_message.html');

}

public function recoverPassword(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

    $input = $request->getParsedBody();

    $correo = $input['correo'];
    $usuario = Usuario::where('correo','=',$correo)->get('correo');
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

    if(count($usuario)>0){
        try{
        $token = bin2hex(random_bytes(50));

        $pswd = new RecuperacionContraseña();

        $pswd->correo= $correo;
        $pswd->token = $token;
        $pswd->save();

        //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

             //smtp settings
    $mail->isSMTP(); // send as HTML
    $mail->Host = "smtp.gmail.com"; // SMTP servers
    $mail->SMTPAuth = true; // turn on SMTP authentication
    $mail->Username = "cachonfernanda81@gmail.com"; // Your mail
    $mail->Password = 'heubd12pc'; // Your password mail
    $mail->Port = 587; //specify SMTP Port
    $mail->SMTPSecure = 'tls';                               
    $mail->setFrom($correo,'SSIGEN');
    $mail->addAddress('cachonfernanda81@gmail.com'); // Your mail
    $mail->addReplyTo($correo);
    $mail->isHTML(true);
    $mail->Subject='Recuperación de cuenta SSIGEN';
    $code= $token;
    $mail->Body= $message="Copia el siguiente enlace en tu navegador para recuperar tu contraseña: http://$host$uri/confirmarRecuperarContraseña/$code";
    mail($correo, "Send Code", $message);

    if($mail->send()){
        return $this->container->get('view')->render($response, "wait_message.html", ['correo'=>$correo]); 
    }else{
        $message = "Error al enviar correo";
        $color = 'red';
        return $this->container->get('view')->render($response, "recuperar_contraseña.html", ['message'=>$message,'class'=>$color]); 
    }
         
        }catch(\PDOException $e){
        $this->logger->error($e->getMessage());

    }
   
    }else{
        $message = "No existe usuario registrado con el correo proporcionado";
        $color = 'red';
        return $this->container->get('view')->render($response, "recuperar_contraseña.html", ['message'=>$message,'class'=>$color]); 

    }

}


public function confirmPasswordRecover (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
    return $this->container->get('view')->render($response, 'nueva_contraseña.html');

}

public function passwordRecover (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

    $input = $request->getParsedBody();
    $token = $args['token'];

        if($input['pswd-recover1'] == $input['pswd-recover2']){
            $correo = RecuperacionContraseña::where('token','=',$token)->get();
           
          if($correo != "[]"){
          
           try{
                $pass = password_hash($input['pswd-recover1'],PASSWORD_DEFAULT);
        
                Usuario::where('correo','=',$correo[0]->correo)->update(['clave_acceso'=>$pass]);

        RecuperacionContraseña::where('correo','=',$correo[0]->correo)->where('token','=',$token)->delete();

        $color = 'blue';
    $msg = "La contraseña ha sido cambiada exitosamente. Regrese a la página de inicio para iniciar sesión.";
    return $this->container->get('view')->render($response, 'nueva_contraseña.html',['class'=>$color,'message'=>$msg]);
    
    }catch(\PDOException $e){
        $this->logger->error($e->getMessage());

    }


    }else{
        $color = 'red';
        $msg = "El codigo ha expirado";
        return $this->container->get('view')->render($response, 'nueva_contraseña.html',['class'=>$color,'message'=>$msg]);
    }
}else{
        $color = 'red';
        $msg = "Las contraseñas no coinciden. Intente nuevamente.";
        return $this->container->get('view')->render($response, 'nueva_contraseña.html',['class'=>$color,'message'=>$msg]);
    }
  

}
     
     //CERRAR SESION
     public function cerrarSesion (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
         if(session_status() == PHP_SESSION_ACTIVE){
         session_destroy();
         return $response->withHeader('Location',"iniciarSesion")->withStatus(302);
         }
     }

}


