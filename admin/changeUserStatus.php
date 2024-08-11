<?php

session_start();

require "../classes/Database.php";
require "../classes/Message.php";
require "../classes/Url.php";
require "../classes/User.php";
require "../classes/Auth.php";

$database = new Database();
$connection = $database->connectionDB();

$message = new Message();

if($_SERVER["REQUEST_METHOD"] === "POST" and AUTH::checkRole(Roles::Admin->value)){
    $tUser = new User();
    $tUser->id = $_SESSION["logged_user_id"];
    
    $user = new User();
    $user->id = $_POST["id"];
    $user->status = $_POST["status"];

    $returnUrl = "/OrderingSystem/admin/allUsers.php";
    $returnQuery = $_POST["returnQuery"];

    if($returnQuery != ""){
        $returnUrl = $returnUrl . $returnQuery;
    }

    if($tUser->id != $user->id){
        if($user->changeUserStatus($connection, $user->status, $user->id)){
            $message->createMessageSession("Status uživatele byl změněn.", MessageType::Success->value);
            Url::redirectUrl($returnUrl);
        }else{
            $message->createMessageSession("Něco se nepovedlo.", MessageType::Failure->value);
            Url::redirectUrl($returnUrl);
        } 
    }else{
        Url::redirectUrl($returnUrl);
    }

}else{
    Url::redirectUrl("/OrderingSystem");
}

?>