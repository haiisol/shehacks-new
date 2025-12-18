<div class="wrap-action">
    <div class="action-area">    
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <input type="text" name="fil_date" class="form-control form-control-sm datepicker-range-month" style="width: 200px;">
            </div>
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
        </form>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:void(0)" class="widget-icon-2 rounded-10 bg-light-accent text-primary me-3">
                        <ion-icon name="walk-sharp"></ion-icon>
                    </a>
                    <div>
                        <p class="mb-1">Total Hits <span id="load_total_transaksi_label"></span></p>
                        <h4 id="load_total_pengunjung"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:void(0)" class="widget-icon-2 rounded-10 bg-light-success text-success me-3">
                        <ion-icon name="walk-sharp"></ion-icon>
                    </a>
                    <div>
                        <p class="mb-1">Total Hits Unik IP Adrress <span id="load_total_omset_label"></span></p>
                        <h4 id="load_total_url_referral"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-data" class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-left">Page View</th>
                                <th class="text-left">CTA Button</th>
                                <th class="text-center">Hits</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-info text-info me-3">
                        <ion-icon name="caret-down-circle-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Hits Page View</h6>
                        <p>Berdasarkan hist page view</p>
                    </div>
                </div>
                <div class="text-center" id="no-data-pv">
                    <img src="<?php echo base_url();?>assets/backoffice/images/no-data.png" class="image-no-data">
                </div>
                <div class="chart-container6" id="container-chart-pv">
                    <canvas id="chart_url_pv"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-danger text-danger me-3">
                        <ion-icon name="disc-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Hits CTA button</h6>
                        <p>Berdasarkan hist cta button</p>
                    </div>
                </div>
                <div class="text-center" id="no-data-btn">
                    <img src="<?php echo base_url();?>assets/backoffice/images/no-data.png" class="image-no-data">
                </div>
                <div class="chart-container6" id="container-chart-btn">
                    <canvas id="chart_url_btn"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-12">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-danger text-danger me-3">
                        <ion-icon name="calendar-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6 id="title_filter_date">Hari Ini</h6>
                    </div>
                    <div class="dropdown options position-absolute top-0 end-0 mt-2 me-3">
                        <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                            <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" id="fil_total_date">
                            <li><a class="dropdown-item" href="javascript:void(0);" value="today">Hari Ini</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" value="last7">7 Hari Terakhir</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" value="month">Bulan Ini</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" value="year">Tahun Ini</a></li>
                        </ul>
                    </div>
                </div>

                <canvas id="chart_filter_date"></canvas>
            </div>
        </div>
    </div>
</div>


<?php include APPPATH.'views/admin/component/include_source.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

        $('.datepicker-range-month').daterangepicker({
            startDate: moment().startOf('month'),
            endDate   : moment().endOf('month'),
            locale    : {
                format: 'DD/MM/YYYY'
            },
        });

        var save_method;

        var table_data = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/report/cta_btn/datatables',
                complete: function(data, type) {
                    json = data.responseJSON;
                    $('#load_total_pengunjung').html(json.total_pengunjung+' <span class="fw-400 fs-6">Hits</span>');
                    $('#load_total_url_referral').html(json.total_referral+' <span class="fw-400 fs-6">URL IP Adrress</span>');
                },
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 1, 2, 3],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        $('#form-filter').submit(function(e) {
            e.preventDefault();

            filter = $(this).serialize();
            url_reload = '<?php echo base_url();?>admin/report/cta_btn/datatables/?' + filter;
            table_data.ajax.url(url_reload).load();

            load_data_url_pv(filter);
            load_data_url_btn(filter);

        });

        var bg_chart = ['#FF64B4','#FFD28C','#82DCE1','#1787bd','#91E6CD','#FF4164','#FFD2D7','#69BEEB','#82AF9B','#E1A03C','#FAAF96',];

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

        var chart_pv = new Chart(document.getElementById('chart_url_pv'), {
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

        load_data_url_pv('');
            
        function load_data_url_pv(filter) {
            $.ajax({
                url  : '<?php echo base_url();?>admin/report/cta_btn/load_data_url?'+filter,
                data : { param:'pv' },
                success: function(response) {
                    
                    if (response.status == 1) {
                        document.getElementById("container-chart-pv").style.display="block";
                        $('#no-data-pv').addClass('d-none');
                        
                        if (response.labels.length > 0) {
                            chart_pv.data.labels            = response.labels;
                            chart_pv.data.datasets[0].data  = response.datasets;
                            chart_pv.update();
                        } else {
                            document.getElementById("container-chart-pv").style.display="none";
                            $('#no-data-pv').removeClass('d-none');
                        }

                    }
                }
            });
        }

        var chart_btn = new Chart(document.getElementById('chart_url_btn'), {
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

        load_data_url_btn('');
            
            function load_data_url_btn(filter) {
                $.ajax({
                    url  : '<?php echo base_url();?>admin/report/cta_btn/load_data_url?'+filter,
                    data : { param:'btn' },
                    success: function(response) {
                        
                        if (response.status == 1) {
                            document.getElementById("container-chart-btn").style.display="block";
                            $('#no-data-btn').addClass('d-none');
                            
                            if (response.labels.length > 0) {
                                chart_btn.data.labels            = response.labels;
                                chart_btn.data.datasets[0].data  = response.datasets;
                                chart_btn.update();
                            } else {
                                document.getElementById("container-chart-btn").style.display="none";
                                $('#no-data-btn').removeClass('d-none');
                            }

                        }
                    }
                });
            }


            var options_line = {
                responsive: true,
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

            var chart_date = new Chart(document.getElementById('chart_filter_date'), {
                type: 'line',
                data: {
                    datasets: [{
                        label: ' ',
                        // backgroundColor: bg_chart_1,
                        // borderWidth: 0,
                        hoverOffset: 10,
                        lineTension: 0, 
                        borderColor: bg_chart,
                        fill: true
                    }],
                },
                options_line
            });

            load_total_date('today');

            function load_total_date(value) {
                $.ajax({
                    method  : 'post',
                    url     : '<?php echo base_url();?>admin/report/cta_btn/fetch_data_total_date',
                    data    : { value:value },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 1) {   

                            $('#title_filter_date').html(response.label_title);

                            chart_date.data.labels = response.labels;
                            chart_date.data.datasets[0].data = response.datasets;
                            chart_date.update();
                        }
                    },
                });
            }

            $(document).on('click', '#fil_total_date li a', function() {
                var value = $(this).attr('value');
                load_total_date(value);
            });
        // -------------------------- end total visitor --------------------------

    });

</script>