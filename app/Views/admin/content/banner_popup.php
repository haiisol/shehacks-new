<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form method="post" id="form-data" enctype="multipart/form-data" role="form">
                    <input type="hidden" name="id" id="id">

                    <div class="row">
                        <div class="col-md-6 pr-md-5">
                            <div class="form-group">
                                <label class="control-label">Unggah Banner</label>
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
                                <label class="control-label">Link</label>
                                <input type="text" name="button_url" id="button_url" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Status <span class="text-danger">*</span></label><br>
                                <select name="status" id="status" data-placeholder="Pilih Status" class="form-control select2-custome">
                                    <option value="" disabled></option>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 <?php echo $access_edit; ?>">
                            <button type="submit" id="submit-form-data" class="btn btn-primary"><span class="icon feather icon-check"></span>Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.getScript('<?php echo base_url();?>assets/backoffice/js/custome.js');

        var validate_form = $('#form-data').validate({
            rules: {
                // button_url: {
                //     required: true
                // },
                status: {
                    required: true,
                },
                image: {
                    extension: 'jpg|jpeg|png',
                    filesize: 1000000
                },
            },
            messages: {
                // button_url: {
                //     required: 'Link harus diisi.'
                // },
                status: {
                    required: 'Status harus dipilih.'
                },
                image: {
                    extension: 'Unggah file dengan format .PNG/.JPG/.JPEG/.WebP',
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
                    error.insertAfter('.image_feed');
                } else {
                    error.appendTo(element.parent());
                }
            }
        });

        load_data();

        function load_data() {
            $.ajax({
                url : '<?php echo base_url();?>admin/content/banner_popup/get_data',
                success  : function(response) {
                    $('#id').val(response.id);
                    $('#button_url').val(response.button_url);
                    $('#status').val(response.status).trigger('change');
                    $('#image_preview').attr('src', response.image);
                }
            });
        }

        // --------------------------- edit data ---------------------------
            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    
                    $('#submit-form-data').buttonLoader('start');

                    $.ajax({
                        url    : '<?php echo base_url();?>admin/content/banner_popup/edit_data',
                        method : 'post',
                        data   : new FormData(this),
                        dataType : 'json',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            $('#submit-form-data').buttonLoader('stop');
                            
                            if(response.status == 1) {
                                load_data();
                                notif_success(response.message);
                            } 
                            else {
                                notif_error(response.message);
                            }
                        }
                    })
                }
            });
        // --------------------------- end edit data ---------------------------
    });
</script>