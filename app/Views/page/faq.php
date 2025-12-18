<section class="hero-section style-1 hero-md section">
    <img data-src="<?php echo base_url();?>assets/front/img/background/bg-hero.webp" alt="cover" class="img-fluid lazyload hero-cover">
    <div class="container">
        <div class="content center">
            <div class="row">
                <div class="col-lg-6 m-auto">        
                    <h2 class="title section-heading-lg">Frequently Asked Question SheHacks 2025</h2>

                    <form method="post" id="form-filter" class="form-style mt-2">
                        <div class="form-group form-search">
                            <div class="group-inner s-icon">
                                <span class="group-inner-icon"><i class="lni lni-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Cari faq disini" autocomplete="off" autocorrect="off">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="single-content-section section section-lg">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6">
                <div class="section-title">
                    <h2 class="title section-heading">Kamu Bertanya - Kami Menjawab</h2>
                    <p class="description section-description">Jika kamu masih memiliki pertanyaan terkait program ini yang belum jelas, dapat mengirimkan email ke kami di <a href="mailto:shehacks@kumpul.id" target="_blank"><strong>shehacks@kumpul.id</strong></a></p>
                </div>
            </div>
        </div>
        
        <div class="inner">
            <div class="accordion row" id="load_data_content"></div>
        </div>
    </div>
</section>


<?php $this->load->view('component/section/cta'); ?>


<script type="text/javascript">
    $(document).ready(function() {

        // --------------------------- load data faq ---------------------------
            load_data('');

            function load_data(search)
            {
                $.ajax({
                    url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_faq',
                    data     : { search: search }, 
                    dataType : 'json',
                    beforeSend: function() {},
                    success:function(response) {
                        
                        // data return
                        if (response.status == 1 ) {

                            var load_data = '';

                            $.each(response.data, function(i, val) {
                                
                                // if (i == 0 || i == 1) {
                                if (i == 0) {
                                    var show = 'show';
                                    var collapsed = '';
                                    var aria_expanded = 'true';
                                } else {
                                    var show = '';
                                    var collapsed = 'collapsed';
                                    var aria_expanded = 'false';
                                }

                                load_data += 
                                    '<div class="col-lg-12">'+
                                    '<div class="accordion-item" id="faq-'+val.id+'">'+
                                        '<button type="button" class="accordion-head '+collapsed+' section-description" data-bs-toggle="collapse" data-bs-target="#faq-'+val.id+'-collapse" aria-expanded="true" aria-controls="faq-'+val.id+'-collapse">'+
                                            val.heading+
                                        '</button>'+

                                        '<div id="faq-'+val.id+'-collapse" class="accordion-collapse collapse '+show+'" aria-labelledby="faq-'+val.id+'">'+
                                            '<div class="accordion-body section-description-md reset-style-content">'+
                                                val.description+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '</div>';
                            });
                        }
                        else {
                            var load_data = '<p>Maaf. Pencarian Anda "'+search+'" tidak memberikan hasil apa pun.</p>';
                        }

                        $('#load_data_content').html(load_data);
                    }
                });
            }

            $('input[name="search"]').keyup(delay(function(e) {
                var val = this.value;
                load_data(val);
            }, 800));

            function delay(callback, ms) {
                var timer = 0;

                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }

            $(document).on('keyup keypress', 'input[name="search"]', function(e) {

                var val = this.value;
                var rgx = new RegExp(/[~`#$%\^&*+=[\]\\';/{}|\\":<>]/, 'gi');

                if (rgx.test(val)) {
                    result = false;
                    $(this).addClass('has-error');
                } else {
                    result = true;
                    $(this).removeClass('has-error');
                }
                return result;
            });
        // --------------------------- end load data faq ---------------------------
        
    });
</script>