<?php
header('Content-Type: application/json; charset=utf-8');
require_once("../JWT/jwt.php");
require_once("../config/dbconfig.php");
require_once("../Utils/query.php");

$jwt = new JWT();

$database = new Database();
$db = $database->connect_db("ticket_cli");

$query = new Query();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $get_user = $query->get_filter_data($db, "users", 'username', $username);

    // validasi hasil cek user di database
    if (!empty($get_user)) {
        
        // validasi cek password
        if (md5($password) == $get_user['password']) {
            $headers = ['alg'=>'HS256','typ'=>'JWT'];
            $payload = ['sub'=>'1234567890','name'=>$username, 'exp'=>(time() + 600)];

            $jwt = $jwt->generate_token($headers, $payload);
            echo json_encode(["message"=>"Success", "token"=>$jwt]);

        } else {
            exit(json_encode(["message"=>"Password anda salah"]));
        }
    } else {
        exit(json_encode(["message"=>"Username tidak terdaftar"]));
    }


}