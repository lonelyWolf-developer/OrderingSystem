<?php

    session_start();

    require "../classes/Auth.php";
    require "../classes/User.php";
    require "../classes/Url.php";
    require "../classes/Message.php";
    require "../classes/Database.php";

    if(!Auth::isLoggedIn()){
        Url::redirectUrl("");
    }

    $message = new Message();
    $message = $message->createMessage(false);

    $database = new Database();
    $connection = $database->connectionDB();

    $user = new User();
    $user->id = $_SESSION["logged_user_id"];
    $user->email = $user->getUserEmail($connection, $user->id);
    $user->role = $user->getUserRole($connection, $user->id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/queryes.css">
    <title>Můj účet</title>
</head>
<body>
    
    <?php require "../assets/userMessage.php" ?>

    <?php require "../assets/adminHeader.php" ?>

    <main>
        <div class="container">
            <section class="userButtons">
                <h1>Můj účet</h1>
                
                <?php if($user->role == Roles::Admin->value): ?>
                    <a href="./addUser.php" class="userButton">Vytvořit uživatele</a>
                    <a href="./allUsers.php" class="userButton">Všichni uživatelé</a>
                <?php endif; ?>
                <a href="./changePasswordForm.php" class="userButton">Změnit heslo</a>
                <a href="./logOut.php" class="userButton">Odhlásit se</a>
            </section>
        </div>
    </main>

    <?php require "../assets/footer.php" ?>

    <script src="../js/messageBox.js"></script>
</body>
</html>