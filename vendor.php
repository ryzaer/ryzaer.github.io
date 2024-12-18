
<?php
// run php vendor.php
// run php vendor.php minify to minifiy resources
class templateSPA {
    private static $stmt;
    public $name,$header,$footer;
    
    function uname($str){
        return str_replace(" ","-",preg_replace('/\s+/',' ',strtolower($str)));
    }
    function icon($str=null){
        $this->icon = $str;
        return $this;
    }
    
    function htmbody($num,$title,$content){
        $rands = time();
        $title = $num > 0 ? $title . " ~ {$this->name}" : $this->name;
        $icon = $this->icon ? "\n\t\t<link rel=\"icon\" href=\"{$this->icon}\">" : "";
        return implode('',[
"<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
        <meta name=\"description\" content=\"$title\">$icon
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
        foreach (['pages','css','js'] as $dir) {            
            is_dir(__DIR__ ."/$dir") || mkdir(__DIR__ ."/$dir",0777,true);
        }
        $basedirs = __DIR__ ."/pages";
        // made js and css
$vanilaSPA = <<<JS
class vanilaSPA {
    constructor() {
        /** default page container header, main, footer */
        this.siteHead = "header",
        this.siteMain = "main",
        this.siteFoot = "footer"
    }
    route = (event) => {
        event = event || window.event;
        event.preventDefault();
        window.history.pushState({}, "", event.target.href);            
        this.getPage();
    };
    getPage = async () => { 
        const mainElement = document.querySelector(this.siteMain);   
        var tags = "",
            path = window.location.pathname,
            part = path.split("/"),
            part = part[part.length - 1].trim(),
            page = part ? part : 'index';
        /** make clear that content is not the same content*/
        if(mainElement.getAttribute("content") !== page){
            var html = await fetch(page).then((data) => data.text()),
                htmc;
            mainElement.setAttribute('content',page);
            /** handle error page 4** to 5** 
             * 
            if(html.match(/<title>(\s+)?(4|5)\d{1,2}\s/)){
                html = await fetch(page.replace(page,'404')).then((data) => data.text());
            }
            */
            /** parsing html template title */
            htmc = html.split(/(\\n)?<(\/)?title>(\\n)?/ig)[4];
            document.title = htmc;

            /** parsing html template content */
            htmc = html.split(/(\\n)?<(\/)?main>(\\n)?/ig)[4];
            mainElement.innerHTML = htmc;

            
            /** this is handling hashtags */
            if(this.getHash(1)){
                var getIDElement = document.getElementById(this.getHash(1));
                !getIDElement || window.scrollTo(0, getIDElement.offsetTop);
            }
        }
    };
    getHash = (ints) => {
        const hashData = window.location.hash.split("#");
        return ints > 1 ? hashData[ints] : hashData[1];
    }
}
F3 = new vanilaSPA();
window.onpopstate = F3.getPage;
/*window.onload = F3.getPage;*/
document.addEventListener('click', function(event) {    
    /** Check if the clicked element is an <a> tag */ 
    const anchor = event.target.closest('a');    
    if (anchor){
        /** get the href attribute value to check if it starts with # */ 
        const href = anchor.getAttribute('href');
        if (anchor.hasAttribute('href') && !anchor.hasAttribute('target') && !href.startsWith('#') && !href.match(/(http(s)?:)?\/\//)) 
            F3.route(event)
    }
});
JS;
        if(isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'minify')
            $vanilaSPA = self::$stmt->minify('js',$vanilaSPA);
        file_put_contents(__DIR__."/js/app.js",$vanilaSPA);
        file_exists(__DIR__."/css/app.css") || file_put_contents(__DIR__."/css/app.css",'/* Make Your Own CSS here */');
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
            if(isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'minify')
                $hbody = self::$stmt->minify('html',$hbody);
            file_put_contents($wname,$hbody);   
        }
    }
    function minify(...$text){
        $allows = 'js|css|html';
        $minify = 'js';
        $script = [];
        $ignore = "blockquote|textarea|code|pre";
        $regex1 = [
            // Remove breakline, space & comments /*....*/
            '/\/(\/)?(\s+)?\*+[\s\S]*?\*+(\s+)?\/(\/)?|\n+|\s+|\t+/',
            // Remove space after & before of symbol }{><]|()?!+=;,:
            '/(\s+)?(\}|\{|\>|\<|\]|\(|\)|\?|\!|\+|\||=|;|,|:)(\s+)?/i',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',	
            // Remove the last semicolon }
            '#(;+\}|\s+\})#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. for js array to obj format From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i', 
            // remove space before if,var,$
            '/(?<=(,|;|\{|\}))(\s+)(?=(if|var|\$))/i'
        ];
        $regex2 = [
            ' ',
            '$2',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3',
            ''
        ];
        foreach ($text as $value) {
            $def_ext = false;
            if(isset($value) && is_string($value)){
                foreach (explode("|",$allows) as $key) {
                    if($key && $key == $value){
                        $minify = $key;
                        $def_ext = true;
                    }
                }
                if(!$def_ext){
                    $script[$minify][] = $value;
                }
            }  
            if(isset($value) && is_object($value) && isset($value->ignore) && $value->ignore){
                $ignore = $value->ignore;
            }       
        }
        $regex3 = [
            // remove tab, space & comments for html 
            '/(\t+|\s+|<!--(.*?)-->)/',
            // remove breakline
            '/[\n\r]/i',
            '/(\s+)?(\}|\{|\>|\<|\]|\(|\)|=|;|:)(\s+)?/i',
            // remove space before if,var,$ html tags (also js include js in attr )
            '/(?<=(,|;))(\s+)(?=(if|var|\?|\!|\$))/i',
            // remove space before end of semicolon html singleton tag
            '/\s+?\/>/'
        ];  
        $regex4 = [$regex2[0],$regex2[6],$regex2[1],$regex2[6],'/>'];
        $count = count($script);
        foreach (explode("|",$allows) as $key) {
            if($key && isset($script[$key]) && $script[$key]){
                $shrink = implode("",$script[$key]);
                if($key == 'js'){
                    $shrink = trim(preg_replace([
                        $regex1[0],
                        $regex1[1],
                        //$regex1[2],
                        $regex1[3],
                        $regex1[4],
                        $regex1[5],
                        $regex1[6],
                    ],
                    [
                        $regex2[0],
                        $regex2[1],
                        //$regex2[2],
                        $regex2[3],
                        $regex2[4],
                        $regex2[5],
                        $regex2[6],
                    ],$shrink)); 
                }
                if($key == 'css'){
                    $shrink = trim(preg_replace([
                        $regex1[0],
                        $regex1[1],
                        $regex1[3],
                        $regex1[6],
                    ],
                    [
                        $regex2[0],
                        $regex2[1],
                        $regex2[3],
                        $regex2[6],
                    ],$shrink));  
                }
                if($key == 'html'){
                    $shrink = implode("",$script[$key]);
                    if(is_bool($ignore) && $ignore == true){
                        $shrink = preg_replace($regex3,$regex4,$shrink);
                    }
                    if(is_string($ignore)){
                        $bypass = preg_split('/(<\/?'.$ignore.'[^>]*>)/Uis', $shrink, null, PREG_SPLIT_DELIM_CAPTURE); 
                        $shrink = null;
                        foreach($bypass as $i => $path)
                        {
                            $not_filtered = false;
                            if($i % 4 == 2){
                                // this filter to make sure that ignore tags not catch up on this method
                                preg_match('/(<\/?'.$ignore.'[^>]*>)/i',$path,$match);
                                if($match){
                                    $shrink .= $path;                            
                                }else{ 
                                    $not_filtered = true;
                                }
                            }else{ 
                                $not_filtered = true;
                            }
                            if($not_filtered)
                                $shrink .= preg_replace($regex3,$regex4,$path);
                        }                    
                    }
                    
                }   
                if($count > 1){
                    $script[$key] = $shrink;
                }else{
                    $script = $shrink;
                }
            }
        }
    
        return $script ? $script : false ;
        
    }
}
$nav = <<<HTML
<nav id="mySidebar" class="sidebar">
  <a class="closebtn" onclick="closeNav()">×</a>
  <a href="./" onclick="closeNav()">Home</a>
  <a href="data-contoh" onclick="closeNav()">Data Contoh</a>
  <a href="about" onclick="closeNav()">About</a>
  <a href="contact" onclick="closeNav()">Contact</a>
</nav>
<div id="menuLogo">
    <button class="openbtn" onclick="openNav()">☰ MyProject</button> 
</div>
<script>
	function openNav() {  
        document.getElementById("mySidebar").style.marginLeft = "0";
	}
	function closeNav() {
		document.getElementById("mySidebar").style.marginLeft = "-250px";
	}
</script>
HTML;
$foot = "<div class=\"container\">© 2020 ~&nbsp;<a href=\"//github.com/ryzaer\">ryzaer</a></div>";
templateSPA::name("riza.us")->icon('img/icon-192.png')->header($nav)->footer($foot)->pages("Home","Data Contoh","About","Contact");