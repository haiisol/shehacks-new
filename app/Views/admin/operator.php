<div class="action-area">    
    <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
    <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
    <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
</div>
                            
<div class="row mt-3">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center py-0"><label class="checkbox-custome"><input type="checkbox" name="check-all-record"></label></th>
                            <th class="text-center">No</th>
                            <th class="text-center">Photo</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Hak Akses</th>
                            <th class="text-center">Terakhir Login</th>
                            <th class="text-center">Status Aktif</th>
                            <th class="text-center" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>

            <div class="modal-body">
                <form method="post" id="form-data" enctype="multipart/form-data">
                    <input type="hidden" name="param" id="param">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="nama_admin" id="nama_admin" placeholder="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email_admin" id="email_admin" placeholder="" class="form-control">
                            </div>

                            <div class="form-group fg-password">
                                <label class="control-label">Password</label>
                                <input type="text" name="password_admin" id="password_admin" placeholder="******" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">No. Telephone <span class="text-danger">*</span></label>
                                <input type="text" name="phone_admin" id="phone_admin" placeholder="" class="form-control">
                            </div>

                            <div class="form-group wrap-role">
                                <label class="control-label">Pilih Role User <span class="text-danger">*</span></label><br>
                                <select name="id_role" id="id_role" data-placeholder="Pilih role user" class="form-control select2">
                                    <?php $get_role = $this->db->query("SELECT * FROM tb_admin_user_role ORDER BY role_admin ASC")->result_array(); ?>
                                    <option value="" selected disabled></option>
                                    <?php foreach ($get_role as $val_role) { ?>
                                        <option value="<?php echo $val_role['id_role']; ?>"><?php echo $val_role['role_admin']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Unggah Foto</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail">
                                        <img id="photo_preview" src="<?php echo default_image(); ?>">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                    <div id="photo_feed">
                                        <span class="btn btn-outline-primary btn-file">
                                            <span class="fileupload-new"><span class="icon feather icon-image"></span> Pilih File</span>
                                            <span class="fileupload-exists"><span class="icon feather icon-repeat"></span> Ganti</span>
                                            <input type="file" name="photo" id="photo" accept="image/*">
                                        </span> 
                                        <a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><span class="icon feather icon-trash"></span> Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                url : '<?php echo base_url();?>admin/operator/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, 1, -4, -3, -2, -1],
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

        $.validator.addMethod('cek_email', function(value, element) {
            $.ajax({
                method   : 'post',
                url      : '<?php echo base_url();?>admin/operator/cek_email',
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
                    required: true
                },
                id_role: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    cek_email: {
                        depends: function(element) {
                            if ($param_input.val() == "edit") {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                },
                phone: {
                    required: true,
                    number: true,
                    minlength:10,
                    maxlength:12
                },
                password: {
                    required: {
                        depends: function(element) {
                            if ($param_input.val() == "edit") {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    },
                    minlength: 6
                },
                photo: {
                    extension: 'jpg|jpeg|png',
                    filesize: 1000000
                },
            },
            messages: {
                nama: {
                    required: 'Nama harus diisi.'
                },
                id_role: {
                    required: 'Role user harus dipilih.'
                },
                email: {
                    required: 'Email harus diisi.',
                    email: 'Silahkan masukan alamat email dengan benar',
                    cek_email: 'Email sudah terdaftar'
                },
                phone: {
                    required: 'No Telp harus diisi.',
                    number: 'Silahkan masukan no telp dengan benar',
                    minlength: 'Format telepon minimal 10 karakter',
                    maxlength: 'Format telepon max 12 karakter'
                },
                password: {
                    required: 'Password harus diisi.',
                    minlength: 'Password minimal 6 karakter'
                },
                photo: {
                    extension: 'Unggah file dengan format .PNG/.JPG/.JPEG',
                    filesize: 'File maksimal 1 Mbps.'
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
                if(element.is('#photo')) {
                    error.insertAfter('#photo_feed').addClass('has-error');
                } else {
                    error.appendTo(element.parent());
                }
            }
        });

        // --------------------------- add & edit data --------------------------- 
            $(document).on('click', '#add-data', function() {
                $('#modal-data').modal('show');
                $('.modal-title').text('Tambah data');

                save_method = 'add';
                $('#param').val('add');

                $('#form-data')[0].reset();
                $('#photo_preview').attr('src', '<?php echo default_image(); ?>');
                $('.wrap-role').show();
                $('#id_role').val('').trigger('change');
            });

            $(document).on('click', '.edit-data', function() {
                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');

                save_method = 'edit';
                $('#param').val('edit');

                $('#form-data')[0].reset();
                $('#photo_preview').attr('src', '<?php echo default_image(); ?>');
                $('.wrap-role').show();
                $('#id_role').val('').trigger('change');

                $.ajax({
                    type     : 'POST',
                    url      : '<?php echo base_url(); ?>admin/operator/get_data',
                    data     : { id:id },
                    dataType : 'json',
                    success: function(response) {
                        $('#id').val(response.id_admin);
                        $('#nama_admin').val(response.nama_admin);
                        $('#email_admin').val(response.email_admin);
                        $('#phone_admin').val(response.phone_admin);
                        $('#id_role').val(response.id_role).trigger('change');
                        $('#photo_preview').attr('src', response.photo);

                        if (response.id_role == 0) {
                            $('.wrap-role').hide();
                        } else {
                            $('.wrap-role').show();
                        }
                    }
                });
                return false;
            });


            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {

                    $('#submit-form-data').buttonLoader('start');

                    if(save_method == 'add') {
                        url = '<?php echo base_url(); ?>admin/operator/add_data';
                    } else {
                        url = '<?php echo base_url(); ?>admin/operator/edit_data';
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
                    url      : '<?php echo base_url(); ?>admin/operator/change_status',
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
                    url  : '<?php echo base_url();?>admin/operator/delete_data',
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