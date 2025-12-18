<section class="outcomes-section section section-lg">
    <div class="container">
        <div class="inner">
            <div class="section-title text-center">
                <h2 class="title section-heading-lg">Topik SheHacks 2025</h2>
                <p class="description section-description">Pilihlah salah satu dari 4 topik ini yang paling sesuai dengan ide / produk yang kamu miliki.</p>
            </div>

            <div class="list-area row" id="load_data_program_benefit"></div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        // --------------------------- load data program benefit ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_program_benefit',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                            
                            if (val.image) {
                                var image = '<img src="'+val.image+'" alt="'+val.heading+'" class="img-fluid lazyload list-img">';
                            } else {
                                var image = '';
                            }

                            load_data += 
                                '<div class="list-item col-md-6">'+
                                    '<div class="inner">'+
                                        '<div class="list-head">'+
                                            image+
                                        '</div>'+
                                        '<div class="list-body">'+
                                            '<h2 class="heading section-description-lg">'+val.heading+'</h2>'+
                                            '<p class="description section-description-md">'+val.content+'</p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        });
                    }

                    $('#load_data_program_benefit').html(load_data);
                }
            });
        // --------------------------- end load data program benefit ---------------------------
    })
</script>