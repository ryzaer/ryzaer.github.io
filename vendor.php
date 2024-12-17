
<?php
// run php vendor.php
class templateSPA {
    private static $stmt;
    public $name,$header,$footer;
    
    function uname($str){
        return str_replace(" ","-",preg_replace('/\s+/',' ',strtolower($str)));
    }
    function htmbody($num,$title,$content){
        $rands = time();
        $title = $num > 0 ? $title . " ~ {$this->name}" : $this->name;
        return implode('',[
"<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
        <meta name=\"description\" content=\"$title\">
        <link rel=\"icon\" href=\"img/icon-192.png\">
        <title>$title</title>
        <link rel=\"stylesheet\" href=\"css/app.css?v=$rands\">
    </head>
    <body>
        {$this->header}<main>
            $content
        </main>{$this->footer}
    </body>
    <script src=\"js/app.js?v=$rands\"></script>
</html>"
        ]);
    }
    function dirpages(){

    }
    static function name($str=NULL){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        self::$stmt->name = $str ? $str : "templateSPA";
        return self::$stmt;
    }
    static function header($str=NULL){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        if($str && is_string($str)){
            $str = preg_split('/\n/', $str);
            $str = implode("\n\t\t\t",$str);
            $str = "<header>\n\t\t\t$str\n\t\t</header>\n\t\t";
        }
        self::$stmt->header = $str;
        return self::$stmt;
    }
    static function footer($str=NULL){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        if($str && is_string($str)){
            $str = preg_split('/\n/', $str);
            $str = implode("\n\t\t\t",$str);
            $str = "\n\t\t<footer>\n\t\t\t$str\n\t\t</footer>";
        }
        self::$stmt->footer = $str;
        return self::$stmt;
    }
    static function pages(...$args){
        if(!self::$stmt)
            self::$stmt = new templateSPA();
        $basedirs = __DIR__ .'/pages';
        is_dir($basedirs) || mkdir($basedirs,0777,true);
        // $pages =  self::$stmt->basedirs(__DIR__.'/pages','/\.htm(l|a|x)?$/');  
        // var_dump($pages);
        // check if template files exist
        foreach ($args as $num => $val) {
            $filehtm = $num > 0 ? self::$stmt->uname($val) : 'index';
            file_exists("$basedirs/$filehtm.html") || file_put_contents("$basedirs/$filehtm.html","<template>\n\t<p>this is $val page.</p>\n</template>");
            // remove template tags
            $getpage = trim(preg_replace('/<\/?template>/','',file_get_contents("$basedirs/$filehtm.html")));
            $getpage = preg_split('/\n/', $getpage);
            $getpage = implode("\n\t\t",$getpage);
            $wname= __DIR__."/$filehtm.html";
            $hbody= self::$stmt->htmbody($num,$val,$getpage);
            file_put_contents($wname,$hbody);   
        }
    }
}
$nav = <<<HTML
<nav>
    <ul>
        <li><a href="./">Home</a></li>
        <li><a href="data-contoh">Data Contoh</a></li>
        <li><a href="about">About</a></li>
        <li><a href="contact">Contact</a></li>
    </ul>
</nav>
HTML;
$foot = "Copyright @ <a href=\"https://github.com/ryzaer\" target=\"_blank\">ryzaer</a> 2020";
templateSPA::name("riza.us")->header($nav)->footer($foot)->pages("Home","Data Contoh","About","Contact");