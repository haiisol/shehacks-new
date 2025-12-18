<div class="modal modal-style style-2 fade banner-popup" id="banner-popup" aria-hidden="true" aria-labelledby="ModalToggleLabel" tabindex="2">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="inner-modal">
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="banner-popup-area" id="load_data_banner_popup"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // --------------------------- load data banner popup ---------------------------
            $.ajax({
                method   : 'POST',
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_banner_popup',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                            
                            var button_url = (val.button_url ? `href="${val.button_url}" target="_blank"` : 'href="javascript:void(0)"');

                            load_data += `
                                <a ${button_url}>
                                    <img data-src="${val.image}" alt="banner" class="image-banner img-fluid lazyload" width="800" height="450">
                                </a>`;
                        });

                        setTimeout(function () {
                            $('#banner-popup').modal('show');
                        }, 2500);
                    }

                    $('#load_data_banner_popup').html(load_data);

                    lazyload()
                }
            });
        // --------------------------- end load data banner popup ---------------------------
    });
</script>