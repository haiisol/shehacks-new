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
                                <th class="text-center">Icon</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Deskripsi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Date Update</th>
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

                    <div class="form-group">
                        <label class="control-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="heading" id="heading" placeholder="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="content" id="content" placeholder="" class="form-control" style="height: 150px"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">Image</label>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail">
                                <img id="image_preview" src="<?php echo default_image(); ?>">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail"></div>
                            <div id="image_feed">
                                <span class="btn btn-outline-primary btn-file">
                                    <span class="fileupload-new"><span class="icon feather icon-image"></span> Pilih File</span>
                                    <span class="fileupload-exists"><span class="icon feather icon-repeat"></span> Ganti</span>
                                    <input type="file" name="image" id="image" accept="image/*">
                                </span> 
                                <a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><span class="icon feather icon-trash"></span> Hapus</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Status <span class="text-danger">*</span></label><br>
                        <select name="status" id="status" data-placeholder="Pilih Status" class="form-control select2-custome">
                            <option value="" disabled></option>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
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
                        <p class="label">Judul :</p>
                        <p class="value" id="d_heading"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Deskripsi :</p>
                        <p class="value" id="d_content"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Status :</p>
                        <p class="value" id="d_status"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Date Update :</p>
                        <p class="value" id="d_date"></p>
                    </div>
                    <div class="list-item">
                        <p class="label">Image :</p>
                        <p class="value" id="d_image">
                            <a class="image-popup"><img class="img-fluid img-profile-md rounded-8"></a>
                        </p>
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
                url : '<?php echo base_url();?>admin/content/program/topik/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets  : [0, 1, 3, 4, -1],
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

        $.validator.addMethod('cek_value', function(value, element) {
            $.ajax({
                method   : 'post',
                url      : '<?php echo base_url();?>admin/content/program/topik/cek_value',
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
                heading: {
                    required: true,
                    cek_value: {
                        depends: function(element) {
                            if ($param_input.val() == 'edit') {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                },
                content: {
                    required: true,
                },
                status: {
                    required: true,
                },
                image: {
                    required: {
                        depends: function(element) {
                            if ($param_input.val() == 'edit') {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    },
                    extension: 'jpg|jpeg|png',
                    filesize: 1000000
                },
            },
            messages: {
                heading: {
                    required: 'Title harus diisi.',
                    cek_value: 'Title sudah digunakan'
                },
                content: {
                    required: 'Content harus diisi.'
                },
                status: {
                    required: 'Status harus dipilih.'
                },
                image: {
                    required: 'Image harus diisi.',
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
                if(element.is('#image')) {
                    error.insertAfter('#image_feed').addClass('has-error');
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
                $('#image_preview').attr('src', '<?php echo default_image(); ?>');
            });

            $(document).on('click', '.edit-data', function() {
                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');

                save_method = 'edit';
                $('#param').val('edit');

                $('#form-data')[0].reset();
                $('#image_preview').attr('src', '<?php echo default_image(); ?>');

                $.ajax({
                    type     : 'POST',
                    url      : '<?php echo base_url(); ?>admin/content/program/topik/get_data',
                    data     : { id:id },
                    dataType : 'json',
                    success: function(response) {
                        $('#id').val(response.id);
                        $('#heading').val(response.heading);
                        $('#subheading').val(response.subheading);
                        $('textarea#content').val(response.content);
                        $('#status').val(response.status).trigger('change');
                        $('#image_preview').attr('src', response.image);
                    }
                });
                return false;
            });


            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    
                    $('#submit-form-data').buttonLoader('start');

                    if(save_method == 'add') {
                        url = '<?php echo base_url(); ?>admin/content/program/topik/add_data';
                    } else {
                        url = '<?php echo base_url(); ?>admin/content/program/topik/edit_data';
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


        // --------------------------- detail data ---------------------------
            $(document).on('click', '.detail-data', function() {
                var id = $(this).attr('data');

                $('#modal-detail-data').modal('show');

                $.ajax({
                    type     : 'POST',
                    url      : '<?php echo base_url(); ?>admin/content/program/topik/detail_data',
                    data     : { id:id },
                    dataType : 'json',
                    success: function(response) {
                        $('#d_heading').html(response.heading);
                        $('#d_content').html(response.content);
                        $('#d_status').html(response.status);
                        $('#d_date').html(response.date);
                        
                        $('#d_image a').attr('href', response.image);
                        $('#d_image img').attr('src', response.image);
                    }
                });
            });
        // --------------------------- end detail data ---------------------------


        // --------------------------- change status ---------------------------
            $(document).on('click', '.change-status', function() {
                var id    = $(this).attr('data');
                var param = $(this).attr('param');

                if (param == 1) {
                    var title_text = 'Deactivate Item';
                    var caption_text = 'Apakah anda yakin ingin me-nonaktifkan Item ini?';
                } else {
                    var title_text = 'Activate Item';
                    var caption_text = 'Apakah anda yakin ingin meng-aktifkan Item ini?';
                }

                $('#modal-change-status').modal('show');
                $('.modal-status-title').text(title_text);
                $('.modal-status-caption').text(caption_text);

                $('#id4').val(id);
                $('#param4').val(param);
            });

            $(document).on('click', '#submit-change-status', function() {
                var id    = $('#id4').val();
                var param = $('#param4').val();

                $('#submit-change-status').buttonLoader('start');

                $.ajax({
                    type : 'POST',
                    url  : '<?php echo base_url();?>admin/content/program/topik/change_status',
                    data : { id:id, param:param },
                    dataType : 'json',
                    success:function(response) {
                        $('#submit-change-status').buttonLoader('stop');
                        $('#modal-change-status').modal('hide');
                        table_data.ajax.reload();

                        if(response.status == 1) {
                            notif_success(response.message);
                        } else {
                            notif_error(response.message);
                        }
                    }
                })
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
                    url  : '<?php echo base_url();?>admin/content/program/topik/delete_data',
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