function openNav(){document.getElementById("mySidebar").style.marginLeft="0"}function closeNav(){document.getElementById("mySidebar").style.marginLeft="-250px"}class vanilaSPA{constructor(){this.siteHead="header",this.siteMain="main",this.siteFoot="footer",this.namePage="pg-stat-name",this.nameStat="pg-stat-load";this.onStyle={"about":[ "css/about.css"]};this.onScript={"about":[ "js/about.js","https://releases.jquery.com/git/jquery-git.min.js"]};const getMain=document.querySelector(this.siteMain);if(!getMain.getAttribute(this.namePage)){getMain.setAttribute(this.namePage,this.getPart())}}route=(event)=>{event=event||window.event;event.preventDefault();window.history.pushState({},"",event.target.href);this.getPage()};getPart=()=>{var path=window.location.pathname.split("/"),part=path[path.length - 1].trim();return part?part:'index'};getPage=async()=>{const mainElement=document.querySelector(this.siteMain);var grab=true,page=this.getPart();if(!mainElement.getAttribute(this.nameStat)){mainElement.setAttribute(this.nameStat,'loaded')}if(mainElement.getAttribute(this.nameStat)=='loaded'){if(window.location.hash)grab=false;const urlParams=new URLSearchParams(window.location.search);if(urlParams.size>0)grab=true;if(grab||mainElement.getAttribute(this.namePage)!=page){mainElement.setAttribute(this.nameStat,'process');var html=await fetch(page).then((data)=>data.text()),htmc;htmc=html.split(/<(\/)?title((\s+)?([\w-]+="[^"]*")?)+?>/ig)[5];document.title=htmc;htmc=html.split(/<(\/)?main((\s+)?([\w-]+="[^"]*")?)+?>/ig)[5];mainElement.innerHTML=htmc;mainElement.setAttribute(this.nameStat,'loaded')}if(this.getHash(1)){var getIDElement=document.getElementById(this.getHash(1));!getIDElement||window.scrollTo(0,getIDElement.offsetTop)}mainElement.setAttribute(this.namePage,page);this.addScript()}else{console.log("page still loading")}};addScript=()=>{if(this.onStyle[this.getPart()])this.onStyle[this.getPart()].forEach((val)=>this.getStyle(val));if(this.onScript[this.getPart()])this.onScript[this.getPart()].forEach((val)=>this.getScript(val))};getScript=(url)=>{if(document.querySelector(`script[src="${url}"]`)){console.log(url+'already loaded');return Promise.resolve()}return new Promise((resolve,reject)=>{const script=document.createElement("script");script.src=url;script.async=true;script.onload=resolve;script.onerror=reject;document.body.appendChild(script)})};getStyle=(url)=>{if(document.querySelector(`link[href="${url}"]`)){console.log(url+'already loaded');return Promise.resolve()}return new Promise((resolve,reject)=>{const link=document.createElement("link");link.rel="stylesheet";link.href=url;link.onload=resolve;link.onerror=reject;document.head.appendChild(link)})};getHash=(ints)=>{const hashData=window.location.hash.split("#");return ints>1?hashData[ints]:hashData[1]}}F3=new vanilaSPA();window.onpopstate=F3.getPage;F3.addScript();document.addEventListener('click',function(event){const anchor=event.target.closest('a');if(anchor){const href=anchor.getAttribute('href');if(anchor.hasAttribute('href')&&!anchor.hasAttribute('target')&&!href.match(/(http(s)?:)?\/\//))F3.route(event)}})