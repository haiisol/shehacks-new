<section class="unconst-section section section-sm">
    <div class="container">
        <div class="inner" id="load_data_hero_image"></div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {    
        $.ajax({
            url : '<?php echo base_url();?>fetch/fetch_data/fetch_data_banner_popup',
            beforeSend: function() {},
            success: function(response) {
                var load_data = '';
                if (response.status == 1) {
                    $.each(response.data, function(i, val) {
                        load_data += `
                            <img src="${val.image}" alt="Shehacks 2025 Hero Image" class="img-fluid image-banner" width="1400" height="788">
                        `;
                    });
                }

                $('#load_data_hero_image').html(load_data);
            }
        });
    });

</script>