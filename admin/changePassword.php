<?php

session_start();

require "../classes/Auth.php";
require "../classes/Database.php";
require "../classes/Url.php";
require "../classes/User.php";
require "../classes/Message.php";

if($_SERVER["REQUEST_METHOD"] === "POST" and Auth::isLoggedIn()){
    $message = new Message();
    
    $database = new Database();
    $connection = $database->connectionDB();

    $user = new User();
    $user->id = $_POST["id"];
    $user->email = $_POST["email"];
    $user->password = $_POST["oldPassword"];

    $newPassword = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);

    if(User::authentication($connection, $user->email, $user->password) and $user->updatePassword($connection, $user->id, $newPassword)){
        $message->createMessageSession("Heslo bylo úspěšně změněno.", MessageType::Success->value);
        Url::redirectUrl("/OrderingSystem/admin/userAccount.php");      
    }else{
        $message->createMessageSession("Zkus to znovu.", MessageType::Failure->value);
        Url::redirectUrl("/OrderingSystem/admin/changePasswordForm.php");
    }


}else{
    Url::redirectUrl("/OrderingSystem");
}

?>