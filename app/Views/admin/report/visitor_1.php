<script src="<?php echo base_url();?>assets/backoffice/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>

<div class="row">
    <div class="col-lg-6">
        <div class="card radius-10">
            <div class="card-body">
                <h6 class="card-title">Last 7 Days</h6>

                <div id="last_7_days"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card radius-10">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="card-title">Last 7 Days</h4>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <select name="fil_page_last7" id="fil_page_last7" class="form-control form-control-sm select2-custome-search">
                                <?php foreach($group_page_view as $gpv) { ?>
                                    <option value="<?php echo $gpv->page_view; ?>" class="select-dropdown__list-item"><?php echo $gpv->page_view; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive pt-3">
                    <table id="table-last7" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Page</th>
                                <th>Number of Visit</th>
                                <th>Hits</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-6">
        <div class="card radius-10">
            <div class="card-body">
                <h6 class="card-title">Days of Month</h6>

                <div id="visit_by_day"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card radius-10">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="card-title">Days of Month</h4>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <select name="fil_page_by_day" id="fil_page_by_day" class="form-control form-control-sm select2-custome-search">
                                <?php foreach($group_page_view as $gpv) { ?>
                                    <option value="<?php echo $gpv->page_view; ?>" class="select-dropdown__list-item"><?php echo $gpv->page_view; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive pt-3">
                    <table id="table-by-day" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Page</th>
                                <th>Number of Visit</th>
                                <th>Hits</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-6">
        <div class="card radius-10">
            <div class="card-body">
                <h6 class="card-title">Monthly History</h6>

                <div id="visit_by_month"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card radius-10">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="card-title">Monthly History</h4>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <select name="fil_page_by_month" id="fil_page_by_month" class="form-control form-control-sm select2-custome-search">
                                <?php foreach($group_page_view as $gpv) { ?>
                                    <option value="<?php echo $gpv->page_view; ?>" class="select-dropdown__list-item"><?php echo $gpv->page_view; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive pt-3">
                    <table id="table-by-month" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Page</th>
                                <th>Number of Visit</th>
                                <th>Hits</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
                    <div>
                        <h6 class="mb-4">Visitor</h6>
                    </div>
                    
                    <div >
                        <form method="post" id="form-filter-visitor" class="form-style">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <input type="text" name="fil_date" placeholder="Filter Tanggal" class="form-control form-control-sm datepicker-range-custom" autocomplete="off" style="width: 230px;">
                                </div>
                                
                                <div class="">
                                    <button type="submit" id="submit-filter-visitor" class="btn btn-padd-xs btn-primary px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><ion-icon class="me-0" name="funnel-sharp"></ion-icon> Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="table-data-visitor" class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Page</th>
                                <th class="text-center">Number of Visit</th>
                                <th class="text-center">Hits</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        // -------------------------- last_7_days --------------------------
            var options = {
                chart: {
                    height: 350,
                    type: "line",
                    parentHeightOffset: 0
                },
                series: <?php echo $series_visit_by_last7; ?>,
                xaxis: {
                    type: "text",
                    categories: <?php json_response($array_last7); ?>
                },
                colors: ["#82BB41", "#f77eb9", "#7ee5e5","#4d8af0"],
                grid: {
                    borderColor: "rgba(130, 187, 65, .1)",
                    padding: {
                        bottom: -15
                    }
                },
                stroke: {
                    width: 3,
                    curve: "smooth",
                    lineCap: "round"
                },
                legend: {
                    show: true,
                    position: "top",
                    horizontalAlign: 'left',
                    containerMargin: {
                        top: 30
                    }
                },
                responsive: [{
                    breakpoint: 500,
                    options: {
                        legend: {
                            fontSize: "11px"
                        }
                    }
                }]
            };
            var apexLineChart = new ApexCharts(document.querySelector("#last_7_days"), options);
            apexLineChart.render();


            var table = $('#table-last7').DataTable({
                ajax  : {
                    type : 'POST',
                    url  :  '<?php echo base_url('admin/report/visitor/last7_datatable/?filter_page=home'); ?>',
                    complete: function(data, type) {
                        json = data.responseJSON;
                    },
                },
                searching  : false,
                paging     : false,
                lengthMenu : false,
                processing : true,
                serverSide : true,
                columnDefs : [
                    {
                        className: 'text-center',
                        targets  : [0, -2, -1],
                    }
                ],
                language: {
                    zeroRecords: "Data tidak ditemukan"
                }
            });

            $('#fil_page_last7').change(function() {
                filter_page = $(this).val();
                url_reload  = '<?php echo base_url();?>admin/report/visitor/last7_datatable/?filter_page=' + filter_page;
                table.ajax.url(url_reload).load();
            });
        // -------------------------- end last_7_days --------------------------


        // -------------------------- visit_by_day --------------------------
            var options = {
                chart: {
                    height: 350,
                    type: "line",
                    parentHeightOffset: 0
                },
                series: <?php echo $series_visit_by_day; ?>,
                xaxis: {
                    type: "text",
                    categories: <?php json_response($array_day); ?>
                },
                colors: ["#82BB41","#f77eb9","#7ee5e5","#4d8af0"],
                grid: {
                    borderColor: "rgba(130, 187, 65, .1)",
                    padding: {
                        bottom: -15
                    }
                },
                stroke: {
                    width: 3,
                    curve: "smooth",
                    lineCap: "round"
                },
                legend: {
                    show: true,
                    position: "top",
                    horizontalAlign: 'left',
                    containerMargin: {
                        top: 30
                    }
                },
                responsive: [{
                    breakpoint: 500,
                    options: {
                        legend: {
                            fontSize: "11px"
                        }
                    }
                }]
            };
            var apexLineChart = new ApexCharts(document.querySelector("#visit_by_day"), options);
            apexLineChart.render();


            var table = $('#table-by-day').DataTable({
                ajax  : {
                    type : 'POST',
                    url  :  '<?php echo base_url('admin/report/visitor/by_day_datatable/?filter_page=home'); ?>',
                    complete: function(data, type) {
                        json = data.responseJSON;
                    },
                },
                searching  : false,
                paging     : false,
                lengthMenu : false,
                processing : true,
                serverSide : true,
                columnDefs : [
                    {
                        className: 'text-center',
                        targets  : [0, -2, -1],
                    }
                ],
                language: {
                    zeroRecords: "Data tidak ditemukan"
                }
            });

            $('#fil_page_by_day').change(function() {
                filter_page = $(this).val();
                url_reload  = '<?php echo base_url();?>admin/report/visitor/by_day_datatable/?filter_page=' + filter_page;
                table.ajax.url(url_reload).load();
            });
        // -------------------------- end visit_by_day --------------------------


        // -------------------------- visit_by_month --------------------------
            var options = {
                chart: {
                    height: 350,
                    type: "line",
                    parentHeightOffset: 0
                },
                series: <?php echo $series_visit_by_month; ?>,
                xaxis: {
                    type: "text",
                    categories: <?php json_response($category_month); ?>
                },
                colors: ["#82BB41", "#7ee5e5", "#f77eb9", "#4d8af0"],
                grid: {
                    borderColor: "rgba(130, 187, 65, .1)",
                    padding: {
                        bottom: -15
                    }
                },
                stroke: {
                    width: 3,
                    curve: "smooth",
                    lineCap: "round"
                },
                legend: {
                    show: true,
                    position: "top",
                    horizontalAlign: 'left',
                    containerMargin: {
                        top: 30
                    }
                },
                responsive: [{
                    breakpoint: 500,
                    options: {
                        legend: {
                            fontSize: "11px"
                        }
                    }
                }]
            };
            var apexLineChart = new ApexCharts(document.querySelector("#visit_by_month"), options);
            apexLineChart.render();


            var table = $('#table-by-month').DataTable({
                ajax  : {
                    type : 'POST',
                    url  :  '<?php echo base_url('admin/report/visitor/by_month_datatable/?filter_page=home'); ?>',
                    complete: function(data, type) {
                        json = data.responseJSON;
                    },
                },
                searching  : false,
                paging     : false,
                lengthMenu : false,
                processing : true,
                serverSide : true,
                columnDefs : [
                    {
                        className: 'text-center',
                        targets  : [0, -2, -1],
                    }
                ],
                language: {
                    zeroRecords: "Data tidak ditemukan"
                }
            });

            $('#fil_page_by_month').change(function() {
                filter_page = $(this).val();
                url_reload  = '<?php echo base_url();?>admin/report/visitor/by_month_datatable/?filter_page=' + filter_page;
                table.ajax.url(url_reload).load();
            });
        // -------------------------- end visit_by_month --------------------------


        // -------------------------- visitor --------------------------
            var table_data_visitor = $('#table-data-visitor').DataTable({
                ajax: {
                    method   : 'post',
                    url      : '<?php echo base_url();?>admin/report/visitor/datatables_visitor',
                    dataType : 'json',
                    complete: function(data, type) {
                        json = data.responseJSON;
                    },
                },
                order      : [[ 1, 'DESC' ]],
                pageLength : 50,
                processing : true,
                serverSide : true,
                retrieve   : true,
                ordering   : true,
                searching  : false,
                deferRender: true,
                scrollX       : false,
                scrollCollapse: false,
                pagingType : 'full_numbers',
                columnDefs : [
                    {
                        className: 'text-center',
                        targets  : [0, -2, -1],
                    },
                    {
                        orderable: false,
                        targets  : [2, 3, 4],
                    }
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Search ...',
                    lengthMenu: '<select class="form-control form-control-sm select2-custome">'+
                                    '<option value="10">10</option>'+
                                    '<option value="50">50</option>'+
                                    '<option value="100">100</option>'+
                                    '<option value="500">500</option>'+
                                    '<option value="1000">1000</option>'+
                                    '<option value="-1">All</option>'+
                                '</select>',
                    zeroRecords: 'Data tidak ditemukan'
                }
            });

            $('#form-filter-visitor').submit(function(e) {
                e.preventDefault();

                filter = $(this).serialize();
                url_reload = '<?php echo base_url();?>admin/report/visitor/datatables_visitor/?' + filter;
                table_data_visitor.ajax.url(url_reload).load();
            });
        // -------------------------- end visitor --------------------------
    });
</script>