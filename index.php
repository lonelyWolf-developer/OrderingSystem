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

                <section class="searchDatabase">
                    <form action="./admin/searchContracts.php" method="post">
                        <input type="number" name="filterOrderNumber" placeholder="Číslo objednávky">
                        <select name="filterType">
                            <option value="<?= null ?>">Typ kola</option>
                            <option value="<?= ContractType::Road->value ?>">Silniční</option>
                            <option value="<?= ContractType::XC->value ?>">Cross Country</option>
                            <option value="<?= ContractType::Trail->value ?>">Trail</option>
                            <option value="<?= ContractType::Enduro->value ?>">Enduro</option>
                            <option value="<?= ContractType::Downhill->value ?>">Downhill</option>
                            <option value="<?= ContractType::Gravel->value ?>">Gravel</option>
                            <option value="<?= ContractType::Fatbike->value ?>">Fatbike</option>
                        </select>
                        <input type="date" name="filterDate">
                        <input type="time" name="filterTime">
                        <input type="text" name="filterUser" placeholder="Zadávající uživatel">                        
                        <select name="filterStatus">
                            <option value="<?= null ?>">Status zakázky</option>
                            <option value="<?= ContractStatus::Entered->value ?>">Zadáno</option>
                            <option value="<?= ContractStatus::Retrieved->value ?>">Vykladněno</option>
                            <option value="<?= ContractStatus::Cancelled->value ?>">Zrušeno</option>
                        </select>
                        <input type="text" name="filterChangingUser" placeholder="Ukončující uživatel">
                        <input type="submit" value="Filtrovat">
                        <a href="/OrderingSystem">Vyčistit filtr</a>
                    </form>
                </section>
                
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