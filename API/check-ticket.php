<?php
header('Content-Type: application/json; charset=utf-8');

require_once("../JWT/jwt.php");
require_once("../config/dbconfig.php");
require_once("../Utils/query.php");

$jwt = new JWT();

$database = new Database();
$db = $database->connect_db("ticket_cli");

$query = new Query();

// validasi method api
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit();
}

function get_otorisasi_header(){
    $headers = null;

    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }

    return $headers;
}


function get_token_bearer() {
    $headers = get_otorisasi_header();
    
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }else{
        return null;
    }
}

$token = get_token_bearer();

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