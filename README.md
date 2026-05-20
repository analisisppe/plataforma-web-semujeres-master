# Sistema de Seguimiento de Acciones para el Avance

## Descripción

Sistema desarrollado para el seguimiento y control de acciones relacionadas con el avance de procesos internos.

---

# Requisitos del proyecto

## Versiones y herramientas recomendadas

| Herramienta | Versión recomendada |
|---|---|
| PHP | 8.1 Thread Safe x64 |
| Composer | Última versión estable |
| SQL Server | Express 2019 o 2022 |
| Microsoft ODBC Driver | Versión 18 |
| Drivers PHP para SQL Server | sqlsrv 5.11 o 5.12 |
| Git | Última versión estable |
| SSMS o Azure Data Studio | Cualquier versión reciente |

> El proyecto es compatible con PHP `^7.2` o `^8.0` según las dependencias definidas en Composer.

---

# Extensiones PHP necesarias

Asegúrate de habilitar las siguientes extensiones en el archivo `php.ini`:

- `ext-json`
- `fileinfo`
- `mbstring`
- `openssl`
- `pdo`
- `curl`
- `sqlsrv`
- `pdo_sqlsrv`

---

# Dependencias principales del proyecto

Dependencias definidas en `composer.json`:

- `slim/slim` `4.7`
- `slim/psr7` `1.3`
- `slim/twig-view` `3.0`
- `illuminate/database` `7.30`
- `monolog/monolog` `2.2`
- `mpdf/mpdf` `8.0`
- `php-di/php-di` `6.3`
- `phpmailer/phpmailer` `6.5`
- `phpoffice/phpspreadsheet` `1.18`
- `respect/validation` `2.0`
- `symfony/asset` `5.3`
- `symfony/twig-bridge` `5.3`

---

# Instalación y configuración del proyecto

## 1. Instalar PHP

Instalar **PHP 8.1 Thread Safe x64** y agregarlo al `PATH` del sistema.

---

## 2. Configurar extensiones PHP

Habilitar en el archivo `php.ini` las siguientes extensiones:

```ini
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo
extension=curl
extension=sqlsrv
extension=pdo_sqlsrv
```

---

## 3. Instalar Composer

Descargar e instalar la última versión estable de Composer.

---

## 4. Instalar SQL Server

Instalar alguna de las siguientes versiones:

- SQL Server Express 2019
- SQL Server Express 2022

---

## 5. Instalar ODBC Driver

Instalar Microsoft ODBC Driver 18 para SQL Server.

---

## 6. Instalar drivers PHP para SQL Server

Instalar los drivers compatibles con la versión de PHP utilizada:

- `sqlsrv`
- `pdo_sqlsrv`

---

## 7. Clonar el repositorio

Clonar el proyecto y acceder a la carpeta:

```bash
git clone <URL_DEL_REPOSITORIO>
cd <NOMBRE_DEL_PROYECTO>
```

---

## 8. Instalar dependencias del proyecto

Ejecutar el siguiente comando:

```bash
composer install
```

---

## 9. Crear la base de datos

Crear la base de datos en SQL Server.

---

## 10. Ejecutar scripts SQL

Ejecutar los siguientes scripts:

```txt
schema_core.sql
database/sqlserver/Insert_core_desarollo.sql
```

---

## 11. Configurar conexión a base de datos

Editar el archivo:

```txt
dbSettings.php
```

y configurar los datos correspondientes a la instancia de SQL Server.

---

## 12. Levantar el servidor local

Ejecutar el siguiente comando:

```bash
php -S localhost:8080 -t public
```

---

## 13. Abrir el sistema en el navegador

Ingresar a la siguiente URL:

```txt
http://localhost:8080/semujeres/public/iniciarSesion
```

---
