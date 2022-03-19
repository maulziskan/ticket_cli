<?php

class Utils 
{
    protected $string;

    public function kodeTicket($prefix, $jml_char)
    {
        $acak = $prefix.substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,$jml_char);

        if (!preg_match('~[0-9]+~', $acak)) {
            $acak = substr($acak, 0, -1).rand(1,9);
        }

        return $acak;
    }
}