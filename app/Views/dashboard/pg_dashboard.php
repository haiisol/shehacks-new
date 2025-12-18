<?php 
    $query_user = $this->db->query("SELECT * FROM tb_user WHERE id_user=".decrypt_url($id_user)." ")->row_array();
?>
<div class="card card-style">
    <div class="card-body">
        <div class="banner-dashboard" id="load_data"></div>

        <?php if ($query_user['kategori_user'] != "") { ?>
            <div class="education-section">
                <div class="section-title style-2" id="menu_hide">
                    <div>
                        <h2 class="title section-heading-xs">Modul Edukasi</h2>
                    </div>

                    <div>
                        <div class="filter-area">
                            <form method="post" id="form-filter" class="form-style">
                                <div class="form-group">
                                    <input type="text" name="search_fil" placeholder="Cari modul .." class="form-control">
                                </div>
                                <div class="form-group act">
                                    <button type="submit" id="submit-form-filter" class="btn px-2 w-100"><span>Cari</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="load_data_modul" class="modul-area"></div>
                <div id="load_data_modul_message"></div>

                <div class="text-center mt-5">
                    <button type="button" id="load_data_modul_more" class="btn btn-light btn-hover-arrow-right d-none"><span>Load More</span></button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
	    // --------------------------- load data about ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_banner_popup',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {

                            load_data += '<img src="'+val.image+'" alt="banner" class="image-banner img-fluid lazyload">';
                            // load_data += '<img src="https://shehacks.id/file_media/image-content/banner-popup-D3JV01ZS5P.png" class="image-banner img-fluid lazyload">';
                        });
                    }

                    $('#load_data').html(load_data);
                }
            });
        // --------------------------- end load data about ---------------------------
    });
</script>

<script>
    $(document).ready(function() {

        var limit = 9;
        var start = 0;

        var data_user = '<?php echo $query_user['kategori_user']; ?>';

        if (data_user == "Ideasi") {
            load_data_modul(limit, start, 'html');
        } else {
            $('#menu_hide').addClass('d-none');
        }

        function load_data_modul(limit, start, load)
        {
            var search = $('input[name="search_fil"]').val();
            
            $.ajax({
                url      : '<?php echo base_url();?>dashboard/dashboard/fetch_data_modul',
                data     : { limit:limit, start:start, search:search },
                dataType : 'json',
                cache    : false,
                success:function(response) {
                    $('#load_data_modul_more').buttonLoader('stop');

                    if (response.status == 1) {
                        
                        if(response.data.length > 0) {
                            
                            var load_data_modul = '';

                            $.each(response.data, function(i, val) {

                                if (val.url_sertifikat) {
                                    var class_learn_btn = '';
                                    // var sertifikat      = '';
                                    var sertifikat = '<div class="sert"><a href="'+val.url_sertifikat+'" class="btn btn-padd-md btn-second px-1 sertifikat" target="_blank"><i class="lni lni-certificate"></i> <span>Sertifikat</span></a></div>';
                                } else {
                                    var class_learn_btn = 'w-100';
                                    var sertifikat      = '';
                                }

                                load_data_modul +=
                                    '<div class="modul-item">'+
                                        '<div class="card-modul">'+
                                            '<div class="modul-header">'+
                                                '<a href="'+val.url_detail+'">'+
                                                    '<img src="'+val.cover+'" class="img-fluid img-thumb">'+
                                                '</a>'+
                                            '</div>'+

                                            '<div class="modul-body">'+
                                                '<ul class="meta-info">'+
                                                    '<li>'+
                                                        '<span class="icon"><i class="lni lni-video"></i></span>'+
                                                        '<span>'+val.total_video+' Video</span>'+
                                                    '</li>'+
                                                '</ul>'+

                                                '<a href="'+val.url_detail+'">'+
                                                    '<h2 class="title section-description-md text-limit-2-row">'+val.modul+'</h2>'+
                                                '</a>'+

                                                '<div class="act">'+
                                                    '<div>'+
                                                        '<a href="'+val.url_edukasi+'" class="btn btn-padd-md px-1 '+class_learn_btn+'"><span>Mulai Belajar</span></a>'+
                                                    '</div>'+
                                                    sertifikat+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';

                            });

                            if (load == 'append') {
                                $('#load_data_modul').append(load_data_modul);
                            } else {
                                $('#load_data_modul').html(load_data_modul);
                            }

                            if (response.load_more == 1) {
                                $('#load_data_modul_more').removeClass('d-none');
                            } else {
                                $('#load_data_modul_more').addClass('d-none');
                            }
                            
                            $('#load_data_modul_message').html('');
                        } 
                        else {
                            $('#load_data_modul_message').html('<div class="no-more-result"><img src="<?php echo base_url();?>assets/front/img/not-found.webp"><span>No More Result Found</span></div>');
                        }
                    }
                }
            })
        }

        $('#form-filter').submit(function(e) {
            e.preventDefault();
            trigger_cta_event('Filter Modul');

            start = 0;
            load_data_modul(limit, start, 'html');
        });

        $(document).on('click', '#load_data_modul_more', function(event) {
            event.preventDefault();
            $(this).buttonLoader('start');

            start = start+limit;
            load_data_modul(limit, start, 'append');
        });

        function trigger_cta_event(data){
            var url_visit = getUrlVisit();
            
            $.ajax({
                 method   : 'POST',
                 url      : '<?php echo base_url();?>analytic/post_cta_btn',
                 data     : { data:data, url:url_visit },
                 dataType : 'json',
                 success:function(response) {

                 }
            });
            return false;
        }
    });
</script>