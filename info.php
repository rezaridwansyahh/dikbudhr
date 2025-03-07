<?php 

$conn_string = "host=118.98.234.198 port=5432 dbname=db_dikbudhr user=postgres password=p0stgr3s";
$dbconn4 = pg_connect($conn_string) or die('connection failed');
echo "berhasil";
die();
phpinfo();