<?php
$username = $argv[1];
$password = $argv[2];

$file_dbconfig = 'config/dbconfig.php';
$file = file_get_contents($file_dbconfig);

// ubah username mysql di file dbconfig.php
$pattern = '/pengguna/i';
$isi_file = preg_replace($pattern, 'root', $file);

// ubah password mysql di file dbconfig.php
$pattern_dua = '/katasandi/i';
$isi_file1 = preg_replace($pattern_dua, 'KataRahasia', $isi_file);

// ubah isi file dbconfig.php
file_put_contents($file_dbconfig, $isi_file1);

require_once("config/dbconfig.php");
$database = new Database();
echo $database->create_db();

