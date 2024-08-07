<?php

session_start();

require "./classes/Contract.php";
require "./classes/Database.php";
require "./classes/Auth.php";
require "./classes/User.php";
require "./classes/Message.php";
require "./classes/Url.php";

$url = Url::getFullUrl();

$database = new Database();
$connection = $database->connectionDB();
$user = new User();

if(Auth::isLoggedIn()){    
    $user->id = $_SESSION["logged_user_id"];
    $user->email = $user->getUserEmail($connection, $user->id);
    $user->role = $user->getUserRole($connection, $user->id);
}else{
    $user->id = null;
}

$contract = new Contract();
$contracts = $contract->getAllContracts($connection);

$message = new Message();
if(Url::readOneQuery($url, "logout") === "0"){
    $message->createMessageSession("Odhlášení proběhlo úspěšně", MessageType::Success->value);
}
$message = $message->createMessage();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Objednávkový systém</title>
</head>

<body>
    <?php require "./assets/userMessage.php" ?>

    <?php require "./assets/header.php" ?>

    <main>
        <div class="container">

            <?php if($user->id != null): ?>

                <?php require "assets/writeOrders.php" ?>

                <?php if($user->role == Roles::Admin->value or $user->role == Roles::Craftsman->value): ?>
                    <?php require "assets/sentOrder.php" ?>
                <?php endif; ?>

            <?php else: ?>

                <?php require "assets/signIn.php" ?>

            <?php endif; ?>

        </div>

    </main>

    <?php require "./assets/footer.php" ?>

    <script src="./js/deleteContract.js"></script>
    <script src="./js/messageBox.js"></script>
</body>

</html>