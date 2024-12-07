
<?php
// run php vendor.php
class jquery {
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
</html>"
        ]);
    }
    static function page(...$args){
        if(!self::$stmt)
            self::$stmt = new jquery();
        foreach ($args as $num => $val) {
            $wname= __DIR__ .'/'.self::$stmt->uname($num > 0 ? $val[0] : 'index').'.html';
            $hbody= self::$stmt->htmbody($val[0]);
            // var_dump($wname);
            file_exists($wname) || file_put_contents($wname,$hbody);    
        }
    }

}

jquery::page(
    ["Mukadimah"],
    ["Data Contoh"],
    ["About"]
);