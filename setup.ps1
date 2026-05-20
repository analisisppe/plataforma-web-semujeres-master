# ============================================================
#  setup.ps1 — Instalacion de entorno para equipo limpio
#  Plataforma Web SE Mujeres
#  Ejecutar como Administrador en PowerShell
# ============================================================

$ErrorActionPreference = "Stop"
$phpDir   = "C:\php"
$phpIni   = "$phpDir\php.ini"
$phpExt   = "$phpDir\ext"
$phpVer   = "8.1"          # version a instalar

function titulo($msg) {
    Write-Host "`n========================================" -ForegroundColor Cyan
    Write-Host "  $msg" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
}

function ok($msg)    { Write-Host "[OK] $msg" -ForegroundColor Green }
function info($msg)  { Write-Host "[..] $msg" -ForegroundColor Yellow }
function fallo($msg) { Write-Host "[!!] $msg" -ForegroundColor Red }

# ------------------------------------------------------------
# 0. Verificar que se ejecuta como Administrador
# ------------------------------------------------------------
if (-not ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole(
    [Security.Principal.WindowsBuiltInRole]::Administrator)) {
    fallo "Ejecuta este script como Administrador (clic derecho -> Ejecutar con PowerShell como administrador)"
    exit 1
}

# ------------------------------------------------------------
# 1. Instalar PHP
# ------------------------------------------------------------
titulo "1. PHP $phpVer Thread Safe x64"

if (Test-Path "$phpDir\php.exe") {
    ok "PHP ya esta instalado en $phpDir"
} else {
    info "Descargando PHP $phpVer TS x64..."
    $phpZip = "$env:TEMP\php.zip"

    # Obtener la URL exacta desde windows.php.net
    $phpPage = Invoke-WebRequest "https://windows.php.net/download/" -UseBasicParsing
    $phpUrl  = ($phpPage.Links.href | Where-Object { $_ -match "php-${phpVer}\.\d+-Win32-vs16-x64\.zip$" } | Select-Object -First 1)

    if (-not $phpUrl) {
        fallo "No se encontro URL de descarga automatica. Descarga manualmente desde https://windows.php.net/download/"
        fallo "Extrae en C:\php y vuelve a ejecutar este script."
        exit 1
    }

    $fullUrl = if ($phpUrl -match "^http") { $phpUrl } else { "https://windows.php.net$phpUrl" }
    info "URL: $fullUrl"
    Invoke-WebRequest $fullUrl -OutFile $phpZip -UseBasicParsing
    Expand-Archive $phpZip -DestinationPath $phpDir -Force
    Remove-Item $phpZip
    ok "PHP extraido en $phpDir"
}

# Agregar PHP al PATH del sistema si no esta
$syspath = [System.Environment]::GetEnvironmentVariable("Path", "Machine")
if ($syspath -notlike "*$phpDir*") {
    [System.Environment]::SetEnvironmentVariable("Path", "$syspath;$phpDir", "Machine")
    $env:Path = "$env:Path;$phpDir"
    ok "PHP agregado al PATH del sistema"
} else {
    ok "PHP ya estaba en el PATH"
}

# ------------------------------------------------------------
# 2. Configurar php.ini
# ------------------------------------------------------------
titulo "2. Configuracion de php.ini"

if (-not (Test-Path $phpIni)) {
    Copy-Item "$phpDir\php.ini-development" $phpIni
    info "Creado php.ini desde php.ini-development"
}

$extensiones = @("fileinfo", "mbstring", "openssl", "pdo", "curl")
foreach ($ext in $extensiones) {
    $pattern = ";extension=$ext"
    $replace  = "extension=$ext"
    $content  = Get-Content $phpIni -Raw
    if ($content -match [regex]::Escape($replace)) {
        ok "extension=$ext ya habilitada"
    } elseif ($content -match [regex]::Escape($pattern)) {
        $content = $content -replace [regex]::Escape($pattern), $replace
        Set-Content $phpIni $content -NoNewline
        ok "extension=$ext habilitada"
    } else {
        ok "extension=$ext no encontrada en php.ini (puede no ser necesaria en esta version)"
    }
}

# ------------------------------------------------------------
# 3. ODBC Driver 18 for SQL Server
# ------------------------------------------------------------
titulo "3. Microsoft ODBC Driver 18 for SQL Server"

$odbcInstalled = Get-WmiObject -Class Win32_Product -ErrorAction SilentlyContinue |
    Where-Object { $_.Name -like "*ODBC Driver*SQL Server*" }

