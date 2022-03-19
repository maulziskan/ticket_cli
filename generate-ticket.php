<?php
require_once("config/dbconfig.php");
require_once("Utils/utils.php");
require_once("Utils/query.php");

$database = new Database();
$db = $database->connect_db("ticket_cli");

$utils = new Utils();
$query = new Query();
$table_name = "ticket";

// validasi argumen command line
if ($argc > 1) {

    // validasi jumlah argumen yang diinput di command line
    if (count($argv) > 2) {
        $event_id = htmlspecialchars($argv[1]);
        $jumlah_tiket = htmlspecialchars($argv[2]);
        $list_field = ["event_id", "ticket_code", "created_at"];
        $list_ticket = [];

        for($a = 0; $a < $jumlah_tiket; $a++) {
            // generate ticket code
            $ticket_code = $utils->kodeTicket("DTK", 7);

            // cek kode tiket di database
            $cek_tiket = $query->get_filter_data($db, $table_name, "ticket_code", $ticket_code);
            
            if (empty($cek_tiket)) {
                array_push($list_ticket, [":event_id"=>$event_id, ":ticket_code"=>$ticket_code, ":created_at"=>date("Y-m-d H:i:s")]);
            }
        }

        $hasil_insert = $query->insert_data($db, $table_name, $list_field, $list_ticket);

        if ($hasil_insert > 0) {
            echo "Generate tiket sebanyak ".$jumlah_tiket." Berhasil!";
        } else {
            echo "Gagal Generate ticket";
        }

    } else {
        exit("Mohon input argumen event_id dan jumlah tiket yang akan digenerate");
    }
    
} else {
    exit("Parameter Kosong");
}
