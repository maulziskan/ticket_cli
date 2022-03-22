<?php
header('Content-Type: application/json; charset=utf-8');

require_once("../JWT/jwt.php");
require_once("../config/dbconfig.php");
require_once("../Utils/query.php");
require_once("../Utils/utils.php");

$jwt = new JWT();
$query = new Query();
$utils = new Utils();

$database = new Database();
$db = $database->connect_db("ticket_cli");

// validasi method api
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit();
}

// validasi cek header autherization
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    http_response_code(401);
    exit("Not Authenticated");
}

// get token
$token = $utils->get_token_bearer();
$cek_token = $jwt->cek_jwt($token);

// validasi cek bearer token
if ($cek_token) {

    // validasi parameter
    if (isset($_GET['event_id']) && isset($_GET['ticket_code'])) {
        $event_id = htmlspecialchars($_GET['event_id']);
        $ticket_code = htmlspecialchars($_GET['ticket_code']);

        // cek ticket code
        $cek_ticket = $query->get_filter_data($db, "ticket", "event_id", $event_id, "ticket_code", $ticket_code);

        // validasi hasil cek ticket
        if (!empty($cek_ticket)) {
            echo json_encode(["ticket_code"=>$cek_ticket['ticket_code'], "status"=>$cek_ticket['status'] == 0 ? 'available' : 'claimed']);
        } else {
            exit(json_encode(["message"=>"Ticket Code tidak terdaftar"]));
        }
    }
} else {
    exit(json_encode(["message"=>"Token JWT Anda tidak valid"]));
}