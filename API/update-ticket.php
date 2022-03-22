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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

// validasi cek header autherization
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    http_response_code(401);
    exit("Not Authenticated");
}

// get token jwt 
$token = $utils->get_token_bearer();
$cek_token = $jwt->cek_jwt($token);

// validasi cek bearer token
if ($cek_token) {

    // validasi parameter
    if (isset($_POST['event_id']) && isset($_POST['ticket_code']) && isset($_POST['status'])) {
        $event_id = htmlspecialchars($_POST['event_id']);
        $ticket_code = htmlspecialchars($_POST['ticket_code']);
        $status = htmlspecialchars($_POST['status']);

        // cek ticket code
        $cek_ticket = $query->get_filter_data($db, "ticket", "event_id", $event_id, "ticket_code", $ticket_code);

        // validasi hasil cek ticket
        if (!empty($cek_ticket)) {
            $tgl_update = date("Y-m-d H:i:s");
            $hasil_update = $query->update_data($db, "ticket", "status", $status, "updated_at", $tgl_update, "", "", "event_id", $event_id, "ticket_code", $ticket_code);

            if($hasil_update) {
                echo json_encode(["ticket_code"=>$cek_ticket['ticket_code'], "status"=>$status == 0 ? 'available' : 'claimed', "updated_at"=>$tgl_update]);
            }
        } else {
            exit(json_encode(["message"=>"Ticket Code tidak terdaftar"]));
        }
    }
} else {
    exit(json_encode(["message"=>"Token JWT Anda tidak valid"]));
}