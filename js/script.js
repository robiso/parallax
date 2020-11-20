/*
 *  WonderCMS 3.0.0 Parallax Theme
 *  by Stephan Stanisic
 *
 *  I'm not much of a front end guy so this is an attempt
 *  of making a theme. Most of this code is from codepen.io!
 *
 *  All of this code is non critical, so it is okay if any of
 *  it failes in older types of browsers.
 *
 *  Credit where due:
 *  Parallax effect from P. Rachel, https://codepen.io/Prachl/pen/jjKzEy
 *  Showing and hiding of navbar from w3scools, https://www.w3schools.com/w3css/tryw3css_templates_parallax.htm
 *  Random generic smooth scroll jQuery code probably from css tricks
 */

// Parallax effect (with config)
window.addEventListener('scroll', function(e){
    var scrolled = window.pageYOffset,
        parallax = document.querySelector(".parallax"),
        title = document.querySelector(".parallax .inner"),
        coords = (scrolled * 0.4) + 'px',
        offset = (scrolled * (type == 'inverse' ? 0.4 : (type == 'parallax' ? -0.4 : 0.1))) + 'px';

    if(['dual', 'parallax'].indexOf(type) >= 0)
        parallax.style.transform = 'translateY(' + coords + ')';

    if(['dual', 'parallax', 'inverse'].indexOf(type) >= 0)
        title.style.transform = 'translateY(' + offset + ')';
});

// Change style of navbar on scroll
window.addEventListener('scroll', function() {
    var numHeight = Number(height.replace && height.replace(/[^0-9]/g,'') || height);
    if(numHeight == 0 && loggedIn) return;
    var normal = "navbar navbar-default", classes = " sticky";

    var navbar = document.querySelector("nav");
    if(document.body.scrollTop > window.innerHeight * numHeight / 100 - 100
    || document.documentElement.scrollTop > window.innerHeight * numHeight / 100 - 100) {
        navbar.className = normal + classes;
    } else {
        navbar.className = normal;
    }
});

// Make anchor links scroll instead of snap.
// (It is jQuery, I know)
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

window.fieldSaveParallax = (fieldname, content, target, menu, visibility = null) => {
  document.getElementById('save').style.display = 'block';

  const dataRaw = {
    fieldname: fieldname,
    token: token,
    content: encodeURIComponent(content),
    target: target,
    menu: menu,
    visibility: visibility
  };
  const data = Object.keys(dataRaw).map(function (key, index) {
    return [key, dataRaw[key]].join('=');
  }).join('&');

  // Send request
  const request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    setTimeout(() => window.location.reload(), 50);
  };
  request.open('POST', '', false);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
  request.send(data);
};
