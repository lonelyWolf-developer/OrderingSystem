<?php

enum ContractType: int
{
    case Road = 0;
    case XC = 1;
    case Trail = 2;
    case Enduro = 3;
    case Downhill = 4;
    case Gravel = 5;
    case Fatbike = 6;
}

enum ContractStatus: int{
    case Entered = 0;
    case Retrieved = 1;
    case Cancelled = 2;
}

class Contract {
    public $id;
    public $orderNumber;
    public $type;
    public $date;
    public $time;
    public $user;
    public $status;
    public $changingUser;

    function getContractType($input){
        $contractType = ContractType::tryFrom($input);

        if($contractType != null){
            return $contractType->name;
        }else{
            return "bike";
        }
         
    }
    function formatDate($input){
        $date = new DateTime($input);
        return $date->format("d.m.Y");
    }

    function formatTime($input){
        $time = new DateTime($input);
        return $time->format("G:i");
    }

    function createContract($connection, $orderNumber, $type, $date, $time, $user, $status)
    {
        $sql = "INSERT INTO product (orderNumber, type, date, time, user, status) VALUES (:orderNumber, :type, :date, :time, :user, :status)";

        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":orderNumber", $orderNumber, PDO::PARAM_INT);
        $stmt->bindValue(":type", $type, PDO::PARAM_INT);
        $stmt->bindValue(":date", $date, PDO::PARAM_STR);
        $stmt->bindValue(":time", $time, PDO::PARAM_STR);
        $stmt->bindValue(":user", $user, PDO::PARAM_INT);
        $stmt->bindValue(":status", $status, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Vytvoření objednávky se nezdařilo.");
            }
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, "../errors/error.log");
            echo $e->getMessage();
        }
    }

    function getAllContracts($connection, $columns = "*", $status = 0){
        $sql = "SELECT $columns FROM product WHERE status = $status ORDER BY Date, Time, Type";

        return $connection->query($sql)->fetchAll();
    }
    
    function deleteContract($connection, $id){
        $sql = "DELETE FROM product WHERE id = :id";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try{
            if($stmt->execute()){
                 return true;
            }else{
                 throw new Exception("Smazání objednávky se nezdařilo.\n");
            }
         }catch(Exception $e){
             error_log($e->getMessage(), 3, "../errors/error.log");
             echo $e->getMessage();
         }
    }

    function changeStatus($connection, $id, $status, $changingUser){
        $sql = "UPDATE product SET status = :status, changingUser = :changingUser WHERE id = :id";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":status", $status, PDO::PARAM_INT);
        $stmt->bindValue(":changingUser", $changingUser, PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try{
            if($stmt->execute()){
                 return true;
            }else{
                 throw new Exception("Změna statusu se nezdařila.\n");
            }
         }catch(Exception $e){
             error_log($e->getMessage(), 3, "../errors/error.log");
             echo $e->getMessage();
         }

    }

    function checkExistContract($connection, $OrderNumber){
        $sql = "SELECT id FROM product WHERE ordernumber = :ordernumber";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":ordernumber", $OrderNumber, PDO::PARAM_INT);

        try{
            if($stmt->execute()){
                $result = $stmt->fetch();                
                return $result[0] != null;
            }
        }catch(Exception $e){
            error_log($e->getMessage(), 3, "../errors/error.log");
            echo $e->getMessage();
        }
    }

    function getOrderNumber($connection, $id){
        $sql = "SELECT ordernumber FROM product WHERE id = :id";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

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
}

?>