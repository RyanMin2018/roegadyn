<?php
namespace src\util;

class CryptoUtil {

    private $secret_key = "rstsecretkey";
    private $secret_iv  = "#@$%^&*()_+=-";
    
    public static function encrypt($str) {
        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 32);
        
        return str_replace("=", "", base64_encode(openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv)));
    }
    
    public static function decrypt($str) {
        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 32);
        return openssl_decrypt(base64_decode($str), "AES-256-CBC", $key, 0, $iv);
    }
    

}

