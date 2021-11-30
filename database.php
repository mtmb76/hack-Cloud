<?php
// used to connect to the database
$host = "127.0.0.1";
$db_name = "lfgr2";
$username = "root";
$password = "";
 
try {
    $GLOBALS['con'] = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}
 
// show error
catch(PDOException $exception){
    echo "Erro de conexão: " . $exception->getMessage();
}
