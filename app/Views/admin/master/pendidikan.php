<div class="action-area">    
    <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
    <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
    <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
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
                                <th class="text-center">Tingkat Pendidikan</th>
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


<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>

            <div class="modal-body">
                <form method="post" id="form-data" enctype="multipart/form-data">
                    <input type="hidden" name="param" id="param">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group">
                        <label class="control-label">Tingkat Pendidikan<span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" placeholder="" class="form-control">
                    </div>

                    <div class="modal-action">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary"><span class="icon feather icon-x"></span>Batal</button>
                        <button type="submit" id="submit-form-data" class="btn btn-primary"><span class="icon feather icon-check"></span>Selesai</button>
                    </div>
                </form>
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
                url : '<?php echo base_url();?>admin/master/pendidikan/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, 1, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 1, -1],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        $.validator.addMethod('cek_value', function(value, element) {
            $.ajax({
                method   : 'post',
                url      : '<?php echo base_url();?>admin/master/pendidikan/cek_value',
                data     : { value:value },
                dataType : 'json',
                success: function(response) {
                    if(response.status == 1) {
                        result = true;
                    } else {
                        result = false;
                    }
                },
                async: false
            });
            return result;
        });

        var $param_input = $('#param');
        var validate_form = $('#form-data').validate({
            rules: {
                nama: {
                    required: true,
                    cek_value: {
                        depends: function(element) {
                            if ($param_input.val() == "edit") {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                },
            },
            messages: {
                nama: {
                    required: 'Tingkat pendidikan harus diisi.',
                    cek_value: 'Tingkat pendidikan sudah digunakan'
                },
            },
            errorElement: 'em',
            errorClass: 'has-error',
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-error')
                $(element).addClass('has-error')
            },
            unhighlight: function(element, errorClass) {
                $(element).parent().removeClass('has-error')
                $(element).removeClass('has-error')
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            }
        });

        // --------------------------- add & edit data --------------------------- 
            $(document).on('click', '#add-data', function() {
                $('#modal-data').modal('show');
                $('.modal-title').text('Tambah data');

                save_method = 'add';
                $('#param').val('add');

                $('#form-data')[0].reset();
            });

            $(document).on('click', '.edit-data', function() {
                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');

                save_method = 'edit';
                $('#param').val('edit');

                $('#form-data')[0].reset();

                $.ajax({
                    type     : 'POST',
                    url      : '<?php echo base_url(); ?>admin/master/pendidikan/get_data',
                    data     : { id:id },
                    dataType : 'json',
                    success: function(response) {
                        $('#id').val(response.id_pendidikan);
                        $('#nama').val(response.nama);
                    }
                });
                return false;
            });


            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {

                    $('#submit-form-data').buttonLoader('start');

                    if(save_method == 'add') {
                        url = '<?php echo base_url(); ?>admin/master/pendidikan/add_data';
                    } else {
                        url = '<?php echo base_url(); ?>admin/master/pendidikan/edit_data';
                    }

                    $.ajax({
                        url      : url,
                        method   : 'post',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            $('#submit-form-data').buttonLoader('stop');
                            $('#modal-data').modal('hide');

                            if(response.status == 1 || response.status == 3) {
                                $('#form-data')[0].reset();
                                table_data.ajax.reload();

                                notif_success(response.message);
                            } 
                            else {
                                notif_error(response.message);
                            } 
                        }
                    })
                }
            });
        // --------------------------- end add & edit data ---------------------------


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
                    url  : '<?php echo base_url();?>admin/master/pendidikan/delete_data',
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


    });

</script>