
jQuery.cookie = function (name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

function trim(str, chars) {
    chars = chars || "\\s";
    str = str.replace(new RegExp("^[" + chars + "]+", "g"), "");
    return str;
}

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(c_name, defaultvalue) {
    var i, x, y, arrcookies = document.cookie.split(";");
    for (i = 0; i < arrcookies.length; i++) {
        x = arrcookies[i].substr(0, arrcookies[i].indexOf("="));
        y = arrcookies[i].substr(arrcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        }
    }
    return defaultvalue;
}


// Click btn cpanel
jQuery(document).ready(function ($) {
    $('#cpanel_btn').click(function () {
        if ($('#cpanel_btn i').attr('class') == 'fa fa-hand-o-left') {
            $('#cpanel_wrapper').animate({
                'right': '-302px'
            }, 300, function () {
                $('#cpanel_wrapper').show().animate({
                    'right': '0px'
                });
            });
            $('#cpanel_btn i').attr('class', 'fa fa-hand-o-right');
        } else if ($('#cpanel_btn i').attr('class') == 'fa fa-hand-o-right') {
            $('#cpanel_wrapper').animate({
                'right': '0px'
            }, 300, function () {
                $('#cpanel_wrapper').show().animate({
                    'right': '-302px'
                });
            });
            $('#cpanel_btn i').attr('class', 'fa fa-hand-o-left');
        }
    });
});

//reset cpanel
function onCPResetDefault(_cookie) {
    for (i = 0; i < _cookie.length; i++) {
        if (getCookie(TMPL_NAME + '_' + _cookie[i]) != undefined) {
            createCookie(TMPL_NAME + '_' + _cookie[i], '', -1);
        }
    }

    if (window.location.href.indexOf('?') > -1) window.location.href = window.location.href.substr(0, window.location.href.indexOf('?'));
    else window.location.reload();
}

//apply config cpanel
function onCPApply() {
    var elems = document.getElementById('cpanel_wrapper').getElementsByTagName('*');
    var usersetting = {};
    for (i = 0; i < elems.length; i++) {
        var el = elems[i];
        if (el.name && (match = el.name.match(/^ytcpanel_(.*)$/))) {
            var name = match[1];
            var value = '';
            if (el.tagName.toLowerCase() == 'input' && (el.type.toLowerCase() == 'radio' || el.type.toLowerCase() == 'checkbox')) {
                if (el.checked) value = el.value;
            } else {
                value = el.value;
            }
            if (trim(value)) {
                if (usersetting[name]) {
                    if (value) usersetting[name] = value + ',' + usersetting[name];
                } else {
                    usersetting[name] = value;
                }
            }
        }
    }

    for (var k in usersetting) {
        name = TMPL_NAME + '_' + k;
        value = usersetting[k];
        createCookie(name, value, 365);
    }

    if (window.location.href.indexOf('?') > -1) window.location.href = window.location.href.substr(0, window.location.href.indexOf('?'));
    else window.location.reload();
}
 jQuery(document).ready(function($){  
         if($('#ytech_left').length) {
            $('#ytech_mommenu .btn2.leftsidebar').css('display', 'inline-block').on('click', function(){
                if($('#ytech_left').hasClass('active')){
                    $(this).find('.overlay').fadeOut(250);
                    $('#ytech_left').removeClass('active');
                    $('body').removeClass('show-sidebar');
                } else {
                    $('#ytech_left').addClass('active');
                    $(this).find('.overlay').fadeIn();
                    $('body').addClass('show-sidebar');
                }
            });
        }
        if($('#ytech_right').length) {
            $('#ytech_mommenu .btn2.rightsidebar').css('display', 'inline-block').on('click', function(){
                if($('#ytech_right').hasClass('active')){
                    $(this).find('.overlay').fadeOut(250);
                    $('#ytech_right').removeClass('active');
                    $('body').removeClass('show-sidebar');
                } else {
                    $('#ytech_right').addClass('active');
                    $(this).find('.overlay').fadeIn(250);
                    $('body').addClass('show-sidebar');
                }
            });
        }

 });
// reponsiver
jQuery(document).ready(function ($) {
    $(window).on('load resize change', function(event){
        Move();
    });

    function Move() {
        if($(window).width() < 992) {
            $('.header-style-1 .sm-serachbox-pro , .header-style-1 .setting-right').prependTo($('.header-style-1 .header-top .yt-menu'));
      }
        else {
            $('.header-style-1 .sm-serachbox-pro').prependTo($('.header-style-1 .search-pro'));
            $('.header-style-1 .setting-right').prependTo($('.header-style-1 .header-bottom .pull-right'));
        }
        if($(window).width() < 992) {
             $(' .header-style-2 .minicart-header ,.header-style-2 .search-wrapper ,  .header-style-2 .setting , .header-style-2 .socials-wrap ').prependTo($('.header-style-2  .yt-menu .block-right-mobile'));

      }
        else {
            $(' .header-style-2 .block-right-mobile  .minicart-header,  .header-style-2 .block-right-mobile .setting').prependTo($('.header-style-2 .wrapper-cart-setting'));
            $(' .header-style-2 .block-right-mobile  .socials-wrap,  .header-style-2 .block-right-mobile .search-wrapper').prependTo($('.header-style-2 .menu-larger .col-lg-12.col-md-12'));
        }
         if($(window).width() < 992) {
                 $(' .header-style-3  .setting ,  .header-style-3  .minicart-header ,  .header-style-3  .search-wrapper ').prependTo($('.header-style-3  .yt-menu .block-right-mobile'));
          }
         else {
            $('.header-style-3  .yt-menu  .setting').prependTo($(' .header-style-3  .header-top-left'));
            $('.header-style-3  .yt-menu  .minicart-header').prependTo($(' .header-style-3  .header-top-right'));
            $('.header-style-3  .yt-menu  .setting').prependTo($(' .header-style-3  .header-top-left'));
            $('.header-style-3  .yt-menu  .search-wrapper').prependTo($(' .header-style-3  .menu-larger  .col-lg-12.col-md-12'));
     
        }
        if($(window).width() < 992) {
                 $(' .header-style-5  .setting ,  .header-style-5 .minicart-header ,  .header-style-5 .socials-wrap  , .header-style-5  .search-wrapper').prependTo($('.header-style-5  .yt-menu .block-right-mobile'));
          }
         else {
            $('.header-style-5  .yt-menu  .setting').prependTo($(' .header-style-5  .header-top-left'));
            $('.header-style-5  .yt-menu  .minicart-header , .header-style-5  .yt-menu  .socials-wrap').prependTo($(' .header-style-5  .header-top-right'));
             $('.header-style-5  .yt-menu  .search-wrapper').prependTo($(' .header-style-5 .menu-larger  .col-lg-12.col-md-12'));
        }
         if($(window).width() < 991) {
            $('.header-style-1 .yt-poscart .minicart-header' ).prependTo($('.header-style-1 .header-top .yt-menu'));
      }
        else {
            $('.header-style-1  .minicart-header').prependTo($('.header-style-1 .yt-poscart'));
          
        }
 }
});
