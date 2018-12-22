<?php
error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","1");

require_once(dirname(__DIR__) . '/config/config.php');
require_once(dirname(__DIR__) . '/src/lib/Client.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

/** @var Client $client */
$client = new Client();
$controllerName = $client->getControllerName();

$ss = dirname(__DIR__).'/src/controller/'.$controllerName.'.php';

try {
    if (file_exists(dirname(__DIR__) . '/src/controller/' . $controllerName . '.php')) {
        require_once dirname(__DIR__) . '/src/controller/' . $controllerName . '.php';

        $controllerName = str_replace("/", "\\", $controllerName);
        if (!class_exists($controllerName, false)) {
            header("HTTP/1.0 404 Not Found");
        }
        /** @var MainController $controller */
        $controller = new $controllerName($client);
        $controller->handleDefault();
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

die();