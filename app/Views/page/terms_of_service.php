<section class="hero-section style-1 hero-md section">
    <img data-src="<?php echo base_url();?>assets/front/img/background/bg-hero.webp" alt="cover" class="img-fluid lazyload hero-cover">
    <div class="container">
        <div class="content center">
            <h2 class="title section-heading-lg mb-0">Term of Service</h2>
        </div>
    </div>
</section>


<section class="single-content-section section section-lg pt-5">
    <div class="container">
        <div class="inner">
            <div id="load_data_content"></div>
        </div>
    </div>
</section>


<script type="text/javascript">
    $(document).ready(function() {

        // --------------------------- load data privacy policy ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>page/terms_of_service/fetch_data',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    
                    // data return
                    if (response.status == 1 ) {

                        var load_data = '';

                        $.each(response.data, function(i, val) {
                            
                            load_data += '<div class="post-description reset-style">'+val.content+'</div>';
                        });

                        $('#load_data_content').html(load_data);
                    }
                }
            });
        // --------------------------- end load data privacy policy ---------------------------
        
    });
</script>