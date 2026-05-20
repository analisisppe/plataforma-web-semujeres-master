# Guía de Instalación — Plataforma Web SE Mujeres
## Entorno: Windows — Equipo limpio

---

## Requisitos previos

| Software | Versión recomendada | Notas |
|---|---|---|
| PHP | 8.1 Thread Safe x64 | No usar Non-Thread Safe |
| Composer | Última estable | Requiere PHP en PATH |
| SQL Server | Express 2019 o 2022 | Gratuito |
| Microsoft ODBC Driver | 18 | Requerido por los drivers sqlsrv |
| Drivers sqlsrv para PHP | 5.11 o 5.12 | DLL separadas de PHP |
| SSMS o Azure Data Studio | Cualquier versión reciente | Para ejecutar los scripts SQL |
| Git | Última estable | Para clonar el repositorio |

---

## Paso 1 — Instalar PHP

1. Ir a: https://windows.php.net/download/
2. Descargar **PHP 8.1 Thread Safe x64** (archivo `.zip`)
3. Extraer en `C:\php`
4. Copiar `C:\php\php.ini-development` y renombrar a `C:\php\php.ini`
5. Agregar `C:\php` al PATH del sistema:
   - Buscar "Variables de entorno" en el menú Inicio
   - En "Variables del sistema" → `Path` → Editar → Nuevo → `C:\php`
   - Aceptar y **reiniciar PowerShell**
6. Verificar:
   ```powershell
   php -v
   ```
   Debe mostrar: `PHP 8.1.x ...`

---

## Paso 2 — Configurar php.ini

Abrir `C:\php\php.ini` y habilitar las siguientes extensiones (quitar el `;` del inicio de cada línea):

```ini
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo
extension=curl
```

> Las extensiones `sqlsrv` y `pdo_sqlsrv` se agregan en el Paso 5.

---

## Paso 3 — Instalar Composer

1. Descargar el instalador: https://getcomposer.org/Composer-Setup.exe
2. Ejecutar el instalador — detectará PHP automáticamente si está en el PATH
3. Verificar:
   ```powershell
   composer -V
   ```

---

## Paso 4 — Instalar SQL Server Express

1. Descargar desde: https://www.microsoft.com/sql-server/sql-server-downloads  
   (elegir la edición **Express**)
2. Ejecutar el instalador con la opción **Básica** o **Personalizada**
3. Durante la instalación, anotar el **nombre de la instancia** que se asigne  
   (por ejemplo: `MIEQUIPO\SQLEXPRESS` o `MIEQUIPO\MSSQLSERVER`)
4. Instalar también **SSMS** (SQL Server Management Studio):  
   https://aka.ms/ssmsfullsetup  
   o **Azure Data Studio**: https://aka.ms/azuredatastudio

---

## Paso 5 — Instalar ODBC Driver y drivers sqlsrv para PHP

### 5.1 — ODBC Driver for SQL Server
1. Descargar **Microsoft ODBC Driver 18 for SQL Server**:  
   https://aka.ms/downloadmsodbcsql
2. Instalar el `.msi` correspondiente a x64

### 5.2 — Drivers sqlsrv para PHP
1. Descargar desde:  
   https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
2. Extraer el archivo descargado
3. Identificar las DLL correctas según tu versión de PHP (8.1 TS x64):
   - `php_sqlsrv_81_ts_x64.dll`
   - `php_pdo_sqlsrv_81_ts_x64.dll`
