<section class="dash-section section section-sm">
    <div class="container">

        <div class="row">
            <div class="col-lg-3 sticky-sidebar">
                <div class="dash-sidebar-head" id="load_data_user"></div>

                <ul class="dash-sidebar nav scrollable h-scrollable">
                    <li class="nav-item">
                        <a href="javascript:void(0)" id="dashboard" class="nav-link section-description-md get-page">
                            <i class="icon fa-solid fa-house"></i> <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" id="profile" class="nav-link section-description-md get-page">
                            <i class="icon fa-solid fa-user-pen"></i> <span class="text">Profile</span>
                        </a>
                    </li>
                    <?php 
                    if ($data_user['kategori_user'] == 'MVP') { ?>
                    <li class="nav-item">
                        <a href="javascript:void(0)" id="pilot_project" class="nav-link section-description-md get-page">
                            <i class="icon fa-solid fa-briefcase"></i> <span class="text">Pilot Project</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a href="javascript:void(0)" id="password" class="nav-link section-description-md get-page">
                            <i class="icon fa-solid fa-unlock"></i> <span class="text">Password</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-9">
                <div class="dash-content">
                    <div id="show-page"></div>
                </div>
            </div>
        </div>

    </div>
</section>

<div class="modal modal-style style-2 fade banner-popup" id="popup-event" aria-hidden="true" aria-labelledby="ModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="inner-modal">
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="banner-popup-area announcement-area">
                    <div class="thumb">
                        <img src="<?php echo base_url();?>assets/front/img/icon/img-1.webp" alt="Your Future Image" class="img-fluid lazyload">
                    </div>
                    
                    <div class="content">
                        <div class="section-title mb-0">    
                            <h2 class="title section-heading-sm">Selamat datang di Event Shehack 2025</h2>
                            <p class="description section-description-md">Sebuah program inovasi untuk perempuan Indonesia yang bertujuan untuk menutup kesenjangan gender dalam hasil ekonomi, pendidikan, kesehatan, dan lingkungan dengan memanfaatkan teknologi.</p>
                        </div>

                        <div class="form-actions text-center">
                            <a href="javascript:void(0)" class="btn w-100 mb-2 btn-yes"><span>Ikuti dan Lengkapi data</span></a>
                            <a href="javascript:void(0)" class="btn btn-outline-accent w-100 btn-no"><span>Tidak</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        var get_url  = document.location.toString();
        var last_url = get_url.substr(get_url.lastIndexOf('?') + 1);

        if (get_url == '<?php echo base_url();?>dashboard' || get_url == '<?php echo base_url();?>dashboard#') {
            load_page('dashboard');
        } else {
            load_page(last_url);
        }

        var page_url = '<?php echo app_url(); ?>/';

        $(document).on('click', '.get-page', function(event) {
            event.preventDefault();
            
            var param_pg = $(this).attr('id');
            $('.get-page').removeClass('active');

            load_page(param_pg);
        });

        function load_page(param_pg) {
            $.ajax({
                url      : '<?php echo base_url();?>dashboard/dashboard/get_page',
                data     : { param_pg:param_pg },
                dataType : 'json',
                success: function(response) {

                    trigger_cta_event('Button menu '+param_pg);

                    $(document).attr("title", response.title);
                    $(document).find('meta[name=description]').attr('content', response.description);
                    $(document).find('meta[name=keywords]').attr('content', response.keywords);

                    $('#'+response.param_pg).addClass('active');
                    $(document).find('#show-page').html(response.page);

                    window.history.pushState("", "", page_url+response.url);
                    
                }
            })
        }

        load_data();

        function load_data() {
            $.ajax({
                url      : '<?php echo base_url();?>dashboard/dashboard/fecth_data_dashboard',
                dataType : 'json',
                success:function(response) {
                    $('#load_data_user').html(
                        '<p class="section-description fw-500 text-limit-1-row">'+response.nama+'</p>'+
                        '<p class="section-description-sm text-limit-1-row">'+response.email+'</p>'
                    );
                }
            })
        }

        // --------------------------- load popup-event ---------------------------
            get_modal_event();
            
            function get_modal_event() {
                $.ajax({
                    url      : '<?php echo base_url();?>dashboard/dashboard/get_modal_event',
                    dataType : 'json',
                    success: function(response) {
                        if (response.status == 1) {
                            setTimeout(function () {
                                $('#popup-event').modal('show');
                            }, 2000);
                        }
                    }
                });
            }

            $(document).on('click', '.btn-yes', function(event) {
                $('#btn-yes').buttonLoader('start');

                $.ajax({
                    url      : '<?php echo base_url();?>dashboard/dashboard/generate_channel',
                    dataType : 'json',
                    success:function(response) {
                        $('#btn-yes').buttonLoader('stop');

                        if (response.status == 1) {
                            $('#popup-event').modal('toggle'); 
                            setTimeout(function () {
                                top.location.href = response.redirect;
                            }, 1000);
                        } else {
                            $('#popup-event').modal('toggle'); 
                        }
                    }
                });
            });

            $(document).on('click', '.btn-no', function(event) {
                $('#btn-no').buttonLoader('start');

                $.ajax({
                    url      : '<?php echo base_url();?>dashboard/dashboard/close_modal_event',
                    dataType : 'json',
                    success:function(response) {
                        $('#popup-event').modal('toggle');
                        $('#btn-no').buttonLoader('stop');
                    }
                });
            });
        // --------------------------- end load popup-event ---------------------------
    });
</script>

