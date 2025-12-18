<section 
    class="hero-image-section section" 
    id="load_data_hero_image" 
    data-stellar-background-ratio="0.5" 
    data-stellar-vertical-offset="80"
>
    <!-- style="background-image: url('https://localhost/shehacks/file_media/image-content/banner-popup-UY1BDI3OCH.jpg')" -->
</section>

<!-- <script src="<?php echo base_url();?>assets/front/vendor/jquery-stellar/jquery.stellar.js"></script> -->

<script type="text/javascript">
    $(document).ready(function() {
        // $('#load_data_hero_image').stellar({
        //     scrollProperty: 'scroll',
        //     positionProperty: 'position',
        //     parallaxBackgrounds: true,
        //     parallaxElements: true,
        // });
    });

    $.ajax({
        url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_banner_popup',
        dataType : 'json',
        beforeSend: function() {},
        success: function(response) {
            var load_data = '';
            if (response.status == 1) {
                $.each(response.data, function(i, val) {
                    if (val.button_url) {
                        var button_url = (val.button_url ? `href="${val.button_url}" target="_blank"` : 'href="javascript:void(0)"');
                        load_data += `
                            <a ${button_url}>
                                <img src="${val.image}" alt="Shehacks 2025 Hero Image" class="img-fluid" width="1400" height="788">
                            </a>`;
                    } else {
                        load_data += `
                            <img src="${val.image}" alt="Shehacks 2025 Hero Image" class="img-fluid" width="1400" height="788">
                        `;
                    }
                });
            }

            $('#load_data_hero_image').html(load_data);
        }
    });
</script>