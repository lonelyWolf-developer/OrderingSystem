<?php

    class Auth{

        public static function isLoggedIn(){
            return isset($_SESSION["is_logged_in"]) and $_SESSION["is_logged_in"];
        }        
        
        public static function checkRole($role){
            if(isset($_SESSION["is_logged_in"]) and $_SESSION["is_logged_in"] and isset($_SESSION["role"])){
                
                return $_SESSION["role"] == $role;

            }else{
                return false;
            }
        }
    }

?>