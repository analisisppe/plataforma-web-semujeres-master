#Sistema De Seguimiento A Las Acciones Para El Avance
Requisitos de versiones del proyecto
- PHP: recomendado 8.1 Thread Safe x64 en Windows; en dependencias del proyecto se acepta PHP ^7.2 o ^8.0.
- Composer: última versión estable.
- SQL Server: Express 2019 o 2022.
- Microsoft ODBC Driver for SQL Server: versión 18.
- Drivers PHP para SQL Server: sqlsrv 5.11 o 5.12.
- Git: última versión estable.
- SSMS o Azure Data Studio: cualquier versión reciente.

#Extensiones PHP necesarias:
- ext-json
- fileinfo
- mbstring
- openssl
- pdo
- curl
- sqlsrv
- pdo_sqlsrv

#Dependencias principales del proyecto según Composer:
- slim/slim 4.7
- slim/psr7 1.3
- slim/twig-view 3.0
- illuminate/database 7.30
- monolog/monolog 2.2
- mpdf/mpdf 8.0
- php-di/php-di 6.3
- phpmailer/phpmailer 6.5
- phpoffice/phpspreadsheet 1.18
- respect/validation 2.0
- symfony/asset 5.3
- symfony/twig-bridge 5.3

#Pasos para levantar el proyecto
- Instala PHP 8.1 Thread Safe x64 y agrégalo al PATH.
- Habilita en php.ini estas extensiones: fileinfo, mbstring, openssl, pdo, curl, sqlsrv y pdo_sqlsrv.
- Instala Composer.
- Instala SQL Server Express 2019 o 2022.
- Instala el Microsoft ODBC Driver 18 para SQL Server.
- Instala los drivers de PHP para SQL Server que correspondan a tu versión de PHP.
- Clona el repositorio y entra a la carpeta del proyecto.
- Ejecuta composer install para descargar dependencias.
- Crea la base de datos en SQL Server.
- Ejecuta los scripts schema_core.sql y database/sqlserver/Insert_core_desarollo.sql.
- Configura dbSettings.php con tu instancia de SQL Server.
- Levanta el servidor con:php -S localhost:8080 -t public
-Abre en el navegador: http://localhost:8080/semujeres/public/iniciarSesion
