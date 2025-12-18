<section class="modul-single section section-sm">
    <div class="container">

        <div class="head">    
            <img class="img-fluid lazyload cover cr_cover">
            <div class="content">
                <div class="row">
                    <div class="col-lg-9">
                        <h2 class="heading section-heading cr_modul"></h2>
                        <ul class="meta-info" id="cr_header_meta"></ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="body">
            <div class="row">
                <div class="col-xl-4 col-lg-5 order-lg-1 mb-lg-0 mb-5 sticky-sidebar">
                    <div class="course-sidebar">
                        <div class="course-widget">
                            <div class="course-thumbnail">
                                <img class="img-fluid cr_cover">
                            </div>

                            <div class="course-sidebar-details">
                                <ul class="course-sidebar-list">
                                    <li>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="label"><i class="ti ti-user"></i>Package</span>
                                            <span class="value" id="cr_package_name"></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="label"><i class="ti ti-layout-list-thumb"></i>Pre Test</span>
                                            <span class="value" id="cr_pretest"></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="label"><i class="ti ti-video-camera"></i>Video</span>
                                            <span class="value" id="cr_total_video"></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="label"><i class="ti ti-layout-list-thumb"></i>Post Test</span>
                                            <span class="value" id="cr_posttest"></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="label"><i class="ti ti-bookmark-alt"></i>Sertifikat</span>
                                            <span class="value" id="cr_sertifikat"></span>
                                        </div>
                                    </li>
                                </ul>
                    
                                <div class="course-action">
                                    <a href="javascript:void(0)" class="btn btn-padd btn-accent px-3 w-100" id="cr_url_edukasi"><span>Mulai Belajar</span></a>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>

                <div class="col-xl-8 col-lg-7 order-lg-0">
                    <div class="single-course-details">
                        <h4 class="course-title section-heading-xs">Description</h4>
                        <p class="course-description reset-style" id="cr_description"></p>
                    </div>

                    <div class="my-5"></div>

                    <ul class="curriculum-area">
                        <li class="curriculum-item">
                            <div class="curriculum-header">
                                <h5 class="curriculum-title section-description">Materi Video</h5>
                            </div>
            
                            <ul class="curriculum-list" id="cr_video"></ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</section>


<script>
    $(document).ready(function() {

        var logged_in_front = '<?php echo $this->session->userdata('logged_in_front'); ?>';
        var id_modul_enc = '<?php echo $id_modul_enc; ?>';

        load_data();

        function load_data()
        {
            $.ajax({
                method   : 'POST',
                url      : '<?php echo base_url();?>modul/modul/fetch_data_modul_detail',
                data     : { id_modul_enc:id_modul_enc },
                dataType : 'json',
                success:function(response) {
                    
                    var video = '';

                    $.each(response.data_video, function(i, val) {
                        video += 
                            '<li class="course-item course-video has-status">'+
                                '<span class="item-name">'+val.judul_video+'</span>'+
                                // '<div class="course-item-meta">'+
                                //     '<span class="item-meta duration">10.30 min</span>'+
                                //     '<i class="item-meta course-item-status"></i>'+
                                // '</div>'+
                            '</li>';
                    });

                    var header_meta =
                        '<li>'+
                            '<div class="course-author">'+
                                '<img src="'+response.photo_admin+'" alt="" class="img-fluid img-user-sm">'+
                                '<span>'+response.nama_admin+'</span>'+
                            '</div>'+
                        '</li>';
                    
                    if (response.pretest) {
                        var pretest = response.pretest;
                    } else {
                        var pretest = '<i class="fa fa-close"></i>';
                    }

                    if (response.posttest) {
                        var posttest = response.posttest;
                    } else {
                        var posttest = '<i class="fa fa-close"></i>';
                    }

                    if (response.sertifikat == 1) {
                        var sertifikat = '<i class="fa fa-check"></i>';
                    } else {
                        var sertifikat = '<i class="fa fa-close"></i>';
                    }

                    if (logged_in_front == true) {
                        var url_edukasi = response.url_edukasi;
                    } else {
                        var url_edukasi = '<?php echo base_url();?>login';
                    }

                    $('.cr_modul').html(response.modul);
                    $('#cr_header_meta').html(header_meta);
                    $('#cr_description').html(response.deskripsi_modul);
                    $('#cr_url_edukasi').attr('href', url_edukasi);
                    $('#cr_package_name').html(response.package_name);

                    $('.cr_cover').attr('src', response.cover);
                    $('.cr_cover').attr('alt', response.modul);
                    $('#cr_total_video').html(response.total_video);
                    $('#cr_pretest').html(pretest);
                    $('#cr_posttest').html(posttest);
                    $('#cr_sertifikat').html(sertifikat);
                    $('#cr_video').html(video);
                }
            })
        }

    });
</script>