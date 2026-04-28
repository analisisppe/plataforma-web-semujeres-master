<?php
require __DIR__ . '/../config/dbSettings.php';
require __DIR__ . '/../vendor/autoload.php';


use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();
$driver = $db_driver ?? 'mysql';

$connection = [
    "driver" => $driver,
    "host" => $db_host,
    "username" => $db_user,
    "password" => $db_pass,
    "database" => $db_name,
    "prefix" => "",
];

if (!empty($db_port)) {
    $connection["port"] = $db_port;
}

if ($driver === 'mysql') {
    $connection["charset"] = 'utf8';
    $connection["collation"] = 'utf8_general_ci';
}

if ($driver === 'sqlsrv') {
    $connection["charset"] = 'utf8';

    if (!empty($db_trusted_connection)) {
        $connection["trusted_connection"] = true;
        unset($connection["username"], $connection["password"]);
    }

    if (isset($db_encrypt)) {
        $connection["encrypt"] = $db_encrypt ? 'yes' : 'no';
    }

    if (isset($db_trust_server_certificate)) {
        $connection["trust_server_certificate"] = $db_trust_server_certificate ? 'yes' : 'no';
    }
}

$capsule->addConnection($connection);

$capsule->setAsGlobal();

$capsule->bootEloquent();

