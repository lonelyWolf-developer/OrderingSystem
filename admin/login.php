<?php

    require "../classes/Database.php";
    require "../classes/Url.php";
    require "../classes/User.php";
    require "../classes/Message.php";

    session_start();

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $message = new Message();
        
        $database = new Database();
        $conn = $database->connectionDB();
        
        $user = new User();
        $user->email = $_POST["email"];
        $user->password = $_POST["password"];        

        if(User::authentication($conn, $user->email, $user->password)){
            $user->id = $user->getUserId($conn, $user->email);

            // Zabraňuje provedení tzv. fixation attack.
            session_regenerate_id(true);

            // Předává informaci, zda je uživatel přihlášen
            $_SESSION["is_logged_in"] = true;
            
            // Předává id přihlášeného uživatele
            $_SESSION["logged_user_id"] = $user->id;

            // Nastavení role uživatele
            $_SESSION["role"] = $user->getUserRole($conn, $user->id);

            $message->createMessageSession("Přihlášení proběhlo úspěšně.", MessageType::Success->value);
            Url::redirectUrl("");
        }else{
            $message->createMessageSession("Zkus to znovu.", MessageType::Failure->value);
            Url::redirectUrl("");
        }
    }

?>