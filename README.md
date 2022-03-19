# ticket_cli

Project ini dibuat dengan php native dan ditambahkan authentication bearer token jwt untuk REST API nya.

Requirements:
1. Mysql > 5.5.5-10.4.21-MariaDB.
2. PHP > 5.6.

Panduan Penggunaan :
1. Setelah clone project, buka project ini melalui terminal/CMD.
2. ketik dan execute : php start.php <username mysql> <password msyql>, contoh : php start.php root p@5sW0RdMy5qL.
3. Apabila berhasil create database akan muncul respon seperti ini  "Database created successfully!" setelah execute langkah No.2.
4. Jika Langkah No. 3 Gagal, replace username dan password di file config/dbconfig.php dan import manual database, file sql di dalam folder database.
5. Untuk proses generate ticket execute php generate-ticket.php <event_id> <jumlah_tiket>, contoh : php generate-ticket.php 1 1000.
6. Untuk API get status ticket, hit API untuk get token jwt terlebih dahulu. Dengan user default admin dan password abc.
7. Untuk API update status ticket, masih menggunakan token jwt. Untuk ubah status input parameter, 1 untuk claimed dan 0 untuk available.
8. Untuk lebih rinci terkait API, dapat mengimport collection postman dari folder postman.
