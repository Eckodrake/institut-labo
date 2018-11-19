// -----------
// Hide Navbar
// -----------
(function () {
    let prevScrollpos = window.pageYOffset;
    window.onscroll = function() {
        let currentScrollPos = window.pageYOffset;
        if (document.body.scrollTop > 80 || prevScrollpos > currentScrollPos) {
            document.getElementById("navbar").style.top = "0";
        } else if (window.pageYOffset > 80) {
            document.getElementById("navbar").style.top = "-50vh";
        }
        prevScrollpos = currentScrollPos;
    }
})(); // End Hide Navbar

// -------------
// Smooth Scroll
// -------------
(function () {
    $('a[href*="#"]')
        .not('[href="#"]')
        .not('[href="#0"]')
        .click(function (event) {
            if (
                location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '')
                &&
                location.hostname === this.hostname
            ) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000, function () {
                        let $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) {
                            return false;
                        } else {
                            $target.attr('tabindex', '-1');
                            $target.focus();
                        }
                    });
                }
            }
        });
})(); // End Smooth Scroll