if ($odbcInstalled) {
    ok "ODBC Driver ya instalado: $($odbcInstalled.Name)"
} else {
    info "Descargando ODBC Driver 18..."
    $odbcUrl  = "https://go.microsoft.com/fwlink/?linkid=2249004"
    $odbcMsi  = "$env:TEMP\msodbcsql.msi"
    Invoke-WebRequest $odbcUrl -OutFile $odbcMsi -UseBasicParsing
    info "Instalando ODBC Driver 18 (silencioso)..."
    Start-Process msiexec.exe -ArgumentList "/i `"$odbcMsi`" /quiet /norestart IACCEPTMSODBCSQLLICENSETERMS=YES" -Wait
    Remove-Item $odbcMsi
    ok "ODBC Driver 18 instalado"
}

# ------------------------------------------------------------
# 4. Drivers sqlsrv para PHP
# ------------------------------------------------------------
titulo "4. Drivers sqlsrv / pdo_sqlsrv para PHP"

$dllSql    = "php_sqlsrv_81_ts_x64.dll"
$dllPdo    = "php_pdo_sqlsrv_81_ts_x64.dll"
$dllTarget = $phpExt

if ((Test-Path "$dllTarget\$dllSql") -and (Test-Path "$dllTarget\$dllPdo")) {
    ok "DLLs sqlsrv ya presentes en $dllTarget"
} else {
    info "Descargando drivers sqlsrv desde GitHub releases..."
    $relUrl  = "https://api.github.com/repos/microsoft/msphpsql/releases/latest"
    $release = Invoke-RestMethod $relUrl
    $asset   = $release.assets | Where-Object { $_.name -match "Windows\.zip$" } | Select-Object -First 1

    if (-not $asset) {
        fallo "No se pudo descargar automaticamente. Descarga desde https://github.com/microsoft/msphpsql/releases"
        fallo "Copia php_sqlsrv_81_ts_x64.dll y php_pdo_sqlsrv_81_ts_x64.dll en $dllTarget y vuelve a ejecutar."
        exit 1
    }

    $zipPath = "$env:TEMP\sqlsrv_drivers.zip"
    $unzipDir = "$env:TEMP\sqlsrv_drivers"
    Invoke-WebRequest $asset.browser_download_url -OutFile $zipPath -UseBasicParsing
    Expand-Archive $zipPath -DestinationPath $unzipDir -Force

    $found = Get-ChildItem $unzipDir -Recurse -Filter $dllSql | Select-Object -First 1
    if ($found) {
        Copy-Item $found.FullName $dllTarget -Force
        Copy-Item (Join-Path $found.DirectoryName $dllPdo) $dllTarget -Force
        ok "DLLs copiadas a $dllTarget"
    } else {
        fallo "No se encontraron las DLLs para PHP 8.1 TS x64 en el paquete descargado."
        exit 1
    }

    Remove-Item $zipPath, $unzipDir -Recurse -Force
}

# Agregar al php.ini si no estan
$iniContent = Get-Content $phpIni -Raw
foreach ($dll in @($dllSql, $dllPdo)) {
    $entry = "extension=$dll"
    if ($iniContent -notmatch [regex]::Escape($entry)) {
        Add-Content $phpIni "`r`n$entry"
        ok "Agregado $entry a php.ini"
    } else {
        ok "$entry ya presente en php.ini"
    }
}

# ------------------------------------------------------------
# 5. Instalar Composer
# ------------------------------------------------------------
titulo "5. Composer"

if (Get-Command composer -ErrorAction SilentlyContinue) {
    ok "Composer ya instalado: $(composer -V 2>&1 | Select-Object -First 1)"
} else {
    info "Descargando instalador de Composer..."
    $composerSetup = "$env:TEMP\ComposerSetup.exe"
    Invoke-WebRequest "https://getcomposer.org/Composer-Setup.exe" -OutFile $composerSetup -UseBasicParsing
    info "Instalando Composer (silencioso)..."
    Start-Process $composerSetup -ArgumentList "/VERYSILENT /NORESTART /PHP=`"$phpDir\php.exe`"" -Wait
    Remove-Item $composerSetup
    # Refrescar PATH
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path", "Machine") + ";" +
                [System.Environment]::GetEnvironmentVariable("Path", "User")
    ok "Composer instalado"
}

# ------------------------------------------------------------
# 6. Instalar dependencias del proyecto
# ------------------------------------------------------------
titulo "6. composer install (dependencias del proyecto)"

$proyectoDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Push-Location $proyectoDir

if (Test-Path "vendor\autoload.php") {
    ok "vendor/ ya existe, ejecutando composer install para verificar..."
}

composer install --no-interaction
ok "Dependencias instaladas"
Pop-Location

# ------------------------------------------------------------
# 7. Crear carpetas requeridas
# ------------------------------------------------------------
titulo "7. Carpetas de uploads y cache"

$carpetas = @("app\uploadFichas", "app\uploadFiles", "app\uploadImg", "logs", "var\cache")
foreach ($c in $carpetas) {
    $ruta = Join-Path $proyectoDir $c
    if (-not (Test-Path $ruta)) {
        New-Item -ItemType Directory -Path $ruta -Force | Out-Null
        ok "Creada: $c"
    } else {
        ok "Ya existe: $c"
    }
}

# ------------------------------------------------------------
# 8. Verificacion final
# ------------------------------------------------------------
titulo "8. Verificacion final"

Write-Host ""
php -v
Write-Host ""

$modulos = php -m 2>&1
$requeridos = @("sqlsrv", "pdo_sqlsrv", "mbstring", "openssl", "fileinfo", "pdo")
foreach ($m in $requeridos) {
    if ($modulos -match "(?i)^$m$") {
        ok "Modulo PHP: $m"
    } else {
        fallo "Modulo PHP FALTANTE: $m"
    }
}

Write-Host ""
Write-Host "============================================================" -ForegroundColor Green
Write-Host "  Entorno listo." -ForegroundColor Green
Write-Host ""
Write-Host "  Siguiente paso:" -ForegroundColor Green
Write-Host "  1. Edita config\dbSettings.php con el nombre de tu instancia SQL Server" -ForegroundColor Green
Write-Host "  2. Crea la BD y ejecuta los scripts SQL (ver INSTALACION.md)" -ForegroundColor Green
Write-Host "  3. Levanta el servidor:" -ForegroundColor Green
Write-Host "     php -S localhost:8080 -t public" -ForegroundColor White
Write-Host "  4. Abre: http://localhost:8080/semujeres/public/iniciarSesion" -ForegroundColor White
Write-Host "============================================================" -ForegroundColor Green
