<?php

class Database{

    public function connectionDB(){
            // $db_host = "127.0.0.1";
            // $db_user = "vlk";
            // $db_password = "Admin123";
            // $db_name = "orders";

            $db_host = "sql6.webzdarma.cz";
            $db_user = "vlksamotarwz2218";
            $db_password = "H:3;=EICr16jx;r@Q!xz";
            $db_name = "vlksamotarwz2218";

            $connection = "mysql:host=" . $db_host . ";dbname=" . $db_name . ";charset=utf8";

            try{
                $db = new PDO($connection, $db_user, $db_password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            }catch(PDOException $e){
                echo $e->getMessage();
                exit;
            }
    }

}

?>