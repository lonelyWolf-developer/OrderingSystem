<?php

    session_start();

    require "../classes/Database.php";
    require "../classes/Auth.php";
    require "../classes/User.php";
    require "../classes/Message.php";
    require "../classes/Url.php";

    $database = new Database();
    $connection = $database->connectionDB();

    $user = new User();

    if(Auth::checkRole(Roles::Admin->value)){    
        $user->id = $_SESSION["logged_user_id"];
        $user->email = $user->getUserEmail($connection, $user->id);
        $allUsers = $user->getAllUsers($connection);
    }else{
        Url::redirectUrl("/OrderingSystem");
    }

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
    
    <?php require "../assets/header.php" ?>

    <main>
        <div class="container">
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
                            <div class="userInfo"><?= $onlyUser->name ?></div>
                            <div class="userInfo"><?= $onlyUser->surname ?></div>
                            <div class="userInfo emailInfo"><?= $onlyUser->email ?></div>
                            <div class="userInfo roleInfo"><?= $onlyUser->role ?></div>
                            <div class="userInfo"><?= $onlyUser->status ?></div>
                            <div class="userInfo">
                                <form action="" method="post">
                                    <input type="hidden" name="id" value="<?= $onlyUser->id ?>">
                                    <?php if($onlyUser->status == UserStatus::Normal->name): ?>
                                        <input type="submit" value="Zablokovat" class="statusButton">
                                    <?php else: ?>
                                        <input type="submit" value="Odblokovat" class="statusButton">
                                    <?php endif; ?>
                                </form>
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

</body>
</html>