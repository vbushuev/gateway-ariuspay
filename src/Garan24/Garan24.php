<?php
namespace Garan24;
class Garan24{
    protected static $logger = "laravel";
    protected static $_debug = true;
    public static function setLogger($l="laravel"){
        self::$logger = $l;
    }
    public static function setDebugMode($b = true){
        self::$_debug=$b;
    }
    public static function getRemoteIp(){
        $ip="";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public static function debug($mix){
        if(!self::$_debug)return;
        $str="";
        if(is_array($mix)){
            foreach($mix as $k=>$v){
                $str.=(strlen($str)?"\n\t":"")."{$k} = {$v}";
            }
            $str="array [\n".$str."\n]";
        }
        elseif (is_object($mix)) {
            $mix = json_decode(json_encode($mix),true);
            foreach($mix as $k=>$v){
                $str.="\n\t{$k} = {$v}";
            }
            $str="object [".$str."\n]";
        }
        else $str=$mix;
        (class_exists("Log",false))?call_user_func("Log::debug",$str."\n"):file_put_contents("../garan24-".date("Y-m-d").'.log',$str."\n",FILE_APPEND);

    }
}
?>
