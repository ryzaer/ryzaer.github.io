
<?php
// run php vendor.php
class templateSPA {
    private static $stmt;
    
    function uname($str){
        return str_replace(" ","-",preg_replace('/\s+/',' ',strtolower($str)));
    }
    function htmbody($title){
        return implode('',[
"<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>$title</title>
    </head>
    <body>
        <h2>Hello, this is $title</h2>
    </body>
    <script>
</html>"
        ]);
    }
    function dirpages(){

    }
    static function name($str){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        self::$stmt->name = $str;
    }
    static function header($str){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        self::$stmt->header = $str;
    }
    static function footer($str){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        self::$stmt->footer = $str;
    }
    static function pages(...$args){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        foreach ($args as $num => $val) {
            $wname= __DIR__ .'/'.self::$stmt->uname($num > 0 ? $val[0] : 'index').'.html';
            $hbody= self::$stmt->htmbody($val[0]);
            // var_dump($wname);
            file_exists($wname) || file_put_contents($wname,$hbody);    
        }
    }

}
templateSPA::pages(
    ["Mukadimah"],
    ["Data Contoh"],
    ["About"]
);