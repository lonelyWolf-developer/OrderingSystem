<?php

session_start();

require "../classes/Contract.php";
require "../classes/Url.php";
require "../classes/Database.php";

$database = new Database();
$connection = $database->connectionDB();

$contract = new Contract();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["Id"];

    if($contract->deleteContract($connection, $id)){
        Url::redirectUrl("");
    }
}

?>