<?php

session_start();

require "../classes/Database.php";
require "../classes/Contract.php";
require "../classes/Url.php";
require "../classes/User.php";
require "../classes/Auth.php";
require "../classes/Message.php";

$database = new Database();
$connection = $database->connectionDB();

$message = new Message();

$contract = new Contract();

if($_SERVER["REQUEST_METHOD"] === "POST" and Auth::isLoggedIn()){
    $contract->id = $_POST["Id"];
    $contract->status = $_POST["Status"];
    $contract->changingUser = $_POST["ChangingUser"];

    if($contract->changeStatus($connection, $contract->id, $contract->status, $contract->changingUser)){
        $message->createMessageSession("Objednávka byla úspěšně smazána.", MessageType::Success->value);
        Url::redirectUrl("/OrderingSystem");
    }else{
        $message->createMessageSession("Jejda, něco se nepovedlo.", MessageType::Failure->value);
        Url::redirectUrl("/OrderingSystem");
    }
}else{
    Url::redirectUrl("/OrderingSystem");
}


?>