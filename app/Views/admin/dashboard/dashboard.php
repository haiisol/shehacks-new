<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <a href="<?php echo base_url();?>admin/user/data-user" class="widget-icon-2 rounded-10 bg-light-primary text-primary me-3">
                        <ion-icon name="people-sharp"></ion-icon>
                    </a>

                    <div>
                        <p class="mb-1">Total User <span id="load_total_user_label"></span></p>
                        <h4 class="mb-0" id="load_total_user"></h4>
                    </div>

                    <div class="dropdown options position-absolute top-0 end-0 mt-2 me-3">
                        <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                            <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" id="fil_total_user">
                            <li><a class="dropdown-item" href="javascript:void(0);" value="all">All</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" value="today">Today</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" value="last7">Last 7 Days</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" value="month">This Month</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" value="year">This Year</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-success text-success me-3">
                        <ion-icon name="bulb-sharp"></ion-icon>
                    </div>

                    <div>
                        <p class="mb-1">Total User Ideasi</p>
                        <h4 class="mb-0" id="load_total_user_ideasi"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-tiffany text-tiffany me-3">
                        <ion-icon name="rocket-sharp"></ion-icon>
                    </div>
                    <div>
                        <p class="mb-1">Total User MVP</p>
                        <h4 class="mb-0" id="load_total_user_mvp"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-info text-info me-3">
                        <ion-icon name="layers-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Total User Kategori</h6>
                        <p>Berikut pengelompokan user berdasarkan Kategori</p>
                    </div>
                </div>

                <div class="chart-container6">
                    <!-- <div class="piechart-legend">
                        <h2 class="mb-1" id="legend_value_kategori"></h2>
                        <h6 class="mb-0" id="legend_title_kategori"></h6>
                    </div> -->
                    <canvas id="chart_kategori"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-secondary text-secondary me-3">
                        <ion-icon name="calendar-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Total User Channel</h6>
                        <p>Berikut pengelompokan user Channel atau tahun alumni</p>
                    </div>
                </div>

                <div class="chart-container6">
                    <canvas id="chart_channel"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-warning text-warning me-3">
                        <ion-icon name="library-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Total User Tingkat Pendidikan</h6>
                        <p>Berikut pengelompokan user berdasarkan tingkat pendidikan</p>
                    </div>
                </div>

                <div class="chart-container6">
                    <canvas id="chart_tingkat_pendidikan"></canvas>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-dark text-dark me-3">
                        <ion-icon name="information-circle-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>Total User Mendapatkan Informasi</h6>
                        <p>Berikut pengelompokan user berdasarkan dapat Informasi</p>
                    </div>
                </div>

                <div class="chart-container6">
                    <canvas id="chart_dapat_informasi"></canvas>
                </div>
            </div>
            
            <div class="scrollable" style="max-height: 400px;">
                <ul class="list-group list-group-flush w-100" id="load_data_dapat_informasi"></ul>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="widget-icon-2 rounded-10 bg-light-primary text-primary me-3">
                        <ion-icon name="map-sharp"></ion-icon>
                    </div>
                    <div class="mb-3">
                        <h6>User Berdasarkan Provinsi</h6>
                        <p>Berikut pengelompokan user berdasarkan Provinsi</p>
                    </div>
                </div>

                <div class="chart-container6">
                    <!-- <div class="piechart-legend">
                        <h2 class="mb-1" id="legend_value_provinsi"></h2>
                        <h6 class="mb-0" id="legend_title_provinsi"></h6>
                    </div> -->
                    <canvas id="chart_provinsi"></canvas>
                </div>
            </div>

            <div class="scrollable" style="max-height: 400px;">
                <ul class="list-group list-group-flush w-100" id="load_data_list_provinsi"></ul>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        // -------------------------- data info --------------------------
            // total user
            load_total_user('all');

            function load_total_user(value) {
                $.ajax({
                    url     : '<?php echo base_url();?>admin/dashboard/dashboard/data_info',
                    data    : { value:value },
                    success: function(response) {
                        // total user
                        $('#load_total_user_label').html(response.total_user_label);
                        $('#load_total_user').html(response.total_user);
                        $('#load_total_user_ideasi').html(response.total_user_ideasi);
                        $('#load_total_user_mvp').html(response.total_user_mvp);
                    },
                });
            }

            $(document).on('click', '#fil_total_user li a', function() {
                var value = $(this).attr('value');
                load_total_user(value);
            });
        // -------------------------- end data info --------------------------


        // -------------------------- conf chartjs --------------------------
            var bg_chart_1 = ['#FF64B4','#FFD28C','#82DCE1','#1787bd','#91E6CD','#FF4164','#FFD2D7','#69BEEB','#82AF9B','#E1A03C','#FAAF96',];

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
                        backgroundColor: bg_chart_1,
                        hoverBackgroundColor: bg_chart_1
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
        // -------------------------- end conf chartjs --------------------------

        
        // -------------------------- chart kategori --------------------------
            var chart_gt = new Chart(document.getElementById('chart_kategori'), {
                type: 'pie',
                data: {
                    datasets: [{
                        label: ' ',
                        backgroundColor: bg_chart_1,
                        borderWidth: 0,
                        hoverOffset: 10,
                    }],
                },
                options
            });
            
            load_data_kategori();
            
            function load_data_kategori() {
                $.ajax({
                    url : '<?php echo base_url();?>admin/dashboard/dashboard/load_data_kategori',
                    success: function(response) {
                        
                        if (response.status == 1) {
                            
                            // chart
                            // $('#legend_title_kategori').html(response.legend_title);
                            // $('#legend_value_kategori').html(response.legend_value);

                            chart_gt.data.labels = response.labels;
                            chart_gt.data.datasets[0].data = response.datasets;
                            chart_gt.update();

                            // list
                            // var load_data_list = '';

                            // $.each(response.data_list, function(i, val) {
                            //     load_data_list += 
                            //         '<li class="list-group-item d-flex justify-content-between align-items-center border-top">'+
                            //             '<span class="label">'+val.label+'</span>'+
                            //             '<span class="badge bg-secondary rounded-pill">'+val.value+' Orang</span>'
                            //         '</li>';
                            // });

                            // $('#load_data_list_kategori').html(load_data_list);
                        }
                    }
                });
            }
        // -------------------------- end chart kategori --------------------------


        // -------------------------- chart channel --------------------------
            var chart_ch = new Chart(document.getElementById('chart_channel'), {
                type: 'pie',
                data: {
                    datasets: [{
                        label: ' ',
                        backgroundColor: bg_chart_1,
                        borderWidth: 0,
                        hoverOffset: 10,
                    }],
                },
                options
            });
            
            load_data_channel();
            
            function load_data_channel() {
                $.ajax({
                    url : '<?php echo base_url();?>admin/dashboard/dashboard/load_data_channel',
                    success: function(response) {
                        if (response.status == 1) {
                            chart_ch.data.labels = response.labels;
                            chart_ch.data.datasets[0].data = response.datasets;
                            chart_ch.update();
                        }
                    }
                });
            }
        // -------------------------- end chart channel --------------------------
       

        // -------------------------- chart provinsi --------------------------
            var chart_pt = new Chart(document.getElementById('chart_provinsi'), {
                type: 'bar',
                data: {
                    datasets: [{
                        label: ' ',
                        backgroundColor: '#FF64B4',
                        borderWidth: 1,
                        hoverOffset: 5
                    }],
                },
                options: {
                    options,
                    plugins: {
                        legend: {
                            display: false,
                        },
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
                                display: true
                            },
                            display: true,
                            ticks: {
                                display: true
                            }
                        }
                    }
                }
            });
            
            load_data_provinsi();
            
            function load_data_provinsi() {
                $.ajax({
                    url : '<?php echo base_url();?>admin/dashboard/dashboard/load_data_provinsi',
                    success: function(response) {
                        
                        if (response.status == 1) {
                            
                            // chart
                            // $('#legend_title_provinsi').html(response.legend_title);
                            // $('#legend_value_provinsi').html(response.legend_value);

                            chart_pt.data.labels = response.labels;
                            chart_pt.data.datasets[0].data = response.datasets;
                            chart_pt.update();

                            // list
                            var load_data_list = '';

                            $.each(response.data_list, function(i, val) {
                                load_data_list += 
                                    '<li class="list-group-item d-flex justify-content-between align-items-center border-top">'+
                                        '<span class="label">'+val.label+'</span>'+
                                        '<span class="badge bg-secondary rounded-pill">'+val.value+' Orang</span>'
                                    '</li>';
                            });

                            $('#load_data_list_provinsi').html(load_data_list);
                        }
                    }
                });
            }
        // -------------------------- end chart provinsi --------------------------


        // -------------------------- chart tingkat pendidikan --------------------------
            var chart_t_pend = document.getElementById('chart_tingkat_pendidikan').getContext('2d');

            var gradientStroke1 = chart_t_pend.createLinearGradient(0, 0, 0, 400);
                gradientStroke1.addColorStop(0, '#000428');  
                gradientStroke1.addColorStop(1, '#004e92'); 

            var gradientStroke2 = chart_t_pend.createLinearGradient(0, 0, 0, 300);
                gradientStroke2.addColorStop(0, '#155472');
                gradientStroke2.addColorStop(1, '#1787bd');

            var gradientStroke3 = chart_t_pend.createLinearGradient(0, 0, 0, 500);
                gradientStroke3.addColorStop(0, '#2193b0');  
                gradientStroke3.addColorStop(1, '#6dd5ed'); 

            var gradientStroke4 = chart_t_pend.createLinearGradient(0, 1500, 0, 0);
                gradientStroke4.addColorStop(0, '#068a8f');  
                gradientStroke4.addColorStop(1, '#6dd5ed'); 

            var gradientStroke5 = chart_t_pend.createLinearGradient(0, 0, 0, 500);
                gradientStroke5.addColorStop(0, '#14aa62');  
                gradientStroke5.addColorStop(1, '#00eb7a'); 

            var gradientStroke6 = chart_t_pend.createLinearGradient(0, 1500, 0, 0);
                gradientStroke6.addColorStop(0, '#56ab2f');  
                gradientStroke6.addColorStop(1, '#a8e063'); 

            var chart_tp = new Chart(document.getElementById('chart_tingkat_pendidikan'), {
                type: 'bar',
                data: {
                    datasets: [{
                        label: ' ',
                        backgroundColor: [ gradientStroke1, gradientStroke2, gradientStroke3, gradientStroke4, gradientStroke5, gradientStroke6, ],
                        borderWidth: 0,
                        hoverOffset: 6
                    }],
                },
                options: {
                    options,
                    plugins: {
                        legend: {
                            display: false,
                        },
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
                                display: true
                            },
                            display: true,
                            ticks: {
                                display: true
                            }
                        }
                    }
                }
            });
            
            load_data_tingkat_pendidikan();
            
            function load_data_tingkat_pendidikan() {
                $.ajax({
                    url : '<?php echo base_url();?>admin/dashboard/dashboard/load_data_tingkat_pendidikan',
                    success: function(response) {
                        
                        if (response.status == 1) {
                            
                            chart_tp.data.labels = response.labels;
                            chart_tp.data.datasets[0].data = response.datasets;
                            chart_tp.update();
                        }
                    }
                });
            }
        // -------------------------- end chart tingkat pendidikan --------------------------


        // -------------------------- chart dapat informasi --------------------------
            var chart_d_info = document.getElementById('chart_dapat_informasi').getContext('2d');

            var gradientStroke1 = chart_d_info.createLinearGradient(0, 0, 0, 400);
                gradientStroke1.addColorStop(0, '#804674');  
                gradientStroke1.addColorStop(1, '#A86464'); 

            var gradientStroke2 = chart_d_info.createLinearGradient(0, 0, 0, 300);
                gradientStroke2.addColorStop(0, '#B3E5BE');
                gradientStroke2.addColorStop(1, '#F5FFC9');

            var gradientStroke3 = chart_d_info.createLinearGradient(0, 0, 0, 500);
                gradientStroke3.addColorStop(0, '#FFFFD0');  
                gradientStroke3.addColorStop(1, '#F3CCFF'); 

            var gradientStroke4 = chart_d_info.createLinearGradient(0, 1500, 0, 0);
                gradientStroke4.addColorStop(0, '#9A208C');  
                gradientStroke4.addColorStop(1, '#F5C6EC'); 

            var gradientStroke5 = chart_d_info.createLinearGradient(0, 0, 0, 500);
                gradientStroke5.addColorStop(0, '#94AF9F');  
                gradientStroke5.addColorStop(1, '#AEC2B6'); 

            var gradientStroke6 = chart_d_info.createLinearGradient(0, 1500, 0, 0);
                gradientStroke6.addColorStop(0, '#C9EEFF');  
                gradientStroke6.addColorStop(1, '#AA77FF'); 

            var chart_df = new Chart(chart_d_info, {
                type: 'pie',
                data: {
                    datasets: [{
                        label: 'liveCount',
                        backgroundColor: [ gradientStroke1, gradientStroke2, gradientStroke3, gradientStroke4, gradientStroke5, gradientStroke6, ],
                        borderWidth: 0,
                        hoverOffset: 6
                    }],
                },
                options
            });
            
            load_data_dapat_informasi();
            
            function load_data_dapat_informasi() {
                $.ajax({
                    url : '<?php echo base_url();?>admin/dashboard/dashboard/load_data_dapat_informasi',
                    success: function(response) {
                        
                        if (response.status == 1) {

                            chart_df.data.labels = response.labels;
                            chart_df.data.datasets[0].data = response.datasets;
                            chart_df.update();

                            // list
                            var load_data_list = '';

                            $.each(response.data_list, function(i, val) {
                                load_data_list += 
                                    '<li class="list-group-item d-flex justify-content-between align-items-center border-top">'+
                                        '<span class="label">'+val.label+'</span>'+
                                        '<span class="badge bg-secondary rounded-pill">'+val.value+' Orang</span>'
                                    '</li>';
                            });

                            $('#load_data_dapat_informasi').html(load_data_list);
                        }
                    }
                });
            }
        // -------------------------- end chart dapat informasi --------------------------

    });
</script>