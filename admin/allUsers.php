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
    <!-- <link rel="stylesheet" href="../css/main.css"> -->
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
                        <div class="row">
                            <div class="userInfo"><?= $oneUser["Name"] ?></div>
                            <div class="userInfo"><?= $oneUser["Surname"] ?></div>
                            <div class="userInfo"><?= $oneUser["Email"] ?></div>
                            <div class="userInfo"><?= $oneUser["Role"] ?></div>
                            <div class="userInfo"><?= $oneUser["Status"] ?></div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <p>Nemáš žádné uživatele.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require "../assets/footer.php" ?>

</body>
</html>