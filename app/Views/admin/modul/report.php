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
                <select name="fil_kategori_user" id="fil_kategori_user" data-placeholder="Pilih Kategori User" class="form-control form-control-sm select2-custome">
                    <option value="0" selected>All Kategori</option>
                    <option value="Ideasi">Ideasi</option>
                    <option value="MVP">MVP</option>
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
                <select name="fil_umur" id="fil_umur" data-placeholder="Pilih Kategori User" class="form-control form-control-sm select2-custome">
                    <option value="0" selected>All Umur</option>
                        <option value="1">20 Sampai 30</option>
                        <option value="2">31 Sampai 40</option>
                        <option value="3">41 Sampai 50</option>
                        <option value="4">51 Lebih</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
            
            <div class="form-group">
                <a type="submit" href="<?php echo base_url();?>admin/modul/report/export" name="export" id="export_excel" class="btn btn-padd-xs btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Export"><span class="icon feather icon-file"></span></a>
            </div>
        </form>
    </div>
</div>


<div class="card mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Channel</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Total Modul</th>
                        <th class="text-center">Mendaftar</th>
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
                url : '<?php echo base_url();?>admin/modul/report/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, 1, -3, -2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 1, 2, 3, 4, 5, -2, -1],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        $('#form-filter').submit(function(e) {
            e.preventDefault();

            filter = $(this).serialize();
            url_reload = '<?php echo base_url();?>admin/modul/report/datatables/?' + filter;
            table_data.ajax.url(url_reload).load();

            // export excel
            url_export = '<?php echo base_url();?>admin/modul/report/export/?' + filter;
            $('#export_excel').attr('href', url_export);
        });

    });

</script>