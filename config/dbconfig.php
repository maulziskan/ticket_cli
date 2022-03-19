<?php

date_default_timezone_set("Asia/Jakarta");

class Database
{
    // requirement for mysql db
    private $host = "localhost";
    private $user = "pengguna";
    private $pasw = "katasandi";
    public $conn;

    public function create_db()
    {

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=".$this->host,$this->user,$this->pasw);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $create_database = "CREATE DATABASE `ticket_cli` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
            USE `ticket_cli`;
            
            CREATE TABLE `ticket` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `event_id` varchar(25) DEFAULT NULL,
              `ticket_code` text DEFAULT NULL,
              `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = available , 1 = claimed',
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `ticket_code` (`ticket_code`) USING HASH
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            
            INSERT INTO `ticket` (`id`, `event_id`, `ticket_code`, `status`, `created_at`) VALUES
            (1,	'1',	'DTKExFppQ3',	0,	'2022-03-19 09:41:54'),
            (2,	'1',	'DTK5OGUCCG',	0,	'2022-03-19 09:41:54'),
            (3,	'1',	'DTKJBLe45n',	0,	'2022-03-19 09:41:54'),
            (4,	'1',	'DTKrp1xAfg',	0,	'2022-03-19 09:41:54'),
            (5,	'1',	'DTKthbmcv5',	0,	'2022-03-19 09:41:54'),
            (6,	'1',	'DTKQl8zlIJ',	0,	'2022-03-19 09:41:54'),
            (7,	'1',	'DTKlAj4dNc',	0,	'2022-03-19 09:41:54'),
            (8,	'1',	'DTKlRRZD0Y',	0,	'2022-03-19 09:41:54'),
            (9,	'1',	'DTKN7SKEMO',	0,	'2022-03-19 09:41:54'),
            (10,	'1',	'DTKmQiJV1U',	0,	'2022-03-19 09:41:54');
            
            CREATE TABLE `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` text NOT NULL,
            `password` text NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            
            INSERT INTO `users` (`id`, `username`, `password`) VALUES
            (1,	'admin',	'900150983cd24fb0d6963f7d28e17f72');";

            $this->conn->exec($create_database);
            $hasil = "Database created successfully!";

        }catch(PDOException $e){
            $hasil = "Connection error: ".$e->getMessage();
        }

        return $hasil;
    }

    public function connect_db($dbname)
    {

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$dbname,$this->user,$this->pasw);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Connection error: ".$e->getMessage();
        }

        return $this->conn;
    }
}