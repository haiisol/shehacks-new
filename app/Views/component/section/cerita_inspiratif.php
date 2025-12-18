<?php $web = $this->main_model->get_admin_web(); ?>

<section class="testimonial-section section section-lg wrap-cerita-alumni">
    <div class="container">
        <div class="head-inner">    
            <div class="section-title white">
                <h2 class="title section-heading">Cerita Inspiratif Alumni</h2>
            </div>
        </div>
        <div class="row mt-5" id="load_data_cerita_alumni"></div>
    </div>
</section>


<script type="text/javascript">
    $.ajax({
        url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_artikel_alumni',
        dataType : 'json',
        beforeSend: function() {},
        success: function(response) {
            var load_data = '';
            
            if (response.status === 1) {
                if (response.data.length > 0) {
                    $.each(response.data, function(i, val) {
                    
                    load_data += `
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="post">
                                <a href="${val.url_detail}" class="head">
                                    <img src="<?= before_load(); ?>" data-src="${val.image}" alt="${val.heading}" class="img-fluid lazyload post-img" width="382" height="300">
                                    <span class="badge badge-white">${val.kategori}</span>
                                </a>

                                <div class="body">
                                    <a href="${val.url_detail}">
                                        <h2 class="post-title section-description">${val.heading}</h2>
                                    </a>
                                    <div class="meta-info mb-0">
                                        <div class="text section-description-sm"><i class="icon lni lni-user"></i>Admin Shehacks</div>
                                        <div class="text section-description-sm"><i class="icon lni lni-calendar"></i>${val.tanggal}</div>
                                    </div>
                                </div>
                            </div>
                        </div>`;


                        '<div class="col-lg-4 col-md-6 mb-4">'+
                            '<div class="post">'+
                                '<div class="head"> '+   
                                    '<a href="'+val.url_detail+'">'+
                                        '<img src="<?= before_load(); ?>" data-src="'+val.image+'" alt="'+val.heading+'" class="img-fluid lazyload post-img" width="335" height="300">'+
                                    '</a>'+
                                    '<span class="badge badge-white">'+val.kategori+'</span>'+
                                '</div>'+

                                '<div class="body">'+
                                    '<a href="'+val.url_detail+'">'+
                                        '<h2 class="post-title section-description">'+val.heading+'</h2>'+
                                    '</a>'+
                                    '<div class="meta-info mb-0">'+
                                        '<div class="text section-description-sm"><i class="icon lni lni-user"></i>Admin Shehacks</div>'+
                                        '<div class="text section-description-sm"><i class="icon lni lni-calendar"></i>'+val.tanggal+'</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                    });

                    $('#load_data_cerita_alumni').html(load_data);
                } else {
                    $('.wrap-cerita-alumni').addClass('d-none');
                }
            }

            lazyload();
        }
    });
</script>