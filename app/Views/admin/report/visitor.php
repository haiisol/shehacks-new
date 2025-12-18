<div class="wrap-action">
    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <select name="fil_periode" id="fil_periode" data-placeholder="Pilih Periode" class="form-control form-control-sm" style="min-width: 200px; max-width: 200px;">
                    <option value="byhour" selected>Hari Ini</option>
                    <option value="bylast7day">7 Hari Terakhir</option>
                    <option value="byday">Bulan Ini</option>
                    <option value="bymonth">Tahun Ini</option>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_page" id="fil_page" data-placeholder="Pilih Halaman" class="form-control form-control-sm" style="min-width: 580px; max-width: 580px;">
                </select>
            </div>


            <!-- <div class="form-group mb-2">
                <input type="text" name="fil_date" class="form-control form-control-sm datepicker-range-custom" style="width: 200px;">
            </div> -->
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
        </form>
    </div>
</div>


<div class="row mt-3">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-primary text-primary me-3">
                        <ion-icon name="disc-sharp"></ion-icon>
                    </div>
                    
                    <div>
                        <p class="mb-1">Total Hits <span class="chart_visitor_label"></span></p>

                        <h4 id="load_total_pengunjung"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-info text-info me-3">
                        <ion-icon name="walk-sharp"></ion-icon>
                    </div>
                    
                    <div>
                        <p class="mb-1">Total Unik IP <span class="chart_visitor_label"></span></p>

                        <h4 id="load_total_unik_ip"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-secondary text-secondary me-3">
                        <ion-icon name="download-sharp"></ion-icon>
                    </div>
                    
                    <div>
                        <p class="mb-1">Total Referal <span class="chart_visitor_label"></span></p>

                        <h4 id="load_total_referal"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Grafik Pengunjung <span class="chart_visitor_label"></span></h6>
                <canvas id="chart_visitor" height="100"></canvas>
            </div>
        </div>
    </div>


    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-info text-info me-3">
                        <ion-icon name="enter-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Pengunjung URL Referal</h6>
                        <p>Berdasarkan pengunjung dari mana</p>
                    </div>
                </div>
                <div class="text-center" id="no-data-referral">
                    <img src="<?php echo base_url();?>assets/backoffice/images/no-data.png" class="image-no-data">
                </div>
                <div class="chart-container6" id="container-chart">
                    <canvas id="chart_url_referal"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-danger text-danger me-3">
                        <ion-icon name="book-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Pengunjung Page View</h6>
                        <p>Berdasarkan Halaman</p>
                    </div>
                </div>
                <div class="text-center" id="no-data-page">
                    <img src="<?php echo base_url();?>assets/backoffice/images/no-data.png" class="image-no-data">
                </div>
                <div class="chart-container6" id="container-chart-page">
                    <canvas id="chart_url_page"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

        var date = $('#fil_periode').val();
        load_fil_page(date);
        function load_fil_page(date) {
            $.ajax({
                url  : '<?php echo base_url();?>admin/report/visitor/data_select',
                data : { date:date },
                success: function(response) {
                    $('#fil_page').html(response.data);
                }
            });
        }

        $(document).on('change', '#fil_periode', function() {
            var date = $(this).val();
            load_fil_page(date);
        });

        var form_filter = $('#form-filter');
        var fillinformfilter = form_filter.serialize();

        load_data_chart_visitor(fillinformfilter);
        load_data_url_referal(fillinformfilter);
        load_data_url_page(fillinformfilter);
        load_data_header(fillinformfilter);

        $('#form-filter').submit(function(e) {
            e.preventDefault();

            filter = $(this).serialize();

            load_data_chart_visitor(filter);
            load_data_url_referal(filter);
            load_data_url_page(filter);
            load_data_header(filter);
        });

        function load_data_header(filter) {
            $.ajax({
                url     : '<?php echo base_url();?>admin/report/visitor/load_data_header?'+filter,
                data    : { param: 'page' },
                success: function(response) {

                    if (response.status == 1) {
                        $('#load_total_pengunjung').html(response.total_pengunjung+' <span class="fw-400 fs-6">Hits</span>');
                        $('#load_total_unik_ip').html(response.total_unik_ip+' <span class="fw-400 fs-6">Unik IP Adress</span>');
                        $('#load_total_referal').html(response.total_referral+' <span class="fw-400 fs-6">URL Referal</span>');
                    }
                }
            });
        }


        // -------------------------- chart visitor --------------------------

            var bg_chart = ['#FF64B4','#FFD28C','#82DCE1','#1787bd','#91E6CD','#FF4164','#FFD2D7','#69BEEB','#82AF9B','#E1A03C','#FAAF96',];

            var options_line = {
                    responsive: true,
                    cutout: 10,
                    layout: {
                        padding: { top: 30, bottom: 10 }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            usePointStyle: true,
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                display: false
                            },
                            display: false,
                            ticks: {
                                display: false
                            }
                        }
                    }
                }

            var chart_visitor = new Chart(document.getElementById('chart_visitor'), {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: ' ',
                            hoverOffset: 10,
                            lineTension: 0, 
                            borderColor: bg_chart,
                            fill: true
                        }],
                    },
                    options_line
                });
            
            function load_data_chart_visitor(filter) {
                $.ajax({
                    url : '<?php echo base_url();?>admin/report/visitor/fetch_data_chart_visitor?'+filter,
                    success: function(response) {
                        
                        if (response.status == 1) {
                            
                            // chart title
                            $('.chart_visitor_label').html(response.card_title);

                            // reset data chart
                            chart_visitor.data.labels = [];
                            chart_visitor.data.datasets = [];

                            // set data chart
                            chart_visitor.data;
                            chart_visitor.data.labels = response.data_chart.labels;

                            for (let i=0; i<response.data_chart.datasets.length; i++) {
                                if (chart_visitor.data.datasets[i]) {
                                    chart_visitor.data.datasets[i].label = response.data_chart.datasets[i].label;
                                    chart_visitor.data.datasets[i].data = response.data_chart.datasets[i].data;
                                    chart_visitor.data.datasets[i].borderColor = response.data_chart.datasets[i].borderColor;
                                } 
                                else {
                                    chart_visitor.data.datasets.push({
                                        label: response.data_chart.datasets[i].label,
                                        data: response.data_chart.datasets[i].data,
                                        borderColor: response.data_chart.datasets[i].borderColor
                                    });
                                }
                            }

                            chart_visitor.update();
                        }
                    }
                });
            }
        // -------------------------- end chart visitor --------------------------


        var options = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: 10,
            layout: {
                padding: { top: 10, bottom: 10 }
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    usePointStyle: true,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            elements: {
                arc: {
                    backgroundColor: bg_chart,
                    hoverBackgroundColor: bg_chart
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    display: false,
                    ticks: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    display: false,
                    ticks: {
                        display: false
                    }
                }
            }
        }

        var chart_gt = new Chart(document.getElementById('chart_url_referal'), {
            type: 'pie',
            data: {
                datasets: [{
                    label: ' ',
                    backgroundColor: bg_chart,
                    borderWidth: 0,
                    hoverOffset: 10,
                }],
            },
            options
        });
            
            function load_data_url_referal(filter) {
                $.ajax({
                    method  : 'POST',
                    url     : '<?php echo base_url();?>admin/report/visitor/load_data_url?'+filter,
                    dataType: 'json',
                    data    : { param: 'referral' },
                    success: function(response) {
                        
                        if (response.status == 1) {
                            document.getElementById("container-chart").style.display="block";
                            $('#no-data-referral').addClass('d-none');
                            
                            if (response.labels.length > 0) {
                                chart_gt.data.labels            = response.labels;
                                chart_gt.data.datasets[0].data  = response.datasets;
                                chart_gt.update();
                            } else {
                                document.getElementById("container-chart").style.display="none";
                                $('#no-data-referral').removeClass('d-none');
                            }

                        }
                    }
                });
            }

        var chart_page = new Chart(document.getElementById('chart_url_page'), {
            type: 'pie',
            data: {
                datasets: [{
                    label: ' ',
                    backgroundColor: bg_chart,
                    borderWidth: 0,
                    hoverOffset: 10,
                }],
            },
            options
        });
            
            function load_data_url_page(filter) {
                $.ajax({
                    method  : 'POST',
                    url     : '<?php echo base_url();?>admin/report/visitor/load_data_url?'+filter,
                    dataType: 'json',
                    data    : { param: 'page' },
                    success: function(response) {
                        
                        if (response.status == 1) {
                            document.getElementById("container-chart-page").style.display="block";
                            $('#no-data-page').addClass('d-none');
                            
                            if (response.labels.length > 0) {
                                chart_page.data.labels            = response.labels;
                                chart_page.data.datasets[0].data  = response.datasets;
                                chart_page.update();
                            } else {
                                document.getElementById("container-chart-page").style.display="none";
                                $('#no-data-page').removeClass('d-none');
                            }

                        }
                    }
                });
            }
    });
</script>