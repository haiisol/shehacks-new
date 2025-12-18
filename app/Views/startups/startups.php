<section class="hero-section style-1 section hero-startup">
    <img data-src="<?php echo base_url();?>assets/front/img/background/bg-hero.webp" alt="cover" class="img-fluid lazyload hero-cover">
    <div class="container">
        <div class="content">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-6">        
                    <h2 class="title section-heading-lg stroke-gradient">Temukan Startup Alumni SheHacks by Indosat disini</h2>
                </div>
                <div class="col-lg-5">
                    <p class="description section-description-md">Sejak 2020, SheHacks by Indosat Ooredoo Hutchison terus memberikan kesempatan pada womenpreneur untuk mengembangkan diri dan juga bisnisnya.</p>
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
                    <h5 class="text-center">Alumni Akselerator SheHacks</h5>
                    
                    <form method="post" id="form-filter" class="form-style">
                        <div class="filter-fill">
                            <div class="form-group">
                                <input type="text" name="search" id="search" placeholder="Cari Startups" class="form-control" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <select name="period" id="period" data-placeholder="Semua Periode" data-allow-clear="false" class="form-control select-custome">
                                    <option value="0" selected>Semua Periode</option>
                                    <?php foreach (period() as $key) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="sector" id="sector" data-placeholder="Semua Sektor" data-allow-clear="false" class="form-control select-custome-search">
                                    <option value="0" selected>Semua Sektor</option>
                                    <?php foreach ($sector as $key) { ?>
                                        <option value="<?php echo $key['id_sector']; ?>"><?php echo $key['nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="filter-action">
                            <div class="form-group">
                                <button type="submit" id="submit-form-filter" class="btn"><span>Filter</span></button>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
        
        <div class="inner">
            <div id="data_feedback"></div>
            <div class="startup-area" id="load_data"></div>

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

        const INSTAGRAM_ICON = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M17.0625 6.9375V6.94875M3 7.5C3 6.30653 3.47411 5.16193 4.31802 4.31802C5.16193 3.47411 6.30653 3 7.5 3H16.5C17.6935 3 18.8381 3.47411 19.682 4.31802C20.5259 5.16193 21 6.30653 21 7.5V16.5C21 17.6935 20.5259 18.8381 19.682 19.682C18.8381 20.5259 17.6935 21 16.5 21H7.5C6.30653 21 5.16193 20.5259 4.31802 19.682C3.47411 18.8381 3 17.6935 3 16.5V7.5ZM8.625 12C8.625 12.8951 8.98058 13.7536 9.61351 14.3865C10.2464 15.0194 11.1049 15.375 12 15.375C12.8951 15.375 13.7536 15.0194 14.3865 14.3865C15.0194 13.7536 15.375 12.8951 15.375 12C15.375 11.1049 15.0194 10.2464 14.3865 9.61351C13.7536 8.98058 12.8951 8.625 12 8.625C11.1049 8.625 10.2464 8.98058 9.61351 9.61351C8.98058 10.2464 8.625 11.1049 8.625 12Z" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
        const WEBSITE_ICON = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M22 13.0107C21.8 15.208 20.7996 17.4056 19.0996 19.1035C17.0996 21.001 14.5999 22 12 22C13.2 22 14.5 21.101 15.5 19.1035C16.3 17.4056 16.8 15.208 17 13.0107H22ZM7 13.0107C7.1 15.208 7.60039 17.4056 8.40039 19.1035C9.40038 21.0011 10.7 22 12 22C9.40007 22 6.90037 21.1009 4.90039 19.1035C3.20039 17.4056 2.2 15.208 2 13.0107H7ZM14.7998 13.0107C14.6998 14.9084 14.2996 16.8068 13.5996 18.2051C12.9999 19.4029 12.3004 20.0025 11.9004 20.0029C11.5004 20.0029 10.8002 19.4033 10.2002 18.2051C9.5002 16.8068 9.1 14.9084 9 13.0107H14.7998ZM4.90039 4.9209C8.80041 1.02619 15.1005 1.02593 18.9004 4.9209C20.6004 6.61876 21.5998 8.81644 21.7998 11.0137H16.7998C16.6998 8.81643 16.2004 6.61876 15.4004 4.9209C14.5005 3.02361 13.1003 2.02561 11.9004 2.02539C10.7005 2.02539 9.40036 3.0236 8.40039 4.9209C7.60039 6.61877 7.1 8.81643 7 11.0137H2C2.20001 8.81643 3.2004 6.61877 4.90039 4.9209ZM11.9004 4.02246C12.4004 4.02277 13.1004 4.62212 13.7002 5.82031C14.4001 7.21854 14.8004 9.11612 14.9004 11.0137H9C9.1 9.11607 9.5002 7.21855 10.2002 5.82031C10.8002 4.62182 11.4004 4.02246 11.9004 4.02246Z" fill="#2F2F2F"/></svg>`;
        const FOUNDERS_ICON = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M7.5 10.875V16.5M7.5 7.5V7.51125M12 16.5V10.875M16.5 16.5V13.125C16.5 12.5283 16.2629 11.956 15.841 11.534C15.419 11.1121 14.8467 10.875 14.25 10.875C13.6533 10.875 13.081 11.1121 12.659 11.534C12.2371 11.956 12 12.5283 12 13.125M3 5.25C3 4.65326 3.23705 4.08097 3.65901 3.65901C4.08097 3.23705 4.65326 3 5.25 3H18.75C19.3467 3 19.919 3.23705 20.341 3.65901C20.7629 4.08097 21 4.65326 21 5.25V18.75C21 19.3467 20.7629 19.919 20.341 20.341C19.919 20.7629 19.3467 21 18.75 21H5.25C4.65326 21 4.08097 20.7629 3.65901 20.341C3.23705 19.919 3 19.3467 3 18.75V5.25Z" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;


        // --------------------------- load data blog ---------------------------
            localStorage.setItem('offset_data', 0);

            var limit  = 12;
            var offset = localStorage.getItem('offset_data');
            var filter = $('#form-filter').serialize();

            get_data(limit, offset, filter, 'html');

            function get_data(limit, offset, filter, load) {

                $.ajax({
                    url      : '<?php echo base_url();?>startups_data/fetch_data',
                    data     : { 
                        limit:limit, 
                        offset:offset, 
                        filter
                    },
                    dataType : 'json',
                    beforeSend: function() {},
                    success:function(response) {
                        
                        // reset
                        $('#data_feedback').html('');

                        // data return
                        if (response.status == 1 ) {
                            
                            if (response.data.length > 0) {

                                var load_data = '';

                                $.each(response.data, function(i, val) {

                                    // platform
                                    const platform = val.url ? `
                                        <div class="info-item">
                                            <span class="label">
                                                ${val.url_label === 'Instagram' ? INSTAGRAM_ICON : WEBSITE_ICON}
                                            </span>
                                            <a href="${val.url}" target="_blank">
                                                <span class="value accent">
                                                    ${val.url_label === 'Instagram' && val.url.includes('instagram.com/') ? `@${val.url.split("instagram.com/")[1].replace(/\//g, "")}` : val.startup_name}
                                                </span>
                                            </a>
                                        </div>
                                    ` : '';

                                    // founders
                                    const founders = val.founders_url ? `
                                        <div class="info-item">
                                            <span class="label">${FOUNDERS_ICON}</span>
                                            <a href="${val.founders_url}" target="_blank">
                                                <span class="value accent">${val.founders_name}</span>
                                            </a>
                                        </div>
                                    ` : '';

                                    
                                    load_data += `
                                        <div class="startup-item">
                                            <div class="post post-startup">
                                                <div class="head">
                                                    <img src="${val.gambar}" alt="${val.startup_name}" class="img-fluid lazyload post-img">
                                                </div>
                                                <div class="body">
                                                    <div class="post-info">
                                                        <div class="info-item">
                                                            <span class="label">Periode</span>
                                                            <span class="value accent">${val.period}</span>
                                                        </div>
                                                        <div class="info-item text-end">
                                                            <span class="value accent">${val.sector}</span>
                                                        </div>
                                                    </div>

                                                    <h2 class="post-title section-description">${val.startup_name}</h2>
                                                    
                                                    <p class="post-description">${val.sort_description}</p>

                                                    <div class="post-info flex-column">
                                                        ${platform}
                                                        ${founders}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                                        
                                });

                                localStorage.setItem('offset_data', response.offset);

                                if (response.load_more == 1) {
                                    $('#show_load_more').removeClass('d-none');
                                } else {
                                    $('#show_load_more').addClass('d-none');
                                }

                                if (load == 'html') {
                                    $('#load_data').html(load_data);
                                } else {
                                    $('#load_data').append(load_data);
                                }
                            }
                            else {
                                $('#load_data').html('');
                                $('#show_load_more').addClass('d-none');

                                $('#data_feedback').notfounditem('Data tidak ditemukan', 'Maaf, kami tidak dapat menemukan data yang Anda cari.');
                            }
                        }
                    }
                });
            }

            $(document).on('click', '#btn-load-more',function() {
                var offset = localStorage.getItem('offset_data');
                var filter = $('#form-filter').serialize();

                get_data(limit, offset, filter, 'append');
            });

            $('#form-filter').submit(function(e) {
                e.preventDefault();
                var filter = $(this).serialize();
                get_data(limit, offset, filter, 'html');
            });
        // --------------------------- end load data blog ---------------------------
        
    });
</script>