<?php

class Utils 
{

    public function kodeTicket($prefix, $jml_char)
    {
        $acak = $prefix.substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,$jml_char);

        if (!preg_match('~[0-9]+~', $acak)) {
            $acak = substr($acak, 0, -1).rand(1,9);
        }

        return $acak;
    }

    private function get_otorisasi_header()
    {
        $headers = null;
    
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
    
        return $headers;
    }
    
    
    public function get_token_bearer() 
    {
        $headers = $this->get_otorisasi_header();
        
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }else{
            return null;
        }
    }
}