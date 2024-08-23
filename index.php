<?php

session_start();

require "./classes/Contract.php";
require "./classes/Database.php";
require "./classes/Auth.php";
require "./classes/User.php";
require "./classes/Message.php";
require "./classes/Url.php";

$url = Url::getFullUrl();
$encodeQuery = Url::getEncodeQuery($url);
$query = Url::readAllQueryes($url);

$database = new Database();
$connection = $database->connectionDB();
$user = new User();

$filterOrderNumber = isset($query["filterOrderNumber"]) ? $query["filterOrderNumber"] : "";
$filterType = isset($query["filterType"]) ? $query["filterType"] : null;
$filterDate = isset($query["filterDate"]) ? $query["filterDate"] : "";
$filterTime = isset($query["filterTime"]) ? $query["filterTime"] : "";
$filterUser = isset($query["filterUser"]) ? $query["filterUser"] : "";
$filterStatus = isset($query["filterStatus"]) ? $query["filterStatus"] : "0";
$filterChangingUser = isset($query["filterChangingUser"]) ? $query["filterChangingUser"] : "";

if(Auth::isLoggedIn()){    
    $user->id = $_SESSION["logged_user_id"];
    $user->email = $user->getUserEmail($connection, $user->id);
    $user->role = $user->getUserRole($connection, $user->id);
}else{
    $user->id = null;
}

$contract = new Contract();
$contracts = $contract->getAllContracts($connection, $columns = "*", $orderNumber = $filterOrderNumber, $type = $filterType, $date = $filterDate, $time = $filterTime, $fUser = $filterUser, $status = $filterStatus, $changingUser = $filterChangingUser);
// $contracts = $contract->getAllContracts($connection);

$message = new Message();
if(Url::readOneQuery($url, "logout") === "0"){
    $message->createMessageSession("Odhlášení proběhlo úspěšně", MessageType::Success->value);
}
$message = $message->createMessage();

$statusText = "Zadané";

switch ($filterStatus) {
    case "1":
        $statusText = "Vyskladněné";
        break;
    case "2":
        $statusText = "Zrušené";
}

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
                        <input type="text" name="filterOrderNumber" value="<?= htmlspecialchars($filterOrderNumber) ?>" placeholder="Číslo objednávky">
                        <select name="filterType">
                            <option value="<?= null ?>">Typ kola</option>
                            <option value="<?= ContractType::Road->value ?>" <?php if($filterType === "0") {echo " selected ";} ?>>Silniční</option>
                            <option value="<?= ContractType::XC->value ?>" <?php if($filterType === "1") {echo " selected ";} ?>>Cross Country</option>
                            <option value="<?= ContractType::Trail->value ?>" <?php if($filterType === "2") {echo " selected ";} ?>>Trail</option>
                            <option value="<?= ContractType::Enduro->value ?>" <?php if($filterType === "3") {echo " selected ";} ?>>Enduro</option>
                            <option value="<?= ContractType::Downhill->value ?>" <?php if($filterType === "4") {echo " selected ";} ?>>Downhill</option>
                            <option value="<?= ContractType::Gravel->value ?>" <?php if($filterType === "5") {echo " selected ";} ?>>Gravel</option>
                            <option value="<?= ContractType::Fatbike->value ?>" <?php if($filterType === "6") {echo " selected ";} ?>>Fatbike</option>
                        </select>
                        <input type="date" name="filterDate" value="<?= htmlspecialchars($filterDate) ?>">
                        <input type="time" name="filterTime" value="<?= htmlspecialchars($filterTime) ?>">
                        <input type="text" name="filterUser" placeholder="Zadávající uživatel" value="<?= htmlspecialchars($filterUser) ?>">                        
                        <select name="filterStatus">
                            <!-- <option value="<?= null ?>">Status zakázky</option> -->
                            <option value="<?= ContractStatus::Entered->value ?>" <?php if($filterStatus === "0") {echo " selected ";} ?>>Zadáno</option>
                            <option value="<?= ContractStatus::Retrieved->value ?>" <?php if($filterStatus === "1") {echo " selected ";} ?>>Vyskladněno</option>
                            <option value="<?= ContractStatus::Cancelled->value ?>" <?php if($filterStatus === "2") {echo " selected ";} ?>>Zrušeno</option>
                        </select>
                        <input type="text" name="filterChangingUser" placeholder="Ukončující uživatel" value="<?= htmlspecialchars($filterChangingUser) ?>">
                        <section class="buttons">
                            <input type="submit" value="Filtrovat">
                            <a href="/OrderingSystem">Vyčistit filtr</a>
                        </section>
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