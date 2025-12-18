<div class="wrap-action">
    <div class="action-area">    
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
        <a href="javacript:void(0);" id="kurasi-data-multiple" data="kurasi" class="btn btn-success invisible"><span class="icon feather icon-user"></span> Kurasi User</a>
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <input type="text" name="fil_date" class="form-control form-control-sm datepicker-range" style="width: 200px;">
            </div>

            <div class="form-group mb-2">
                <select name="fil_channel" id="fil_channel" data-placeholder="Pilih Kategori User" class="form-control form-control-sm select2-custome" style="width: 160px;">
                    <option value="0" selected>All Channel / Alumni</option>
                    <option value="1">2023</option>
                    <option value="2">2024</option>
                    <!-- <option value="3">2023, 2024</option> -->
                    <option value="4">2025</option>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_kategori_user" id="fil_kategori_user" data-placeholder="Pilih Kategori User" class="form-control form-control-sm select2-custome">
                    <option value="0" selected>All Kategori User</option>
                    <option value="Ideasi">Ideasi</option>
                    <option value="MVP">MVP</option>
                    <option value="Pandu Perempuan Daerah">Pandu Perempuan Daerah</option>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_jumlah_anggota" id="fil_jumlah_anggota" data-placeholder="Pilih Kategori User" class="form-control form-control-sm select2-custome">
                    <option value="0" selected>All Anggota Team</option>
                    <option value="1">1 Sampai 5</option>
                    <option value="2">6 Sampai 10</option>
                    <option value="3">11 lebih</option>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_dapat_informasi" id="fil_dapat_informasi" data-placeholder="Pilih Jabatan" class="form-control form-control-sm select2-custome-search">
                    <option value="0" selected>All Informasi</option>
                    <?php $get_infor = $this->db->query("SELECT id_informasi, nama FROM tb_master_dapat_informasi 
                        WHERE status_delete = 0 ORDER BY nama ASC")->result_array(); 
                    ?>                    
                    <?php foreach ($get_infor as $val_infor) { ?>   
                        <option value="<?php echo $val_infor['id_informasi']; ?>"><?php echo $val_infor['nama']; ?></option> 
                    <?php } ?>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_provinsi" id="fil_provinsi" data-placeholder="Pilih Jabatan" class="form-control form-control-sm select2-custome-search">
                    <option value="0" selected>All Provinsi</option>
                    <?php $get_prov = $this->db->query("SELECT id, name FROM tb_master_province ORDER BY name ASC")->result_array(); 
                    ?>                    
                    <?php foreach ($get_prov as $val_prov) { ?>   
                        <option value="<?php echo $val_prov['id']; ?>"><?php echo $val_prov['name']; ?></option> 
                    <?php } ?>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_pendidikan" id="fil_pendidikan" data-placeholder="Pilih Pendidikan" class="form-control form-control-sm select2-custome-search">
                    <option value="0" selected>All Pendidikan</option>
                    <?php $get_pendidikan = $this->db->query("SELECT id_pendidikan, nama FROM tb_master_pendidikan ORDER BY id_pendidikan ASC")->result_array(); 
                    ?>                    
                    <?php foreach ($get_pendidikan as $val_pe) { ?>   
                        <option value="<?php echo $val_pe['id_pendidikan']; ?>"><?php echo $val_pe['nama']; ?></option> 
                    <?php } ?>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_umur" id="fil_umur" data-placeholder="Pilih Kategori User" class="form-control form-control-sm select2-custome-search">
                    <option value="0" selected>All Umur</option>
                        <option value="1">20 Sampai 30</option>
                        <option value="2">31 Sampai 40</option>
                        <option value="3">41 Sampai 50</option>
                        <option value="4">51 Lebih</option>
                </select>
            </div>

            <div class="form-group mb-2">
                <select name="fil_pp" id="fil_pp" data-placeholder="Pilih Pilot Project" class="form-control form-control-sm select2-custome">
                    <option value="0" selected>All Pilot Project</option>
                    <option value="1">Lengkapi</option>
                    <option value="2">Tidak Lengkapi</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
            
            <div class="form-group">
                <a type="submit" href="<?php echo base_url();?>admin/user/data_user/export" name="export" id="export_excel" class="btn btn-padd-xs btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Export"><span class="icon feather icon-file"></span></a>
            </div>
        </form>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:void(0)" class="widget-icon-2 rounded-10 bg-light-accent text-primary me-3">
                        <ion-icon name="calendar-sharp"></ion-icon>
                    </a>
                    <div>
                        <p class="mb-1">Channel 2023</p>
                        <p class="mb-1"><span class="label_date"></span></p>
                        <h6 id="load_total_2023"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:void(0)" class="widget-icon-2 rounded-10 bg-light-success text-success me-3">
                        <ion-icon name="calendar-sharp"></ion-icon>
                    </a>
                    <div>
                        <p class="mb-1">Channel 2024</p>
                        <p class="mb-1"><span class="label_date"></span></p>
                        <h6 id="load_total_2024"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:void(0)" class="widget-icon-2 rounded-10 bg-light-danger text-danger me-3">
                        <ion-icon name="calendar-sharp"></ion-icon>
                    </a>
                    <div>
                        <p class="mb-1">Channel 2025</p>
                        <p class="mb-1"><span class="label_date"></span></p>
                        <h6 id="load_total_2025"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-0"><label class="checkbox-custome"><input type="checkbox" name="check-all-record"></label></th>
                        <th class="text-center">No</th>
                        <th class="text-center">Channel</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Nama Startup</th>
                        <th class="text-center">Mendaftar</th>
                        <th class="text-center">Ver Email</th>
                        <th class="text-center">Kurasi User</th>
                        <th class="text-center" width="15%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<?php include APPPATH.'views/admin/component/include_source.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

        var save_method;

        var table_data = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/user/data_user/datatables',
                complete: function(data, type) {
                    $('#load_total_2023').html(json.total_2023);
                    $('#load_total_2024').html(json.total_2024);
                    $('#load_total_2025').html(json.total_2025);
                    //$('.label_date').html(json.label_date);
                },
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, 1, 2, 3, -3, -2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 1, -3, -2, -1],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        $('#form-filter').submit(function(e) {
            e.preventDefault();

            filter = $(this).serialize();
            url_reload = '<?php echo base_url();?>admin/user/data_user/datatables/?' + filter;
            table_data.ajax.url(url_reload).load();

            // export excel
            url_export = '<?php echo base_url();?>admin/user/data_user/export/?' + filter;
            $('#export_excel').attr('href', url_export);
        });

        // --------------------------- delete data ---------------------------
            // delete single
            $(document).on('click', '#delete-data', function() {
                var id = $(this).attr('data');
                $('#modal-delete').modal('show');
                $('.modal-title').text('Hapus data');
                $('#id3').val(id);
                $('#method').val('single');
            });

            // post delete
            $(document).on('click', '#button-delete', function() {
                var id = $('#id3').val();
                var method = $('#method').val();

                $.ajax({
                    type : 'POST',
                    url  : '<?php echo base_url();?>admin/user/data_user/delete_data',
                    data : { id:id, method:method },
                    dataType : 'json',
                    success: function(response) {
                        $('#modal-delete').modal('hide');
                        table_data.ajax.reload();

                        if(response.status == 1) {
                            notif_success(response.message);
                        } else if(response.status == 2) {
                            notif_success(response.message);
                            $('#delete-data-multiple').addClass('invisible', true);
                        } else {
                            notif_error(response.message);
                        }
                    }
                });
                return false;
            });
        // --------------------------- end delete data ---------------------------

        // --------------------------- kurasi data ---------------------------
            // post kurasi
            $(document).on('click', '#button-kurasi', function() {
                var id = $('#id3').val();
                var method = $('#method').val();

                $.ajax({
                    type : 'POST',
                    url  : '<?php echo base_url();?>admin/user/data_user/kurasi_data',
                    data : { id:id, method:method },
                    dataType : 'json',
                    success: function(response) {
                        $('#modal-kurasi').modal('hide');
                        table_data.ajax.reload();

                        if(response.status == 1) {
                            notif_success(response.message);
                        } else if(response.status == 2) {
                            notif_success(response.message);
                            $('#kurasi-data-multiple').addClass('invisible', true);
                        } else {
                            notif_error(response.message);
                        }
                    }
                });
                return false;
            });
        // --------------------------- end delete data ---------------------------

        // --------------------------- change status ---------------------------
            $(document).on('click', '.change-status', function() {
                var id = $(this).attr('data');
                var param = $(this).attr('param');

                $('#modal-change-status').modal('show');
                $('#id4').val(id);
                $('#param4').val(param);
            });

            $(document).on('click', '#submit-change-status', function() {
                var id = $('#id4').val();
                var param = $('#param4').val();

                $('#submit-change-status').buttonLoader('start');

                $.ajax({
                    type     : 'POST',
                    url      : '<?php echo base_url(); ?>admin/user/data_user/change_status',
                    data     : { id:id, param:param },
                    dataType : 'json',
                    success: function(response) {
                        $('#modal-change-status').modal('hide');
                        $('#submit-change-status').buttonLoader('stop');

                        if(response.status == 1) {
                            table_data.ajax.reload();
                            notif_success(response.message);
                        } else {
                            notif_error(response.message);
                        }
                    }
                });
                return false;
            });
        // --------------------------- end change status ---------------------------

    });

</script>