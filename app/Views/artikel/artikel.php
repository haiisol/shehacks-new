<section class="hero-section style-1 hero-md section">
    <img data-src="<?php echo base_url();?>assets/front/img/background/bg-hero.webp" alt="cover" class="img-fluid lazyload hero-cover">
    <div class="container">
        <div class="content center">
            <div class="row">
                <div class="col-lg-6 m-auto">        
                    <h2 class="title section-heading-lg">Artikel SheHacks</h2>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="startup-section section section-lg pt-5">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-lg-11 m-auto">        
                <div class="filter-area">
                    <form method="post" id="form-filter" class="form-style">
                        <div class="form-group blog">
                            <label for="search" class="control-label">Cari Artikel</label>
                            <input type="text" name="search" id="search" placeholder="Cari artikel" class="form-control">
                        </div>

                        <div class="form-group blog">
                            <label for="kategori" class="control-label">Kategori</label>
                            <select name="kategori" id="kategori" data-placeholder="Pilih kategori" data-allow-clear="false" class="form-control select-custome-search">
                                <option value="" readonly selected hidden>Pilih industri</option>
                                <?php $get_kategori = $this->db->query("SELECT * FROM tb_blog_kategori WHERE status_delete = 0 ORDER BY nama ASC")->result_array(); ?>
                                <?php foreach ($get_kategori as $key_kategori) { ?>
                                    <option value="<?php echo $key_kategori['id_blog_kategori']; ?>"><?php echo $key_kategori['nama']; ?></option>
                                <?php }; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" id="submit-form-filter" class="btn"><span>Filter</span></button>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
        
        <div class="inner">
            <div id="blog_feedback"></div>
            <div class="startup-area" id="load_data_blog"></div>

            <div class="load-more-area d-none" id="show_load_more">
                <a href="javascript:void(0)" id="btn-load-more" class="btn btn-arrow"><span>Load More</span></a>
            </div>
        </div>
    </div>
</section>


<script defer src="<?php echo base_url();?>assets/front/vendor/select2/js/select2.min.js"></script>
<script src="<?php echo base_url();?>assets/front/js/function.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        // --------------------------- load data blog ---------------------------
            localStorage.setItem('offset_blog', 0);

            var limit  = 12;
            var offset = localStorage.getItem('offset_blog');
            var filter = $('#form-filter').serialize();

            get_data(limit, offset, filter, 'html');

            function get_data(limit, offset, filter, load) {

                $.ajax({
                    url  : '<?php echo base_url();?>artikel_data/fetch_data',
                    data : { 
                        limit:limit, 
                        offset:offset, 
                        filter
                    },
                    dataType : 'json',
                    beforeSend: function() {},
                    success: function(response) {
                        
                        // reset
                        $('#blog_feedback').html('');
                        $('#btn-load-more').buttonLoader('stop');
                        $('#submit-form-filter').buttonLoader('stop');

                        // data return
                        if (response.status == 1 ) {

                            if (response.data != '') {

                                var load_data = '';

                                $.each(response.data, function(i, val) {
                                
                                    load_data +=
                                        '<div class="startup-item">'+
                                            '<div class="post">'+
                                                '<a href="'+val.url_detail+'" class="head">'+
                                                    '<img src="'+val.gambar+'" alt="'+val.gambar_keterangan+'" class="img-fluid lazyload post-img">'+
                                                '</a>'+
                                                '<div class="body">'+
                                                    '<a href="'+val.url_detail+'">'+
                                                        '<h2 class="post-title section-description">'+val.judul+'</h2>'+
                                                    '</a>'+
                                                    '<div class="meta-info line-separator">'+
                                                        '<div class="text section-description-sm"><i class="icon lni lni-grid-alt"></i> '+val.kategori+'</div>'+
                                                        '<div class="text section-description-sm"><i class="icon lni lni-calendar"></i> '+val.date_create+'</div>'+
                                                    '</div>'+
                                                    '<p class="post-description">'+val.deskripsi+'</p>'+
                                                    // '<a href="'+val.url_detail+'" class="link link-arrow">Lihat Detail</a>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>';
                                        
                                });

                                localStorage.setItem('offset_blog', response.offset);

                                if (response.load_more == 1) {
                                    $('#show_load_more').removeClass('d-none');
                                } else {
                                    $('#show_load_more').addClass('d-none');
                                }

                                if (load == 'html') {
                                    $('#load_data_blog').html(load_data);
                                } else {
                                    $('#load_data_blog').append(load_data);
                                }
                            }
                            else {
                                $('#load_data_blog').html('');
                                $('#show_load_more').addClass('d-none');

                                $('#blog_feedback').notfounditem('Data tidak ditemukan', 'Maaf, kami tidak dapat menemukan data yang Anda cari.');
                            }
                        }
                    }
                });
            }

            $(document).on('click', '#btn-load-more',function() {
                
                $(this).buttonLoader('start');

                var offset = localStorage.getItem('offset_blog');
                var filter = $('#form-filter').serialize();

                get_data(limit, offset, filter, 'append');
            });

            $('#form-filter').submit(function(e) {
                e.preventDefault();

                $('#submit-form-filter').buttonLoader('start');

                var filter = $(this).serialize();
                get_data(limit, offset, filter, 'html');
            });
        // --------------------------- end load data blog ---------------------------
        
    });
</script>