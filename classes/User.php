<?php

enum Roles: int{
    case Admin = 0;
    case Craftsman = 1;
    case Warehouseman = 2;
}

class User{
    public $id;
    public $name;
    public $surname;
    public $email;
    public $password;
    public $role;

   function createUser($connection, $name, $surname, $email, $password, $role){
        $sql = "INSERT INTO user (name, surname, email, password, role) VALUES (:name, :surname, :email, :password, :role)";

        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":surname", $surname, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_INT);

        try{
            if($stmt->execute()){
                return $connection->lastInsertId();
            }else{
                throw new Exception("Vytvoření uživatele se nezdařilo.");
            }
        }catch(Exception $e) {
            error_log($e->getMessage(), 3, "../errors/error.log");
            echo $e->getMessage();
        }       
    }

    static function authentication($connection, $loginEmail, $loginPassword){
        $sql = "SELECT password FROM user WHERE email = :email";

        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":email", $loginEmail, PDO::PARAM_STR);

        try{
            if($stmt->execute() and $user = $stmt->fetch()){                
                return password_verify($loginPassword, $user[0]);
            }else{
                throw new Exception("Autentikace se nezdařila.");
            }

        }catch(Exception $e){
            error_log($e->getMessage(), 3, "../errors/error.log");
            echo $e->getMessage();
        }
    }

    function getUserId($connection, $userEmail){
        $sql = "SELECT id FROM user WHERE email = :email";

        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":email", $userEmail, PDO::PARAM_STR);

        try{
            if($stmt->execute()){
                $result = $stmt->fetch();                
                return $result[0];
            }
        }catch(Exception $e){
            error_log($e->getMessage(), 3, "../errors/error.log");
            echo $e->getMessage();
        }

    }

    function getUserRole($connection, $id){
        $sql = "SELECT role FROM user WHERE id = :id";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try{
            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result["role"];
            }else{
                throw new Exception("Získání uživatelské role selhalo");
            }
        }catch(Exception $e){
            error_log("Chyba u funkce getUserRole\n", 3, "../errors/error.log");
            echo "Typ chyby: " . $e->getMessage();
        }
    }

    function getUserEmail($connection, $id){
        $sql = "SELECT email FROM user WHERE id = :id";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try{
            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result["email"];
            }else{
                throw new Exception("Získání uživatelského emailu selhalo.");
            }
        }catch(Exception $e){
            error_log("Chyba u funkce getUserEmail\n", 3, "../errors/error.log");
            echo "Typ chyby: " . $e->getMessage();
        }
    }

    function updatePassword($connection, $id, $password){
        $sql = "UPDATE user SET password = :password WHERE id = :id";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try{
            if($stmt->execute()){
                return true;
            }else{
                throw new Exception("Změna hesla se nezdařila.");
            }
        }catch(Exception $e){
            error_log("Chyba u funkce updatePassword\n", 3, "../errors/error.log");
            echo "Typ chyby: " . $e->getMessage();
        }
    }
}

?>