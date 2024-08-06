<?php

    session_start();

    require "../classes/Database.php";
    require "../classes/Url.php";
    require "../classes/User.php";
    require "../classes/Auth.php";
    require "../classes/Message.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" and Auth::checkRole(Roles::Admin->value)){
        $database = new Database();
        $connection = $database->connectionDB();

        $message = new Message();

        $admin = new User();

        $createdUser = new User();

        $createdUser->name = $_POST["name"];
        $createdUser->surname = $_POST["surname"];
        $createdUser->email = $_POST["email"];
        $createdUser->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $createdUser->role = $_POST["role"];

        $createdUser->id = $admin->createUser($connection, $createdUser->name, $createdUser->surname, $createdUser->email, $createdUser->password, $createdUser->role);

        if(!empty($createdUser->id)){
            $message->createMessageSession("Uživatel byl úspěšně vytvořen.", MessageType::Success->value);
            Url::redirectUrl("/OrderingSystem/admin/userAccount.php");
        }else{
            $message->createMessageSession("Něco se nepovedlo.", MessageType::Failure->value);
            Url::redirectUrl("/OrderingSystem/admin/userAccount.php");
        }
    }else{
        echo "Nepovolený přístup!!!";
    }

?>