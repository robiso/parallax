/*
 *  WonderCMS 2.* Parallax Theme
 *  by Stephan Stanisic
 *
 *  I'm not much of a front end guy so this is an attempt
 *  of making a theme. Most of this code is from codepen.io!
 *
 *  Credit where due:
 *  Parallax effect from P. Rachel, https://codepen.io/Prachl/pen/jjKzEy
 *  Showing and hiding of navbar from w3scools, https://www.w3schools.com/w3css/tryw3css_templates_parallax.htm
 *  Random generic smooth scroll jQuery code propably from csstricks
 */

// Parallax effect
window.addEventListener('scroll', function(e){
  var scrolled = window.pageYOffset,
      parallax = document.querySelector(".parallax"),
      title = document.querySelector(".parallax .inner"),
      coords = (scrolled * 0.4) + 'px',
      offset = (scrolled * 0.1) + 'px';
  parallax.style.transform = 'translateY(' + coords + ')';
  title.style.transform = 'translateY(' + offset + ')';
});

// Change style of navbar on scroll
window.addEventListener('scroll', function() {
    var normal = "navbar navbar-default ", classes = "sticky";

    var navbar = document.querySelector("nav");
    if (document.body.scrollTop > window.innerHeight * Number(height.replace(/[^0-9]/g,'')) / 100 - 100 || document.documentElement.scrollTop > window.innerHeight * Number(height.replace(/[^0-9]/g,'')) / 100 - 100) {
        navbar.className = normal + classes;
    } else {
        navbar.className = normal;
    }
});

$('a[href*="#"]').not('[href="#"]').not('[href="#0"]')
.click(function(event) {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
        location.hostname == this.hostname ) {

        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

        if (target.length) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 1000, function() {
                var $target = $(target);
                $target.focus();
                if ($target.is(":focus")) {
                    return false;
                } else {
                    $target.attr('tabindex','-1');
                    $target.focus();
                };
            });
        }

    }
});


/* This is my own 'awesome' code

if(document.getElementById("adminPanel")) {

    var adminPanel = document.getElementById("adminPanel");

    var nav = adminPanel.getElementsByClassName("nav")[0];
    var newNav = document.createElement("li");
    newNav.innerHTML = '<a href="#themeOptions" aria-controls="themeOptions" role="tab" data-toggle="tab">Theme options</a>';
    nav.appendChild(newNav);

    var tabs = adminPanel.getElementsByClassName("tab-content")[0];
    var newTab = document.createElement("div");
    newTab.className = "tab-pane";
    newTab.id = "themeOptions";
    newTab.innerHTML = `
        <p class="subTitle">Background image</p>
        <div class="change"><p>Upload a image with the name '<b>${page}.jpg</b>' to the files section.</p></div>

        <p class="subTitle">Header height</p>
        <div class="form-group"><div class="change"><select class="form-control" name="themeSelect" onchange="fieldSave('themeHeaderHeight',this.value,'pages');"><option value="${height}">Current: ${height.replace(/[^0-9]/g,'')}%</option><option value="100vh">100% (default)</option><option value="90vh">90%</option><option value="80vh">80%</option><option value="70vh">70%</option><option value="60vh">60%</option></select></div></div>
    `;
    tabs.appendChild(newTab);

} */
