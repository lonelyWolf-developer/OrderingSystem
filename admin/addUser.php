<?php

    session_start();

    require "../classes/User.php";
    require "../classes/Auth.php";
    require "../classes/Database.php";

    if(!Auth::checkRole(Roles::Admin->value)){
        die("Na toto nemáš oprávnění!");
    }

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
    <title>Přidání uživatele</title>
</head>
<body>
    <?php require "../assets/header.php" ?>

    <main>
        <div class="container">
            <section class="createUser">
                <h1>Nový uživatel</h1>
                <form action="./registrationUser.php" method="post">
                    <input type="text" name="name" placeholder="Křestní jméno" required>
                    <input type="text" name="surname" placeholder="Příjmení" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Heslo" id="firstPassword" onkeyup="return validate()" required>
                    <p class="validatePassword" id="validateMessage">Délka hesla musí být alespoň 8 znaků<br>musí obsahovat alespoň jedno velké 
                    a jedno malé písmeno<br>jedno číslo a jeden speciální znak kromě mezer.</p>
                    <input type="password" name="passwordAgain" placeholder="Heslo znovu" id="secondPassword" oninput="return conform()" required>
                    <p class="validatePassword" id="conformMessage">Hesla se neshodují.</p>
                    <select name="role" required>
                        <option value="<?= Roles::Admin->value ?>">Administrátor</option>
                        <option value="<?= Roles::Craftsman->value ?>">Řemeslník</option>
                        <option value="<?= Roles::Warehouseman->value ?>">Skladník</option>
                    </select>
                    <input type="submit" value="Odeslat">
                </form>
            </section>
        </div>
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/passwordValidate.js"></script>
</body>
</html>