<?php

enum MessageType: string{
    case Success = "success";
    case Failure = "failure";
}

class Message{
    public $message;
    public $color;
    public $crossPath;

    function createMessageSession($message, $color){
        $_SESSION["message"] = $message;
        $_SESSION["color"] = $color;
    }

    function createMessage($normalPath = true){
        if(isset($_SESSION["message"]) and $_SESSION["message"] != "" and isset($_SESSION["color"]) and $_SESSION["color"] != ""){
            $message = new Message();
            $message->message = $_SESSION["message"];
            $message->color = $_SESSION["color"];

            if(!$normalPath){
                $message->crossPath = "../img/crossMenu.png";
            }else{
                $message->crossPath = "./img/crossMenu.png";
            }

            return $message;
        }else{
            return false;
        }
    }

    function killSession(){
        $_SESSION["message"] = "";
        $_SESSION["color"] = ""; 
    }
}

?>