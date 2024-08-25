<?php

session_start();

require "../classes/Auth.php";
require "../classes/Url.php";
require "../classes/Contract.php";

if($_SERVER["REQUEST_METHOD"] === "POST" and Auth::isLoggedIn()){
    $redirectUrl = "";    
    $queryArray = array();
    $oneQuery = "?";

    if(isset($_POST["filterOrderNumber"])){
        $queryArray["filterOrderNumber"] = $_POST["filterOrderNumber"];
    }

    if(isset($_POST["filterType"])){
        $queryArray["filterType"] = $_POST["filterType"];
    }

    if(isset($_POST["filterDate"])){
        $queryArray["filterDate"] = $_POST["filterDate"];
    }

    if(isset($_POST["filterTime"])){
        $queryArray["filterTime"] = $_POST["filterTime"];
    }

    if(isset($_POST["filterUser"])){
        $queryArray["filterUser"] = $_POST["filterUser"];
    }
    
    if(isset($_POST["filterStatus"])){
        $queryArray["filterStatus"] = $_POST["filterStatus"];
    }

    if(isset($_POST["filterChangingUser"])){
        $queryArray["filterChangingUser"] = $_POST["filterChangingUser"];
    }
    
    foreach($queryArray as $key => $value){
        if(!empty($value) or $value == 0){
            $queryArray[$key] = $key . "=" . $value; 
        }else{
            unset($queryArray[$key]);
        }                    
    }
    
    $oneQuery = $oneQuery . implode("&", $queryArray);
    
    if($oneQuery != "?"){
        $redirectUrl = $redirectUrl . $oneQuery;
    }

    Url::redirectUrl($redirectUrl);

}else{
    Url::redirectUrl("");
}

?>