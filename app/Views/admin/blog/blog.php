<?php $id_newadd = encrypt_url(0); ?>

<div class="wrap-action">
    <div class="action-area">    
        <a href="<?php echo base_url();?>admin/blogs/tambah/<?php echo $id_newadd; ?>" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
        <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <select name="fil_kategori" id="fil_kategori" data-placeholder="Pilih kategori" data-allow-clear="false" class="form-control form-control-sm select2-custome-search">
                    <option value="0" selected>All Kategori</option>
                    <?php $get_sk = $this->db->query("SELECT * FROM tb_blog_kategori WHERE status_delete = 0 ORDER BY nama ASC")->result_array(); ?>
                    <?php foreach ($get_sk as $val_kategori) { ?>   
                        <option value="<?php echo $val_kategori['id_blog_kategori']; ?>"><?php echo $val_kategori['nama']; ?></option> 
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
        </form>
    </div>
</div>
                            
<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-data" class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-0"><label class="checkbox-custome"><input type="checkbox" name="check-all-record"></label></th>
                                <th class="text-center">No</th>
                                <th class="text-center">Gambar</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Viewer</th>
                                <th class="text-center">Date Create</th>
                                <th class="text-center" width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APPPATH.'views/admin/component/include_source.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {

        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

        var table_data = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/blog/blog/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, 1, 2, -3, -2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 2, -1],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        $('#form-filter').submit(function(e) {
            e.preventDefault();

            filter = $(this).serialize();
            url_reload = '<?php echo base_url();?>admin/blog/blog/datatables/?' + filter;
            table_data.ajax.url(url_reload).load();
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
                    url  : '<?php echo base_url();?>admin/blog/blog/delete_data',
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


        // --------------------------- handle address ---------------------------
            $(document).on('change', '.provinsi', function() {
                var id = $(this).val();

                $.ajax({
                    type : 'POST',
                    url  : '<?php echo base_url();?>admin/dashboard/dashboard/get_address',
                    data : { id:id, param:'provinsi' },
                    dataType : 'json',
                    success: function(response) {
                        $('.kabupaten').html(response.data);
                    }
                });
            });

            $(document).on('change', '.kabupaten', function() {
                var id = $(this).val();

                $.ajax({
                    type : 'POST',
                    url  : '<?php echo base_url();?>admin/dashboard/dashboard/get_address',
                    data : { id:id, param:'kabupaten' },
                    dataType : 'json',
                    success: function(response) {
                        $('.kecamatan').html(response.data);
                    }
                });
            });
        // --------------------------- end handle address ---------------------------

    });

</script>