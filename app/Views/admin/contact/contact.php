<div class="wrap-action">
    <div class="action-area">
        <!-- <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a> -->
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
        <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <input type="text" name="fil_date" class="form-control form-control-sm datepicker-range" style="width: 200px;">
            </div>
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
            
            <div class="form-group">
                <a type="submit" href="<?php echo base_url();?>admin/contact/contact/export" name="export" id="export_excel" class="btn btn-padd-xs btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Export"><span class="icon feather icon-file"></span></a>
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
                        <th class="text-center py-0"><label class="checkbox-custome"><input type="checkbox" name="check-all-record"></label></th>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Subject</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center" width="15%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-detail-data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail</h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>

            <div class="modal-body">
                <div class="list-info">
                    <div class="list-item">
                        <p class="label">Nama :</p>
                        <p class="value" id="d_nama"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Email :</p>
                        <p class="value" id="d_email"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Phone :</p>
                        <p class="value" id="d_phone"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Subject :</p>
                        <p class="value" id="d_subject"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Pesan :</p>
                        <p class="value" id="d_message"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Date :</p>
                        <p class="value" id="d_date"></p>
                    </div>
                </div>
            </div>
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
                url : '<?php echo base_url();?>admin/contact/contact/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, 1, -1],
                },
                {
                    orderable: false,
                    targets  : [0, -1],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        $('#form-filter').submit(function(e) {
            e.preventDefault();

            filter = $(this).serialize();
            url_reload = '<?php echo base_url();?>admin/contact/contact/datatables/?' + filter;
            table_data.ajax.url(url_reload).load();

            // export excel
            url_export = '<?php echo base_url();?>admin/contact/contact/export/?' + filter;
            $('#export_excel').attr('href', url_export);
        });

        // --------------------------- detail data ---------------------------
            $(document).on('click', '.detail-data', function() {
                var id = $(this).attr('data');

                $('#modal-detail-data').modal('show');

                $.ajax({
                    type     : 'POST',
                    url      : '<?php echo base_url(); ?>admin/contact/contact/detail_data',
                    data     : { id:id },
                    dataType : 'json',
                    success: function(response) {
                        $('#d_nama').html(response.nama);
                        $('#d_email').html(response.email);
                        $('#d_phone').html(response.phone);
                        $('#d_subject').html(response.subject);
                        $('#d_message').html(response.message);
                        $('#d_date').html(response.date);
                        
                    }
                });
            });
        // --------------------------- end detail data ---------------------------


    });

</script>