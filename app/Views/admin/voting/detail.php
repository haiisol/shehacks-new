
<div class="action-area">    
    <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
    <a type="submit" href="<?php echo base_url();?>admin/voting/hasil/export_detail/<?php echo $id_voting_enc; ?>" name="export" id="export_excel" class="btn btn-padd-xs btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Export"><span class="icon feather icon-file"></span> Export </a>
</div>


<div class="row mt-3">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title"><?php echo $title; ?></h6>

            <div class="table-responsive">
                <table id="table-data" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama User</th>
                            <th class="text-center">Telp User</th>
                            <th class="text-center">Email</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo view('admin/component/include_source'); ?>

<script type="text/javascript">

    $(document).ready(function() {
        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

        var id_enc = "<?php echo $id_voting_enc; ?>";

        var table = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/voting/hasil/datatables_detail/'+id_enc,
            },
            columnDefs : [
                {
                    className: "text-center",
                    targets: [0],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table.ajax.reload();
        });

        // --------------------------- filter & export data ---------------------------
            $('#form-filter').submit(function (e) {
                e.preventDefault();

                filter = $(this).serialize();

                url_reload = '<?php echo base_url();?>admin/voting/hasil/datatables_detail/'+id_enc;
                table.ajax.url(url_reload).load();

                url_export = '<?php echo base_url();?>admin/voting/hasil/export_detail/'+id_enc;
                $('#export-excel').attr('href', url_export);

            });
        // --------------------------- filter & export data ---------------------------

    });
</script>