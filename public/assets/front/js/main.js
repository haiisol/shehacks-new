function setCookie(param, value, expired=false) {
    if (expired) {
        var d = new Date();
        d.setTime(d.getTime() + (expired * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
    }
    else {
        var expires = "expires=Fri, 31 Dec 9999 23:59:59 GMT";
    }
    document.cookie = param+"="+value+";"+expires+";path=/";
}

function getCookie(cookieName) {
    var name = cookieName+"=";
    var cookieArray = decodeURIComponent(document.cookie).split(';');
    for(var i = 0; i < cookieArray.length; i++) {
        var cookie = cookieArray[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return '';
}

var generate_rsa = new JSEncrypt({ default_key_size: 2048 });
function encrypt_url(string){var rsa = new JSEncrypt();rsa.setPublicKey(generate_rsa.getPublicKey());return encodeURIComponent(rsa.encrypt(string));}
function decrypt_url(string){var rsa = new JSEncrypt();rsa.setPrivateKey(generate_rsa.getPrivateKey());return rsa.decrypt(decodeURIComponent(string));}

function scrollTop() {
    $('html, body').animate({ scrollTop: $('html').offset().top }, 0);
}

function getUrlVisit() {
    var a = window.location.hostname.replace(/^https?:\/\//,'');
    var b = a.replace(/^www\./,'');
    var domain = b.split('/')[0];
        
    var url = domain + window.location.pathname;
    
    return url;
}

function lazyload()
{
    $('.lazyload').Lazy({
        effect: 'fadeIn',
        effectTime: 800,
        threshold: 200,
        visibleOnly: false,
        combined: true,
        placeholder: ""
    });
}
        
(function ($) {
    'use strict';

    // $(document).ajaxSuccess(function(event, xhr, settings ) {
    //     $(xhr.responseText).find('img.lazyload').lazyload({
    //         effect: 'fadeIn',
    //         effectTime: 800,
    //         threshold: 200,
    //         visibleOnly: true,
    //         combined: true,
    //         placeholder: "aaaaa"
    //     });
    // });
    
    $(document).ready(function () {
        
        lazyload()

        // header
        function windowScroll() {

            const nv    = document.getElementById('navbar');
            var target  = $('#navbar');
          
            if (document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50) {
                nv.classList.add('shrink');
                
                if($(target).hasClass('collapse-active')) {
                } else {
                    $('#image-logo-default').addClass('d-none');
                    $('#image-logo-dark').removeClass('d-none');
                }
            } else {
                nv.classList.remove('shrink');

                if($(target).hasClass('collapse-active')) {
                } else {
                    $('#image-logo-default').removeClass('d-none');
                    $('#image-logo-dark').addClass('d-none');
                }
            }
        }

        window.addEventListener('scroll', (ev) => {
            ev.preventDefault();
            windowScroll();
        })

        $(document).on('click', '.navbar-toggler', function () {
            var target = $('#navbar');

            if($(target).hasClass('collapse-active')) {
                $(target).removeClass('collapse-active');
                $('#image-logo-default').removeClass('d-none');
                $('#image-logo-dark').addClass('d-none');

            } else {
                $(target).addClass('collapse-active');
                $('#image-logo-default').addClass('d-none');
                $('#image-logo-dark').removeClass('d-none');
            }
        });

        $(document).on('click', '.navbar-collapse.show', function (e) {
            var elemenet = $(e.target);
            
            if(elemenet.is('a') && elemenet.attr('href') != 'javascript:void(0)' && elemenet.attr('class') != 'dropdown-toggle') {
                $(this).collapse('hide');
                close_navbar_collapse()
            }
            else {
                var sub_menu = elemenet.siblings();

                if (sub_menu.hasClass('open')) {
                    sub_menu.removeClass('open');
                    sub_menu.slideUp(600, 'swing');
                }
                else {
                    sub_menu.addClass('open');
                    sub_menu.slideDown(600, 'swing');
                }
            }
        });

        function close_navbar_collapse() {
            var mn = $('#navbar');
            var tg = $('.navbar-toggler');
            var nc = $('.navbar-collapse');

            if (mn.hasClass('collapse-active')) {
                mn.removeClass('collapse-active');
            }

            if (!tg.hasClass('collapsed')) {
                tg.addClass('collapsed');
                tg.attr('aria-expanded', 'false');
            }

            if (nc.hasClass('show')) {
                nc.removeClass('show');
            }
        }


        // anchor redirect
        $(document).on('click', '.anchor[href*="#"]:not([href="#"])',function(e) {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var t = $(this.hash);
                t = t.length ? t : $('[name=' + this.hash.slice(1) + ']');
                if (t.length) {
                    $('html, body').animate({
                        scrollTop: t.offset().top
                    }, 800, 'easeInOutExpo');
                    return false;
                }
            }
        });
        
        // validate filesize
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param) 
        });

        // validation phone
        $('.val-telp').on('input propertychange paste', function (e) {
            var val = $(this).val()
            var reg = /^0/gi;
            if (val.match(reg)) {
                $(this).val(val.replace(reg, ''));
            }
        });

        // validation capitalize
        $('.val-capitalize').on('input propertychange paste', function (e) {
            var str = $(this).val()
            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(str);
        });

        // validation lowercase
        $('.val-lowercase').on('input propertychange paste', function (e) {
            var str = $(this).val()
            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toLowerCase();
            });
            $(this).val(str);
        });
        
        // show password text
        $(document).on('click', '.show-password', function() {
            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $($(this).attr('toggle'));
            
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
        
        // video magnific popup
        if ($('.popup-video').length) {
            $('.popup-video').magnificPopup({
                disableOn: 320,
                type: 'iframe',
                removalDelay: 150,
                preloader: true,
                fixedContentPos: false,
                zoom: {
                    enabled: true,
                    duration: 400
                }
            });
        }

        // image magnific popup
        if ($('.popup-image').length) {
            $('.popup-image').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                closeBtnInside: true,
                fixedContentPos: true,
                mainClass: 'mfp-no-margins mfp-with-zoom',
                image: {
                    verticalFit: true
                },
                zoom: {
                    enabled: true,
                    duration: 400
                }
            });
        }

        // gallery magnific popup
        $('.gallery-item').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: true,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom',
            gallery: {
                enabled: true
            },
            image: {
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 400
            }
        });

        // sticky sidebar
        $('.sticky-sidebar').theiaStickySidebar({
            additionalMarginTop: 70
        });
            
        // button loader
        $.fn.buttonLoader = function(action) {
            var self = $(this);

            //start loading animation
            if (action == 'start') {
                
                $(self).attr('disabled', true);
                $(self).attr('data-btn-text', $(self).text());
                $(self).html('<span class="loading-area"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="text">Loading ...</span></span>');
                $(self).addClass('active');
            }

            //stop loading animation
            if (action == 'stop') {
                $(self).html($(self).attr('data-btn-text'));
                $(self).removeAttr('disabled');
                $(self).removeClass('active');
            }
        }

        // alert notification
        $.fn.alertNotification = function(param, message) {

            if (param == 'accent') {
                var icon = '<i class="lni lni-checkmark"></i>';
            } else if (param == 'second') {
                var icon = '<i class="lni lni-checkmark"></i>';
            } else if (param == 'success') {
                var icon = '<i class="lni lni-checkmark"></i>';
            } else if (param == 'info') {
                var icon = '<i class="lni lni-information"></i>';
            } else if (param == 'warning') {
                var icon = '<i class="lni lni-warning"></i>';
            } else if (param == 'danger') {
                var icon = '<i class="lni lni-close"></i>';
            }

            var html = 
                '<div class="alert alert-dismissible fade show">'+
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'+
                    '<div class="alert-inner text-'+param+'">'+
                        '<div class="alert-icon">'+icon+'</div>'+
                        '<div class="alert-text"><span>'+message+'</span></div>'+
                    '</div>'+
                '</div>';

            $(this).html(html);
        }

        // not found item
        $.fn.notfounditem = function(message, description) {
            
            var get_url = window.location.origin;

            var res =
                '<div class="no-more-result">'+
                    '<img src="https://bekup.kemenparekraf.go.id/assets/front/img/not-found.webp" class="img-fluid">'+
                    '<h2 class="section-description-xl">'+message+'</h2>'+
                    '<p class="section-description-md">'+description+'</p>'+
                '</div>';
            
            $(this).html(res);
        }

        // widget whatsapp
        $('.trigg-whatsapp').on('click', function () {
            var dp = $(this).attr('data-phone');
            var dm = $(this).attr('data-message');

            if (dm) {
                var m = '&text='+dm;
            } else {
                var m = '';
            }

            window.open("https://api.whatsapp.com/send?phone=" + dp + m);
            
            return false;
        });

        // scroll up
        $(document).on('scroll', scrollUp);

        function scrollUp(e) {
            e.preventDefault();

            var trigg_scrolltop = $('#back-to-top');

            if( document.body.scrollTop > 500 || document.documentElement.scrollTop > 500 ) {
                trigg_scrolltop.addClass('active');
            } else {
                trigg_scrolltop.removeClass('active');
            }
        }

        // scroll top
        $('#back-to-top').on('click', function() {
            $('html, body').animate({
                scrollTop: $('html').offset().top
            }, 0);
        });

        // ------------------------------------ share social media ------------------------------------
            $(document).on('click', '.post-share-facebook', function() {
                var t = $(this).attr('data_title');
                var u = $(this).attr('data_url');

                window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;
            });

            $(document).on('click', '.post-share-twitter', function() {
                var t = $(this).attr('data_title');
                var u = $(this).attr('data_url');
                
                window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(u)+'&text='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;
            });

            $(document).on('click', '.post-share-linkedin', function() {
                var u = $(this).attr('data_url');

                window.open('https://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(u),'sharer','toolbar=0,status=0,width=626,height=436');return false;
            });

            $(document).on('click', '.post-share-whatsapp', function() {
                var t = $(this).attr('data_title');
                var u = $(this).attr('data_url');

                if ($(window).width() > 960) {
                    window.open('https://web.whatsapp.com/send?text='+encodeURIComponent(u)+' - '+encodeURIComponent(t),'?utm_source=whatsapp&utm_medium=sharer&utm_campaign=social');return false;
                } else {
                    window.open('whatsapp://send?text='+encodeURIComponent(u)+' - '+encodeURIComponent(t),'?utm_source=whatsapp&utm_medium=sharer&utm_campaign=social');return false;
                }
            });
        
            $(document).on('click', '.post-share-telegram', function() {
                var t = $(this).attr('data_title');
                var u = $(this).attr('data_url');

                window.open('https://t.me/share/url?url='+encodeURIComponent(u)+'&text='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;
            });

            $(document).on('click', '.post-share-googleplus', function() {
                var u = $(this).attr('data_url');

                window.open('https://plus.google.com/share?url='+encodeURIComponent(u),'sharer','toolbar=0,status=0,width=626,height=436');return false;
            });
        // ------------------------------------ end share social media ------------------------------------

        
    });
})(jQuery);