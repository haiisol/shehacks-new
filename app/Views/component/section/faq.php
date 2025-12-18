<section class="question-section section section-xl">
    <div class="container">
        <div class="section-title center">
            <p class="subtitle section-description d-inline-block">Tanya SheHacks 2025</p>
            <h2 class="title section-heading">Frequently Asked Questions 😊</h2>
        </div>

        <div class="inner">
            <div class="row accordion" id="load_data_faq"></div>
        </div>
    </div>
</section>

<script>
    $.ajax({
        url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_faq',
        data     : { limit: '6'},
        dataType : 'json',
        beforeSend: function() {},
        success:function(response) {
            var load_data = '';
            
            if (response.status === 1) {

                $.each(response.data, function(i, val) {
                    
                    load_data += `
                        <div class="col-lg-6">
                            <div class="accordion-item" id="faq-val${val.id}">
                                <button type="button" class="accordion-head collapsed section-description" data-bs-toggle="collapse" data-bs-target="#faq-val${val.id}-collapse" aria-expanded="false" aria-controls="faq-val${val.id}-collapse">${val.heading}</button>

                                <div id="faq-val${val.id}-collapse" class="accordion-collapse collapse" aria-labelledby="faq-val1">
                                    <div class="accordion-body section-description-md reset-style-content">${val.description}</div>
                                </div>
                            </div>
                        </div>`;
                });

            }

            $('#load_data_faq').html(load_data);
        }
    });
</script>