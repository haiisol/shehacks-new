<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"><?php echo $title; ?></h6>

                <form method="post" id="form-tambah-data" enctype="multipart/form-data" class="cmxform">
                    <div class="row">
                        <div class="col-md-6 pr-md-4">
                            <div class="form-group mb-2">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" placeholder="" class="form-control">
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" placeholder="" class="form-control" disabled>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label">No. Telephone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" placeholder="" class="form-control">
                            </div>
                            <div class="form-group fg-password">
                                <label class="form-label">Password</label>
                                <input type="text" name="password" id="password" placeholder="******" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6 pl-md-4">

                            <div class="form-group mb-2">
                                <label class="form-label">Unggah photo</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail">
                                        <img id="photo_preview" src="<?php echo base_url();?>assets/backoffice/images/no-image-user.png" alt="" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 100px; max-height: 100px;"></div>
                                    <div id="photo_feed">
                                        <span class="btn btn-xs btn-info btn-file">
                                            <span class="fileupload-new">Pilih Foto</span>
                                            <span class="fileupload-exists">Ganti</span>
                                            <input type="file" name="photo" id="photo" accept="image/*">
                                            <input type="hidden" name="photo_old" id="photo_old" value="">
                                        </span> 
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4 mb-4">
                            <div class="block-bottom d-flex">
                                <input type="hidden" name="param" id="param">
                                <input type="hidden" name="id_admin" id="id_admin">
                               
                                <button type="submit" id="btn-simpan" class="btn btn-primary"><i class="link-icon" data-feather="check"></i> Simpan perubahan</button>
                            </div>
                        </div>
                    </div>                    
                </form>
                
            </div>
        </div>
    </div>
</div>

<script>
    $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

    $(document).ready(function() {

        load_data();

        function load_data() {
            $.ajax({
                type  : 'GET',
                url   : '<?php echo base_url();?>admin/edit_profile/get_data',
                async : true,
                dataType : 'json',
                success  : function(response) {
            
                    $('#id_admin').val(response.data.id_admin);
                    $('#nama').val(response.data.nama_admin);
                    $('#email').val(response.data.email_admin);
                    $('#phone').val(response.data.phone_admin);
                    $("#id_role").val(response.data.id_role).trigger('change');
                    $('#photo_preview').attr('src', response.link_photo);
                }
            });
        }

        var $param_input = $('#param');
        $("#form-tambah-data").validate({
            rules: {
                id_role: {
                    required: true
                },
                nama: {
                    required: true
                },
                phone: {
                    required: true,
                    number: true,
                    minlength:10,
                    maxlength:12
                },
                password: {
                    minlength: 6
                }
            },
            messages: {
                nama: {
                    required: "Nama harus diisi."
                },
                phone: {
                    required: "No Telp harus diisi.",
                    number: "Silahkan masukan no telp dengan benar",
                    minlength: "Format telephon minimal 10 karakter",
                    maxlength: "Format telephon max 12 karakter"
                },
                password: {
                    minlength: "Password minimal 6 karakter"
                }
            },
            errorElement: "em",
            errorClass: "has-error",
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
                } else if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="form-group"]');
                    if(controls.find(':checkbox,:radio').length > 1) 
                        controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        error.addClass('has-error');
                } else if(element.is('#id_role')) {
                    error.insertAfter('.role_feed').addClass('has-error');
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $('#form-tambah-data').submit(function(e) {
                e.preventDefault();
                if (jQuery("#form-tambah-data").valid()) {
                    $('#btn-simpan').buttonLoader('start');
                    
                    url = "<?php echo base_url(); ?>admin/edit_profile/edit_data";

                    $.ajax({
                        url      : url,
                        method   : 'post',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            $('#btn-simpan').buttonLoader('stop');
                            if(response.status == 1) {
                                load_data();
                                $("#form-tambah-data")[0].reset();
                                notif_success('Data berhasil disimpan.');
                            } 
                            else if (response.status == 2) {
                                notif_success('Data berhasil disimpan, silahkan login ulang.');
                                top.location.href='<?php echo base_url();?>auth/login/logout';
                            }
                            else {
                                notif_error('Gagal menyimpan data, silahkan coba lagi.');
                            }
                        }
                    })
                }
            });

    });
</script>