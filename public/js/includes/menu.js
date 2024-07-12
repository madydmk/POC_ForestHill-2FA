$(document).ready(function($) {
    /**
     * Click on TR for some pages
     */
    $(".clic tr").click(function(){
        if ($(this).attr("data-href")) {
            document.location.href = $(this).attr("data-href");
        }
    });

    var cnilCookieName = "cookieCNIL";
    if(null == cookieManagerLocal.readCookie(cnilCookieName)) {
        $('#cookies-infos').show();
        cookieManagerLocal.createCookie(cnilCookieName, 'ok', 365);
    }

    $('#cookies-infos p> span').click(function () {
        $('#cookies-infos').slideUp();
    });

    // limitation de l'affichage de la popin d'avertissement concernant les cookies à une fois par semaine (sauf si fermée manuellement : 365 jours)
    var cookies = document.cookie.split('; ');
    var setCookie = true;
    for (var i in cookies) {
        var cookieName = cookies[i].substr(0, cookies[i].indexOf('='));
        if (cookieName == 'cookie_disclaimer') {
            setCookie = false;
            break;
        }
    }
    if (setCookie) {
        var cookieExpires = new Date(new Date().getTime() + (7 * 86400 * 1000)).toUTCString();
        document.cookie = "cookie_disclaimer=1; expires="+cookieExpires+"; path=/";
    }
    // /!\ Attention ! pas de .off('click'), on surcharge le comportement de base sans l'écraser
    $('#cookies-infos p> span').on('click', function () {
        // au clic, expiration du cookie à 365 jours
        var cookieExpires = new Date(new Date().getTime() + (365 * 86400 * 1000)).toUTCString();
        document.cookie = "cookie_disclaimer=1; expires="+cookieExpires+"; path=/";
    });
});

var cookieManagerLocal = {

    createCookie: function(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires=" + date.toGMTString();
        } else {
            var expires = "";
        }
        document.cookie = name+"="+value+expires+"; path=/";
    },

    readCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    },

    /**
     *
     * @param name
     */
    eraseCookie: function(name) {
        this.createCookie(name,"",-1);
    }
};

$(document).ready(function() {
    $('body').addClass('loaded');
    $(window).on("resize", function() {
        var h = $(window).outerHeight() - $('header').outerHeight() - $('footer').outerHeight() - 80;
        $('#content').css('min-height', h);
    });
    $(window).resize();

});

