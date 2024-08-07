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

    if(Auth::checkRole(Roles::Admin->value)){    
        $user->id = $_SESSION["logged_user_id"];
        $user->email = $user->getUserEmail($connection, $user->id);
        $allUsers = $user->getAllUsers($connection);
    }else{
        Url::redirectUrl("/OrderingSystem");
    }

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
    <title>Všichni uživatelé</title>
</head>
<body>
    
    <?php require "../assets/userMessage.php" ?>

    <?php require "../assets/header.php" ?>

    <main>
        <div class="container">
            
            <form action="" method="post">
                <input type="text" name="userName">
                <input type="text" name="userSurname">
                <input type="text" name="userEmail">
                <select name="userRole">
                    <option value="<?= Roles::Admin->value ?>">Admin</option>
                    <option value="<?= Roles::Craftsman->value ?>">Řemeslník</option>
                    <option value="<?= Roles::Warehouseman->value ?>">Skladník</option>
                </select>
                <select name="userStatus">
                    <option value="<?= UserStatus::Normal->value ?>">Normal</option>
                    <option value="<?= UserStatus::Blocked->value ?>">Blocked</option>
                </select>
                <input type="submit" value="Filtrovat">
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