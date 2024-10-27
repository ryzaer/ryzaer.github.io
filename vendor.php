<?php
// run php vendor.php
class jquery {
    private static $stmt;
    function uname($str){
        return str_replace(" ","-",preg_replace('/\s+/',' ',strtolower($str)));
    }
    static function page(...$args){
        if(!self::$stmt)
            self::$stmt = new jquery();
        foreach ($args as $val) {
            $wname=__DIR__ .'/'.self::$stmt->uname($val[0]).'.html';
            var_dump($wname);
            // file_exists($wname) || file_put_contents($wname,"this is {$val[0]} page");    
        }
    }
}

jquery::page(
    ["test"],
    ["dATA CONTOH"],
    ["ikan tongkol"]
);