4. Copiar ambas DLL a `C:\php\ext\`
5. Agregar al final de `C:\php\php.ini`:
   ```ini
   extension=php_sqlsrv_81_ts_x64
   extension=php_pdo_sqlsrv_81_ts_x64
   ```
6. Verificar:
   ```powershell
   php -m
   ```
   Deben aparecer `sqlsrv` y `pdo_sqlsrv` en la lista.

---

## Paso 6 — Clonar el repositorio

```powershell
cd C:\Users\TU_USUARIO\Documents
git clone <URL_DEL_REPOSITORIO> plataformawebsemujeres-master
cd plataformawebsemujeres-master
```

---

## Paso 7 — Instalar dependencias PHP

```powershell
cd "C:\Users\TU_USUARIO\Documents\plataformawebsemujeres-master"
composer install
```

Esto descargará todos los paquetes declarados en `composer.json` (Slim, Eloquent, mPDF, PHPMailer, etc.).

---

## Paso 8 — Crear la base de datos en SQL Server

Abrir SSMS o Azure Data Studio, conectarse a tu instancia local y ejecutar:

```sql
CREATE DATABASE plataformawebsemujeres;
```

---

## Paso 9 — Ejecutar los scripts SQL

En SSMS / Azure Data Studio, conectarse a la base `plataformawebsemujeres` y ejecutar en este orden:

1. `database\sqlserver\schema_core.sql` — crea todas las tablas
2. `database\sqlserver\Insert_core_desarollo.sql` — inserta datos de prueba

---

## Paso 10 — Configurar la conexión a la base de datos

Abrir el archivo `config\dbSettings.php` y ajustar el valor de `$db_host` con el nombre real de tu instancia SQL Server:

```php
<?php
$db_driver = 'sqlsrv';
$db_host = 'NOMBRE_DE_TU_EQUIPO\NOMBRE_INSTANCIA';  // <-- cambiar esto
$db_port = '';
$db_user = '';
$db_pass = '';
$db_name = 'plataformawebsemujeres';
$db_trusted_connection = true;   // usa Windows Authentication
$db_encrypt = false;
$db_trust_server_certificate = false;
```

### Cómo saber el nombre de tu instancia

```powershell
# Listar instancias de SQL Server instaladas
Get-Service -Name 'MSSQL*' | Select-Object Name, DisplayName
```

El valor que necesitas tiene el formato `NOMBRE_EQUIPO\NOMBRE_INSTANCIA`.  
Para obtener el nombre del equipo:

```powershell
hostname
```

Ejemplo resultado: `$db_host = 'MIPC\SQLEXPRESS';`

> **Nota sobre Windows Authentication (`trusted_connection = true`):**  
> El sistema se conecta usando el usuario de Windows actual. El usuario debe tener permisos en la base de datos. Si prefieres usuario/contraseña SQL, cambiar `$db_trusted_connection = false` y llenar `$db_user` y `$db_pass`.

---

## Paso 11 — Levantar el servidor local

```powershell
cd "C:\Users\TU_USUARIO\Documents\plataformawebsemujeres-master"
php -S localhost:8080 -t public
```

---

## Paso 12 — Abrir en el navegador

```
http://localhost:8080/semujeres/public/iniciarSesion
```

---

## Verificación rápida de todo el entorno

Ejecutar en PowerShell antes de levantar el servidor:

```powershell
# PHP instalado
php -v

# Extensiones necesarias presentes
php -m | Select-String -Pattern 'sqlsrv|pdo_sqlsrv|mbstring|openssl|fileinfo'

# Composer instalado
composer -V

# Conexión a SQL Server (opcional — requiere sqlcmd instalado)
sqlcmd -S "NOMBRE_EQUIPO\INSTANCIA" -E -Q "SELECT @@VERSION"
```

---

## Solución de problemas frecuentes

| Error | Causa probable | Solución |
|---|---|---|
| `php` no reconocido | PHP no está en el PATH | Agregar `C:\php` al PATH y reiniciar PowerShell |
| `Could not find driver` | sqlsrv no habilitado en php.ini | Verificar Paso 5 |
| `Unable to connect` en la app | Nombre de instancia incorrecto | Revisar `dbSettings.php` con el nombre real |
| `SSL Security error` | Versión de ODBC Driver incompatible | Instalar ODBC Driver 18 y agregar `$db_trust_server_certificate = true` en `dbSettings.php` si el servidor es local |
| `Class not found` en Composer | `vendor/` ausente | Ejecutar `composer install` |
| Página en blanco o 500 | Error PHP oculto | Revisar `logs/` o activar `display_errors=On` en `php.ini` temporalmente |

---

## Carpetas que deben existir y tener permisos de escritura

```
app/uploadFichas/
app/uploadFiles/
app/uploadImg/
logs/
var/cache/
```

Si no existen, crearlas:

```powershell
New-Item -ItemType Directory -Force -Path "app\uploadFichas","app\uploadFiles","app\uploadImg","logs","var\cache"
```
