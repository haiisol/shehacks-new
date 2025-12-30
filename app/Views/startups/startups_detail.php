<section class="blog-single section" id="load_data"></section>

<script type="text/javascript">
    $(document).ready(function() {

        $.ajax({
            url      : '<?php echo base_url();?>startups_data/fetch_data_detail',
            data     : { id_enc:'<?php echo $id_enc; ?>' },
            dataType : 'json',
            beforeSend: function() {},
            success:function(response) {

                if (response.status == 1) {
                    $.each(response.data, function(i, val) {
                        
                        var load_data = '';

                        load_data += `
                            <section class="hero-section style-1 section hero-startup-detail">
                                <img data-src="${val.thumbnail}" alt="${val.startup_name}" class="img-fluid lazyload hero-cover">
                                <div class="container">
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-lg-7 m-auto">  
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-lg-6 d-flex align-items-start flex-column gap-3">
                                                        <img data-src="${val.logo}" alt="${val.startup_name}" class="img-fluid lazyload" style="object-fit: contain; max-width: 6.25rem; max-height: 3.2rem;">     
                                                        <h2 class="title section-heading-lg stroke-gradient mb-0">${val.startup_name}</h2>
                                                    </div>
                                                    <div class="col-lg-5 mt-lg-0 mt-4">
                                                        <div class="post-info flex-column">
                                                            <div class="info-item">
                                                                <span class="label"><i class="icon lni lni-user"></i> Founder</span>
                                                                <span class="value text-white">${val.founders_name}</span>
                                                            </div>
                                                            <div class="info-item">
                                                                <span class="label"><i class="icon lni lni-calendar"></i> Periode</span>
                                                                <span class="value text-white">${val.period}</span>
                                                            </div>
                                                            <div class="info-item">
                                                                <span class="label"><i class="icon lni lni-briefcase"></i> Sektor</span>
                                                                <span class="value text-white">${val.sector}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <div class="body">
                                <div class="container">
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-lg-7 m-auto">  
                                                <div class="description reset-style">${val.description}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                            // <div class="head">
                            //     <img src="${val.thumbnail}" alt="${val.startup_name}" class="img-fluid lazyload hero-cover">
                            //     <div class="container">
                            //         <div class="content">
                            //             <div class="row">
                            //                 <div class="col-lg-7 m-auto">
                            //                     <h2 class="title section-heading-xl">${val.startup_name}</h2>
                                                
                            //                     <div class="meta-info line-separator">
                            //                         <span class="text"><i class="icon lni lni-user"></i>${val.founders_name}</span>
                            //                         <span class="text"><i class="icon lni lni-user"></i>${val.period}</span>
                            //                         <span class="text"><i class="icon lni lni-user"></i>${val.sector}</span>
                            //                     </div>
                            //                 </div>
                            //             </div>
                            //         </div>
                            //     </div>
                            // </div>

                        $('#load_data').html(load_data);

                        lazyload();
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
                    url      : '<?php echo base_url();?>analytic/post_startups_viewer',
                    data     : { id_enc:'<?php echo $id_enc; ?>' },
                    dataType : 'json',
                    success:function(response) {}
                });
            }
		// --------------------------- end post viewer ---------------------------

    });
</script>