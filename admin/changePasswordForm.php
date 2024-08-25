<?php

session_start();

require "../classes/User.php";
require "../classes/Database.php";
require "../classes/Auth.php";
require "../classes/Message.php";

if(!Auth::isLoggedIn()){
    Url::redirectUrl("");
}

$database = new Database();
$connection = $database->connectionDB();

$user = new User();
$user->id = $_SESSION["logged_user_id"];
$user->email = $user->getUserEmail($connection, $user->id);
$user->role = $user->getUserRole($connection, $user->id);

$message = new Message();
$message = $message->createMessage(false);

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
    <title>Změna hesla</title>
</head>

<body>   
    <?php require "../assets/userMessage.php" ?>
   
    <?php require "../assets/header.php" ?>

    <main>
        <div class="container">
            <section class="changePassword">
                <h1>Změna hesla</h1>
                <form action="./changePassword.php" method="post">
                    <input type="password" placeholder="Staré heslo" name="oldPassword" required>
                    <input type="password" placeholder="Nové heslo" name="newPassword" id="firstPassword" onkeyup="return validate()" required>
                    <p class="validatePassword" id="validateMessage">Délka hesla musí být alespoň 8 znaků<br>musí obsahovat alespoň jedno velké 
                    a jedno malé písmeno<br>jedno číslo a jeden speciální znak kromě mezer.</p>
                    <input type="password" placeholder="Nové heslo znovu" name="newPasswordAgain" id="secondPassword" oninput="return conform()" required>
                    <p class="validatePassword" id="conformMessage">Hesla se neshodují.</p>
                    <input type="hidden" name="id" value="<?= $user->id ?>">
                    <input type="hidden" name="email" value="<?= $user->email ?>">
                    <input type="submit" value="Změnit">
                </form>
            </section>
        </div>
    </main>

    <?php require "../assets/footer.php" ?>

    <script src="../js/messageBox.js"></script>
    <script src="../js/passwordValidate.js"></script>
</body>

</html>