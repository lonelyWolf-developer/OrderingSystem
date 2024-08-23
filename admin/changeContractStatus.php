<?php

session_start();

require "../classes/Database.php";
require "../classes/Contract.php";
require "../classes/Url.php";
require "../classes/User.php";
require "../classes/Auth.php";
require "../classes/Message.php";
require "../classes/Email.php";

$database = new Database();
$connection = $database->connectionDB();

$message = new Message();

$contract = new Contract();

if($_SERVER["REQUEST_METHOD"] === "POST" and Auth::isLoggedIn()){
    $contract->id = $_POST["Id"];
    $contract->status = $_POST["Status"];
    $contract->changingUser = $_POST["ChangingUser"];

    $returnUrl = "/OrderingSystem";
    $returnQuery = $_POST["ReturnQuery"];

    if($returnQuery != ""){
        $returnUrl = $returnUrl . $returnQuery;
    }

    if($contract->changeStatus($connection, $contract->id, $contract->status, $contract->changingUser)){
        $message->createMessageSession("Objednávka byla úspěšně smazána.", MessageType::Success->value);
        
        $user = new User();
        $email = new Email();

        $contract->orderNumber = $contract->getOrderNumber($connection, $contract->id);

        $user->id = $contract->changingUser;
        $user->email = $user->getUserEmail($connection, $user->id);

        if($contract->status == ContractStatus::Cancelled->value){
            $email->subject = "Zrušení požadavku na vyskladnění.";
            $email->message = "Uživatel " . $user->email . " zrušil požadavek na vyskladnění zakázky " . $contract->orderNumber . ".";
        }else{
            $email->subject = "Vyskladnění zakázky dokončeno.";
            $email->message = "Uživatel " . $user->email . " právě vyskladnil zakázku " . $contract->orderNumber . ".";
        }

        $email->sentEmail($email);        

        Url::redirectUrl($returnUrl);
    }else{
        $message->createMessageSession("Jejda, něco se nepovedlo.", MessageType::Failure->value);
        Url::redirectUrl($returnUrl);
    }
}else{
    Url::redirectUrl("/OrderingSystem");
}


?>