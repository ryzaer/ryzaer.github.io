
<?php
// run php vendor.php
// run php vendor.php minify to minifiy resources
include_once __DIR__."/template.php";

// this is main css
$tmp_css = <<<CSS
@import url("https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css");
@import url("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
@import url("https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap");

/* height set for sticky footer */
html,
body{
  height:100%;  
}
body{
  font-family: "Lato", sans-serif;
  display:table;  
  width:100%;
}
  
/* sidebar */
.sidebar {
  height: 100%;
  width: 250px;
  position: fixed;
  z-index: 1;
  top: 0;
  /* left: 0; */
  margin-left: -250px;
  /* background-color: #111; */
  background-color: #f1f1f1;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidebar a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 16px;
  color: #444;
  display: block;
  transition: 0.3s;
}

.sidebar a:hover {
  color: #111;
}

.sidebar .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}
.closebtn {
  cursor: pointer;
}
.openbtn {
  font-size: 20px;
  cursor: pointer;
  /* background-color: #111; */
  color: #444;
  padding: 10px 15px;
  border: none;
}

.openbtn:hover {
  background-color: #e2dede;
}

#menuLogo,main,footer {
  transition: margin-left .5s;
  padding: 16px;
}

header,
main,
footer{
  display:table-row;
  height:1px;
}

main{
  height:100%;  
}
/* main>.container{
  padding:15px;
} */

footer {
  left: 0;
  bottom: 0;
  width: 100%;
  /* background-color: #f1f1f1; */
  color: #444;
  text-align: center;
}

footer>.container{
  padding-top:15px;
  padding-bottom:15px;
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
CSS;

templateSPA::name("riza.us")->icon('img/icon-192.png')->css($tmp_css);
// header navigation
$nav = <<<HTML
<nav id="mySidebar" class="sidebar">
  <a class="closebtn" onclick="closeNav()">×</a>
  <a href="./" onclick="closeNav()">Home</a>
  <a href="data-contoh" onclick="closeNav()">Data Contoh</a>
  <a href="about" onclick="closeNav()">About</a>
  <a href="contact" onclick="closeNav()">Contact</a>
</nav>
<div id="menuLogo" class="container">
    <button class="openbtn" onclick="openNav()">☰ MyProject</button> 
</div>
HTML;
$nav_js = <<<JS
/** sidebar */
function openNav() {  
    document.getElementById("mySidebar").style.marginLeft = "0";
}
function closeNav() {
    document.getElementById("mySidebar").style.marginLeft = "-250px";
}

/** add some list parser */
JS;
templateSPA::header($nav)->js($nav_js);

// footer navigation
$foot = "<div class=\"container\">© 2020 ~&nbsp;<a href=\"//github.com/ryzaer\">ryzaer</a></div>";
templateSPA::footer($foot)->script("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js");

// rendering template
templateSPA::pages("Home","Data Contoh","About","Contact");