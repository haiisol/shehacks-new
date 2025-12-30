<!DOCTYPE html>
<html lang="en">

<?php
$meta_title = strip_tags($title);
$meta_description = strip_tags($description);
$meta_keywords = strip_tags($keywords);

// Kondisi demo 2024
if ($event_running == 'false') {
    if ($page != 'home_announcement' and $page != 'page/coming_soon' and $page != 'artikel/artikel' and $page != 'page/faq' and $page != 'page/contact' and $page != 'page/webinar') {
        $running_event = 'show';
    } else {
        $running_event = 'hide';
    }
} else {
    $running_event = 'show';
}
?>

<head>

    <!-- Meta Pixel Code -->
    <script>
        ! function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1609763062567641');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1609763062567641&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <!-- TikTok Pixel Code Start -->
    <script>
        ! function (w, d, t) {
            w.TiktokAnalyticsObject = t;
            var ttq = w[t] = w[t] || [];
            ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie", "holdConsent", "revokeConsent", "grantConsent"], ttq.setAndDefer = function (t, e) {
                t[e] = function () {
                    t.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                }
            };
            for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
            ttq.instance = function (t) {
                for (
                    var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
                return e
            }, ttq.load = function (e, n) {
                var r = "https://analytics.tiktok.com/i18n/pixel/events.js",
                    o = n && n.partner;
                ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = r, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {};
                n = document.createElement("script");
                n.type = "text/javascript", n.async = !0, n.src = r + "?sdkid=" + e + "&lib=" + t;
                e = document.getElementsByTagName("script")[0];
                e.parentNode.insertBefore(n, e)
            };

            ttq.load('CPUDMLBC77UF05LN3PLG');
            ttq.page();
        }(window, document, 'ttq');
    </script>
    <!-- TikTok Pixel Code End -->

    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#EC008C">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo $meta_description; ?>">
    <meta name="author" content="Bantu Teknologi Indonesia">
    <title><?php echo $meta_title; ?></title>

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#EC008C">
    <meta name="apple-mobile-web-app-title" content="<?php echo $meta_title; ?>">
    <link rel="apple-touch-icon" href="<?php echo $favicon_img; ?>">
    <link rel="shortcut icon" href="<?php echo $favicon_img; ?>">
    <link rel="icon" href="<?php echo $favicon_img; ?>" type="image/x-icon">

    <meta class="foundation-data-attribute-namespace">
    <meta class="foundation-mq-xxlarge">
    <meta class="foundation-mq-xlarge-only">
    <meta class="foundation-mq-xlarge">
    <meta class="foundation-mq-large-only">
    <meta class="foundation-mq-large">
    <meta class="foundation-mq-medium-only">
    <meta class="foundation-mq-medium">
    <meta class="foundation-mq-small-only">
    <meta class="foundation-mq-small">
    <meta class="foundation-mq-topbar">

    <!-- open graph -->
    <link rel="canonical" href="https://shehacks.ioh.co.id/" />
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo $meta_title; ?>" />
    <meta property="og:description" content="<?php echo $meta_description; ?>" />
    <meta property="og:url" content="https://shehacks.ioh.co.id/" />
    <meta property="og:site_name" content="<?php echo $site_name; ?>" />
    <meta property="og:image" content="<?php echo $favicon_og; ?>" />
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="641">
    <meta property="og:image:height" content="452">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">

    <link href="<?php echo base_url(); ?>assets/front/vendor/select2/css/select2.min.css" rel="stylesheet"
        type="text/css">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="<?php echo base_url(); ?>assets/front/css/style.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url(); ?>assets/front/vendor/jquery/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script> -->
    <script defer src="<?php echo base_url(); ?>assets/front/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script defer src="<?php echo base_url(); ?>assets/front/vendor/jquery-validation/additional-methods.js"></script>
    <script defer src="<?php echo base_url(); ?>assets/front/js/plugins.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/2.3.1/jsencrypt.min.js"
        integrity="sha512-zDvrqenA0eFJZCxBsryzUZcvihvNlEXbteMv62yRxdhR4s7K1aaz+LjsRyfk6M+YJLyAJEuuquIAI8I8GgLC8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- G-ZX39TW5V2R -->
    <!-- G-F2PZLW29CG -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZX39TW5V2R"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-ZX39TW5V2R');
    </script>
</head>

