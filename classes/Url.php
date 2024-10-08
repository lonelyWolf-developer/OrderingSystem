<?php
    class Url{
        
        /**
         * 
         * Setting a redirect url address
         * 
         * @return void
         * 
         */
        public static function redirectUrl($path){
            if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] != "off"){
                $url_protocol = "https";
            }else{
                $url_protocol = "http";
            }
        
            header("location: $url_protocol://".$_SERVER["HTTP_HOST"].$path);
        }


        /**
         * 
         * Get full url adrress
         * 
         * @return string $fullUrlAddress
         * 
         */
        public static function getFullUrl(){
            if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] != "off"){
                $url_protocol = "https";
            }else{
                $url_protocol = "http";
            }

            $url =  "$url_protocol://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

            return htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
        }

        /**
         * 
         * Get base url address
         * 
         * @return string $baseUrlAddress
         * 
         */
        public static function getBaseUrl(){
            if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] != "off"){
                $url_protocol = "https";
            }else{
                $url_protocol = "http";
            }

            $url =  "$url_protocol://{$_SERVER['HTTP_HOST']}";

            // $url =  "$url_protocol://{$_SERVER['HTTP_HOST']}";

            return htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
        }

        public static function readOneQuery($url, $query){
            if(str_contains($url, '?') and str_contains($url, $query)){
                $components = parse_url($url);
                parse_str($components['query'], $params);
                return $params[$query];
            }else{
                return "";
            }          
          
        }

        public static function readAllQueryes($url){
            if(str_contains($url, '?')){
                $components = parse_url($url, PHP_URL_QUERY);
                parse_str($components, $result);

                foreach($result as $key => $value){
                    if(str_contains($key, "amp;")){
                        $result[trim($key, "amp;")] = $result[$key];
                        unset($result[$key]);
                    }         
                }

                return $result;
            }else{
                return "";
            }
        }

        public static function getEncodeQuery($url){
            if(str_contains($url, '?')){
                $parseUrl = parse_url($url, PHP_URL_QUERY);
                return "?" . urldecode($parseUrl);
            }else{
                return "";
            }
        }
    }

?>