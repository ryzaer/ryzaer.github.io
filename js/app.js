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
    }
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
            /** parsing html template content */
            htmc = html.split(/(\n)?<(\/)?main>(\n)?/ig)[4];
            mainElement.innerHTML = htmc;
            htmc = html.split(/(\n)?<(\/)?title>(\n)?/ig)[4];
            document.title = htmc;
            /** this is handling hashtags */
            if(this.getHash(1)){
                var getIDElement = document.getElementById(this.getHash(1));
                !getIDElement || window.scrollTo(0, getIDElement.offsetTop);
            }
        }
    }
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
        if (anchor.hasAttribute('href') && !anchor.hasAttribute('target') && !href.startsWith('#') && !href.match(/http(s)?:\/\//)) 
            F3.route(event)
    }
});