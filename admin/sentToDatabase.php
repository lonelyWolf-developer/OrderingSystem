<?php

session_start();

require "../classes/Contract.php";
require "../classes/Database.php";
require "../classes/Url.php";
require "../classes/Message.php";
require "../classes/Email.php";
require "../classes/User.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $message = new Message();
    
    $contract = new Contract();

    $contract->orderNumber = $_POST["orderNumber"];
    $contract->type = $_POST["type"];
    $contract->date = $_POST["date"];
    $contract->time = $_POST["time"];
    $contract->user = $_POST["user"];
    $contract->status = ContractStatus::Entered->value;

    $database = new Database();
    $connection = $database->connectionDB();

    $user = new User();
    $user->id = $contract->user;
    $user->email = $user->getUserEmail($connection, $user->id);

    $email = new Email();
    $email->subject = "Nová objednávka.";
    $email->message = "Uživatel " . $user->email . " vytvořil objednávku " . $contract->orderNumber . ".";

    if(!$contract->checkExistContract($connection, $contract->orderNumber)){
        if ($contract->createContract($connection, $contract->orderNumber, $contract->type, $contract->date, $contract->time, $contract->user, $contract->status)) {
            $message->createMessageSession("Objednávka byla vytvořena", MessageType::Success->value);
            
            $email->sentEmail($email);
            
            Url::redirectUrl("/OrderingSystem");
        } else {
            $message->createMessageSession("Jejda, něco se pokazilo.", MessageType::Failure->value);
            Url::redirectUrl("/OrderingSystem");
        }
    }else{
        $message->createMessageSession("Tato zakázka již byla zadána.", MessageType::Failure->value);
        Url::redirectUrl("/OrderingSystem");
    }

}
?>