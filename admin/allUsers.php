<?php

    session_start();

    require "../classes/Database.php";
    require "../classes/Auth.php";
    require "../classes/User.php";
    require "../classes/Message.php";
    require "../classes/Url.php";

    $database = new Database();
    $connection = $database->connectionDB();

    $url = Url::getFullUrl();

    $user = new User();

    $querry = Url::readAllQueryes($url);    

    $userName = isset($querry["userName"]) ? $querry["userName"] : "";
    $userSurname = isset($querry["userSurname"]) ? $querry["userSurname"] : "";
    $userEmail = isset($querry["userEmail"]) ? $querry["userEmail"] : "";
    $userRole = isset($querry["userRole"]) ? $querry["userRole"] : null;
    $userStatus = isset($querry["userStatus"]) ? $querry["userStatus"] : null;

    if(Auth::checkRole(Roles::Admin->value)){    
        $user->id = $_SESSION["logged_user_id"];
        $user->email = $user->getUserEmail($connection, $user->id);
        $allUsers = $user->getAllUsers($connection, $columns = "*", $name = $userName, $surname = $userSurname, $email = $userEmail, $role = $userRole, $status = $userStatus);
    }else{
        Url::redirectUrl("/OrderingSystem");
    }

    $message = new Message();
    $message = $message->createMessage(false);

    $parseUrl = parse_url($url, PHP_URL_QUERY);
    // echo $parseUrl;
    echo urldecode($parseUrl);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <title>Všichni uživatelé</title>
</head>
<body>
    
    <?php require "../assets/userMessage.php" ?>

    <?php require "../assets/header.php" ?>

    <main>
        <div class="container">
            
            <form action="./searchDatabase.php" method="post">
                <input type="text" name="userName" value="<?= htmlspecialchars($userName) ?>">
                <input type="text" name="userSurname" value="<?= htmlspecialchars($userSurname) ?>">
                <input type="text" name="userEmail" value="<?= htmlspecialchars($userEmail) ?>">
                <select name="userRole">
                    <option value="<?= null ?>">Nevyplněno</option>
                    <option value="<?= Roles::Admin->value ?>" <?php if($userRole === "0") {echo " selected "; } ?>>Admin</option>
                    <option value="<?= Roles::Craftsman->value ?>" <?php if($userRole === "1") {echo " selected "; } ?>>Řemeslník</option>
                    <option value="<?= Roles::Warehouseman->value ?>" <?php if($userRole === "2") {echo " selected "; } ?>>Skladník</option>
                </select>
                <select name="userStatus">
                    <option value="<?= null ?>">Nevyplněno</option>
                    <option value="<?= UserStatus::Normal->value ?>" <?php if($userStatus === "0") {echo " selected "; } ?>>Normal</option>
                    <option value="<?= UserStatus::Blocked->value ?>" <?php if($userStatus === "1") {echo " selected "; } ?>>Blocked</option>
                </select>                
                <input type="submit" value="Odeslat" name="submitButton" id="submit">
                <a href="/OrderingSystem/admin/allUsers.php">Vyčistit filtr</a>
            </form>
        
            <section class="allUsers">
                <h1>Všichni uživatelé</h1>
                <?php if(!empty($allUsers)): ?>
                    <?php foreach($allUsers as $oneUser): ?>                        
                        <?php
                            $onlyUser = new User();
                            $onlyUser->id = $oneUser["Id"];
                            $onlyUser->name = $oneUser["Name"];
                            $onlyUser->surname = $oneUser["Surname"];
                            $onlyUser->email = $oneUser["Email"];
                            $onlyUser->role = $onlyUser->translateUserRole($oneUser["Role"]);
                            $onlyUser->status = $onlyUser->translateUserStatus($oneUser["Status"]);
                        ?>                        
                        
                        <div class="row">
                            <div class="userInfo"><?= htmlspecialchars($onlyUser->name) ?></div>
                            <div class="userInfo"><?= htmlspecialchars($onlyUser->surname) ?></div>
                            <div class="userInfo emailInfo"><?= htmlspecialchars($onlyUser->email) ?></div>
                            <div class="userInfo roleInfo"><?= $onlyUser->role ?></div>
                            <div class="userInfo"><?= $onlyUser->status ?></div>
                            <div class="userInfo">
                                <?php if($user->id != $onlyUser->id): ?>
                                    <form action="./changeUserStatus.php" method="post">
                                        <input type="hidden" name="id" value="<?= $onlyUser->id ?>">
                                        <?php if($onlyUser->status == UserStatus::Normal->name): ?>
                                            <input type="hidden" name="status" value="<?= UserStatus::Blocked->value ?>">
                                            <input type="submit" value="Zablokovat" class="statusButton" id="changeStatusButton">
                                        <?php else: ?>
                                            <input type="hidden" name="status" value="<?= UserStatus::Normal->value ?>">
                                            <input type="submit" value="Odblokovat" class="statusButton" id="changeStatusButton">
                                        <?php endif; ?>
                                    </form>
                                <?php else: ?>
                                    Můj účet
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <p class="notice">Nemáš žádné uživatele.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require "../assets/footer.php" ?>

    <script src="../js/messageBox.js"></script>
    <script src="../js/changeUserStatus.js"></script>

</body>
</html>