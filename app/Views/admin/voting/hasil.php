<div class="wrap-action">
    <div class="action-area">    
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <select name="fil_kategori" id="fil_kategori" data-placeholder="Pilih Kategori" class="form-control form-control-sm select2-custome">
                    <option value="0" selected>All Kategori</option>
                    <option value="Ideasi">Ideasi</option>
                    <option value="Innovate">Innovate</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>

            <div class="form-group">
                <a type="submit" href="<?php echo base_url();?>admin/voting/hasil/export" name="export" id="export_excel" class="btn btn-padd-xs btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Export"><span class="icon feather icon-file"></span></a>
            </div>
        </form>
    </div>
</div>


<div class="card mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center py-0"><label class="checkbox-custome"><input type="checkbox" name="check-all-record"></label></th>
                        <th class="text-center">No</th>
                        <th class="text-center">Logo</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Nama Founders</th>
                        <th class="text-center">Nama Usaha</th>
                        <th class="text-center">Total Voting</th>
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

        var table_data = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/voting/hasil/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets   : [0, 1, 2, 3, -2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 2, 3, -1],
                }
            ],
        });
        
        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        // --------------------------- filter & export data ---------------------------
            $('#form-filter').submit(function (e) {
                e.preventDefault();

                filter = $(this).serialize();

                url_reload = '<?php echo base_url();?>admin/voting/hasil/datatables/?' + filter;
                table_data.ajax.url(url_reload).load();

                url_export = '<?php echo base_url();?>admin/voting/hasil/export/?' + filter;
                $('#export-excel').attr('href', url_export);

            });
        // --------------------------- filter & export data ---------------------------

    });
</script>