<body oncontextmenu="return true" class="">

    <?php if ($page == 'page/coming_soon' or $page == 'page/under_construction') {
        $wsc = '';
    } else {
        $wsc = 'style-2';
    } ?>

    <main class="wrap-site <?php echo $wsc; ?>" id="wrap-site">
        <?php
        $exclude = ['page/coming_soon', 'page/under_construction'];

        echo view('component/layout/csrf_handle');

        if (!in_array($page, $exclude)) {
            echo view('component/layout/navbar');
        }

        echo view($page);

        if (!in_array($page, $exclude)) {
            echo view('component/layout/footer');
        }
        ?>
    </main>

    <button type="button" id="back-to-top" class="back-to-top"></button>

    <?php if ($page == 'home_announcement' or $page == 'page/coming_soon') {
    } else { ?>
        <div class="announce-fixed close" id="load_data_announce_visit"></div>
    <?php } ?>

    <script>
        function trigger_cta_event(data) {
            var url_visit = getUrlVisit();

            setTimeout(function () {
                $.ajax({
                    method: 'POST',
                    url: '<?php echo base_url(); ?>analytic/post_cta_btn',
                    data: {
                        data: data,
                        url: url_visit
                    },
                    dataType: 'json',
                    success: function (res) {}
                });
                return false;
            }, 2000);
        }

        $(document).on("ajaxSuccess", function(event, jqXHR, ajaxOptions, res) {
           $('input[name="' + res.csrf_name + '"]').val(res.csrf_hash);
        });

        $(document).ready(function () {

            var page = '<?php echo $page; ?>';

            // --------------------------- post visitors ---------------------------
            var url_visit = getUrlVisit();
            setTimeout(post_visitor, 4000);

            function post_visitor() {
                $.ajax({
                    method: 'POST',
                    url: '<?php echo base_url(); ?>analytic/post_visitors',
                    data: {
                        page: '<?php echo $page; ?>',
                        url: url_visit
                    },
                    dataType: 'json',
                    success: function (res) {
                        
                    }
                });
            }

            $(document).on('click', '.cta-btn-trigger', function (e) {
                e.preventDefault();

                var data = $(this).attr('data');
                var href = $(this).attr('href');
                var toggle = $(this).attr('data-bs-toggle');

                $.ajax({
                    method: 'POST',
                    url: '<?php echo base_url(); ?>analytic/post_cta_btn',
                    data: {
                        data: data,
                        url: url_visit
                    },
                    dataType: 'json',
                    success: function (res) {
                        setTimeout(function () {
                            if ((toggle) && (toggle !== 'undefined')) {
                                $(toggle).modal('show');
                            } else {
                                var target = $(this).attr('target');
                                if ((target) && (target !== 'undefined')) {
                                    if (target == "_blank") {
                                        window.open(href, '_blank').focus();
                                    } else {
                                        top.location.href = href;
                                    }
                                } else {
                                    top.location.href = href;
                                }
                            }
                        }.bind(this), 500);
                    }
                });
                return false;
            });
            // --------------------------- end post visitors ---------------------------


            // --------------------------- announce visit ---------------------------
            if (page === 'page/under_construction') {

            } else {
                var cann = getCookie('cann');
                if (cann !== 'true') {
                    load_data_announce_visit();
                }
            }

            function load_data_announce_visit() {
                $('#load_data_announce_visit').toggleClass('close show');
                load_data = `
                        <div class="container">
                            <div class="inner">
                                <div class="content">
                                    <div class="txt">
                                        <p class="section-description-md">Hi! Kami senang Anda berkunjung! Kami ingin memberitahu Anda bahwa kami akan merekam aktivitas Anda untuk memastikan pengalaman yang lebih baik di situs kami.</p>
                                    </div>
                                    <div class="act">
                                        <a href="#" onclick="event.preventDefault();" class="btn btn-padd-sm px-4 trigg-announce"><span>OK</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                $('#load_data_announce_visit').html(load_data);
            }

            $(document).on('click', '.trigg-announce', function (e) {
                e.preventDefault();
                $('#load_data_announce_visit').toggleClass('show close');
                setCookie('cann', 'true', 7);
            });
            // --------------------------- end announce visit ---------------------------
        });
    </script>

    <link href="<?php echo base_url(); ?>assets/front/vendor/lineicons/lineicons.min.css" rel="stylesheet"
        type="text/css" rel="preconnect">
    <!-- <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> -->
    <link href="<?php echo base_url(); ?>assets/front/vendor/fontawesome/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">

    <script defer src="<?php echo base_url(); ?>assets/front/vendor/jquery.theia.sticky.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script defer src="<?php echo base_url(); ?>assets/front/js/main.js"></script>

    <!-- Floodlight Tag -->
    <script type="text/javascript">
        var axel = Math.random() + "";
        var a = axel * 10000000000000;
        document.write('<iframe src="https://9410194.fls.doubleclick.net/activityi;src=9410194;type=indos0;cat=sheha0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');
    </script>

    <noscript>
        <iframe
            src="https://9410194.fls.doubleclick.net/activityi;src=9410194;type=indos0;cat=sheha0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=1?"
            width="1" height="1" frameborder="0" style="display:none"></iframe>
    </noscript>
    <!-- End of Floodlight Tag: Please do not remove -->

</body>

</html>