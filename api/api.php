<?php
error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","1");

require_once(dirname(__DIR__) . '/config/config.php');
require_once(dirname(__DIR__) . '/src/Request.php');


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");



/** @var Request $request */
$request = new Request();
$action  = $_POST['action'];

if (!empty($_POST['id'])) {
    $id = (int)$_POST['id'];
    switch ($action) {
        case 'approve':
            break;
        case 'decline':
            break;
        default:
            $response = $request->read($id);
        //read all users
        break;
    }
}



// set response code - 200 OK
http_response_code(200);

// show products data in json format
echo json_encode($response);