<section class="blog-single section" id="load_data_blog"></section>


<script type="text/javascript">
    $(document).ready(function() {

        $.ajax({
            url      : '<?php echo base_url();?>artikel_data/fetch_data_detail',
            data     : { id_enc:'<?php echo $id_enc; ?>' },
            dataType : 'json',
            beforeSend: function() {},
            success:function(response) {

                if (response.status == 1 ) {

                    $.each(response.data, function(i, val) {
                        
                        var load_data = '';

                        var data_tags = '';

                        $.each(val.tags, function(i2, val2) {
                            data_tags += '<p class="tags-item"><span>'+val2.tags+'</span></p>';
                        });

                        load_data +=
                            // '<div class="head">'+
                            //     '<img src="'+val.gambar+'" alt="'+val.gambar_keterangan+'" class="img-fluid lazyload hero-cover">'+
                            //     '<div class="container">'+
                            //         '<div class="content">'+
                            //             '<div class="row">'+
                            //                 '<div class="col-lg-7 m-auto">'+
                            //                     '<h2 class="title section-heading-xl">'+val.judul+'</h2>'+
                                                
                            //                     '<div class="meta-info line-separator">'+
                            //                         '<span class="text"><i class="icon lni lni-user"></i>'+val.admin+'</span>'+
                            //                         '<span class="text"><i class="icon lni lni-grid-alt"></i>'+val.kategori+'</span>'+
                            //                         '<span class="text"><i class="icon lni lni-calendar"></i>'+val.date_create+'</span>'+
                            //                     '</div>'+
                            //                 '</div>'+
                            //             '</div>'+
                            //         '</div>'+
                            //     '</div>'+
                            // '</div>'+

                            '<div class="body">'+
                                '<div class="container">'+
                                    '<div class="content">'+
                                        '<div class="row">'+
                                            '<div class="col-lg-8 m-auto">'+
                                                '<img src="'+val.gambar+'" alt="'+val.gambar_keterangan+'" class="img-fluid lazyload blog-img">'+

                                                '<div class="mb-4">'+
                                                    '<h2 class="title section-heading">'+val.judul+'</h2>'+
                                                    
                                                    '<div class="meta-info line-separator">'+
                                                        '<span class="text"><i class="icon lni lni-user"></i>'+val.admin+'</span>'+
                                                        '<span class="text"><i class="icon lni lni-grid-alt"></i>'+val.kategori+'</span>'+
                                                        '<span class="text"><i class="icon lni lni-calendar"></i>'+val.date_create+'</span>'+
                                                    '</div>'+
                                                '</div>'+

                                                '<div class="description reset-style">'+val.deskripsi+'</div>'+

                                                '<div class="tags-area">'+
                                                    data_tags+
                                                '</div>'+

                                                '<div class="share-area">'+
                                                    '<span class="share-label">SHARE:</span>'+

                                                    '<ul class="social-media style-2">'+
                                                        '<li><a href="javascript:void(0)" class="icon facebook post-share-facebook" data_title="'+val.judul+'" data_url="'+val.url_detail+'"><i class="lni lni-facebook"></i></a></li>'+
                                                        '<li><a href="javascript:void(0)" class="icon twitter post-share-twitter" data_title="'+val.judul+'" data_url="'+val.url_detail+'"><i class="lni lni-twitter"></i></a></li>'+
                                                        '<li><a href="javascript:void(0)" class="icon telegram post-share-telegram" data_title="'+val.judul+'" data_url="'+val.url_detail+'"><i class="lni lni-telegram"></i></a></li>'+
                                                        '<li><a href="javascript:void(0)" class="icon whatsapp post-share-whatsapp" data_title="'+val.judul+'" data_url="'+val.url_detail+'"><i class="lni lni-whatsapp"></i></a></li>'+
                                                        '<li><a href="javascript:void(0)" class="icon linkedin post-share-linkedin" data_title="'+val.judul+'" data_url="'+val.url_detail+'"><i class="lni lni-linkedin"></i></a></li>'+
                                                    '</ul>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';

                        $('#load_data_blog').html(load_data);
                    });
                }
                else {

                }
            }
        });


        // --------------------------- post viewer ---------------------------
            setTimeout(post_viewer, 1000);

            function post_viewer() {
                $.ajax({
                    method   : 'POST',
                    url      : '<?php echo base_url();?>analytic/post_blog_viewer',
                    data     : { id_enc:'<?php echo $id_enc; ?>' },
                    dataType : 'json',
                    success:function(response) {}
                });
            }
		// --------------------------- end post viewer ---------------------------

    });
</script>