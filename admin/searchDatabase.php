<?php

session_start();

require "../classes/Auth.php";
require "../classes/Url.php";
require "../classes/User.php";

if($_SERVER["REQUEST_METHOD"] === "POST" and Auth::checkRole(Roles::Admin->value)){
    $redirectUrl = "/OrderingSystem/admin/allUsers.php";
    
    if(isset($_POST["clean"])){
        Url::redirectUrl($redirectUrl);
    }
    
    $queryArray = array();
    $oneQuery = "?";

    if(isset($_POST["userName"])){
        $queryArray["userName"] = $_POST["userName"];
    }

    if(isset($_POST["userSurname"])){
        $queryArray["userSurname"] = $_POST["userSurname"];
    }

    if(isset($_POST["userEmail"])){
        $queryArray["userEmail"] = $_POST["userEmail"];
    }

    if(isset($_POST["userRole"])){
        $queryArray["userRole"] = $_POST["userRole"];
    }

    if(isset($_POST["userStatus"])){
        $queryArray["userStatus"] = $_POST["userStatus"];
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
    Url::redirectUrl("/OrderingSystem");
}

?>