<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form method="post" id="form-tambah-data" enctype="multipart/form-data" class="cmxform">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Kategori Berita<span class="text-danger">*</span></label><br>
                                <select name="id_kategori" id="id_kategori" class="form-control form-control-sm select2-custome-search" style="width: 100%;">
                                    <?php $get_kat = $this->db->query("SELECT * FROM tb_blog_kategori")->result_array(); ?>
                                     <option value="">Pilih Kategori</option>
                                    <?php foreach ($get_kat as $kan) { ?>
                                        <option value="<?php echo $kan['id_blog_kategori']; ?>"><?php echo $kan['nama']; ?></option>
                                    <?php } ?>
                                </select>
                                <div class="kanal_feed"></div>
                            </div>
                            <div class="form-group">
                                <label>Judul Berita <span class="text-danger">*</span></label>
                                <input type="text" name="judul" id="judul" placeholder="" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Gambar Berita</label>
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail">
                                                <img id="gambar_preview" src="<?php echo base_url();?>assets/backoffice/images/no-image.png" alt="" style="max-width: 100px; max-height: 100px;">
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 100px; max-height: 100px;"></div>
                                            <div id="gambar_feed">
                                                <span class="btn btn-md btn-primary btn-file">
                                                    <span class="fileupload-new">Pilih Gambar</span>
                                                    <span class="fileupload-exists">Ganti Gambar</span>
                                                    <input type="file" name="gambar" id="gambar" accept="image/*">
                                                </span> 
                                                <a href="#" class="btn btn-md btn-danger fileupload-exists" data-dismiss="fileupload">Hapus gambar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sumber gambar</label>
                                        <textarea rows="2" name="gambar_sumber" id="gambar_sumber" class="form-control"></textarea> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Keterangan Gambar</label>
                                        <textarea rows="2" name="gambar_keterangan" id="gambar_keterangan" class="form-control"></textarea> 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Text Berita <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" id="deskripsi-sum" rows="5" placeholder="" class="form-control"></textarea>
                                <div class="feedback-deskripsi"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <label>Sisipkan Tags</label>
                            <input name="tags" id="tags" class="tags form-control" value="" />
                        </div>
                        <div class="form-group">
                            <label>Penulis</label>
                            <input type="text" name="penulis" id="penulis" value="<?php echo $admin['nama_admin']; ?>" class="form-control" readonly="">
                        </div>
                        <div class="form-group">
                            <label>Tanggal dibuat</label>
                            <input type="text" name="create"  id="create" value="<?php echo date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s'); ?>" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="row mt-5 mb-3">
                        <div class="col-md-12">
                            <div class="block-bottom d-flex justify-content-between">
                                <input type="hidden" name="param" id="param">
                                <input type="hidden" name="id_data" id="id_data">
                                <a href="<?php echo base_url();?>admin/blog/blog" class="btn btn-light"><ion-icon name="arrow-back-circle-sharp"></ion-icon> Kembali</a>
                                <button type="submit" id="btn-simpan" class="btn btn-primary"><ion-icon name="checkmark-done-circle-sharp"></ion-icon> Selesai</button>
                            </div>
                        </div>
                    </div>                    
                </form>
                
            </div>
        </div>
    </div>
</div>

<?php include APPPATH.'views/admin/component/include_source.php'; ?>

<script>
    $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

    $(document).ready(function() {

        var param_newsletter    = '<?php echo $param_newsletter; ?>';
        var param_id            = '<?php echo $param_id; ?>';

        newsletter(param_newsletter, param_id);

        $('.tags').tagsInput({
            'width':'100%',
            'interactive':true,
        });

        // summernote
        var summernoteElementdeskripsi = $('#deskripsi-sum');
        $('#deskripsi-sum').summernote({
            height: 220,
            codeviewFilter: true,
            dialogsInBody: true,
            toolbar: [
            ['font', ['bold', 'underline', 'strikethrough']],
            ['para', ['ul', 'ol', 'paragraph']], 
            ['insert', ['link','picture']],
            ['view', ['codeview']]
            ],
            callbacks: {
                onChange: function (contents, $editable) {
                    summernoteElementdeskripsi.val(summernoteElementdeskripsi.summernote('isEmpty') ? "" : contents);
                },
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    bufferText = bufferText.replace(/\r?\n/g, '<br>');
                    document.execCommand('insertHtml', false, bufferText);
                }
            }
        });

        // $.validator.addMethod("cek_value_judul", function(value, element) {
        //     $.ajax({
        //         method  : "post",
        //         url     : '<?php echo base_url();?>admin/blog/blog/cek_value',
        //         data    : { value:value },
        //         success: function(response) {
        //             if(response.status == 1) {
        //                 result = true;
        //             } else {
        //                 result = false;
        //             }
        //         },
        //         async: false
        //     });
        //     return result;
        // });


        $('#form-tambah-data').each(function () {
            if ($(this).data('validator'))
                $(this).data('validator').settings.ignore = ".note-editor *";
        });

        var form_submit = $("#form-tambah-data").validate({
            ignore: ".note-editor *",
            rules: {
                judul: {
                    required: true,
                    // cek_value_judul: {
                    //     depends: function(element) {
                    //         if ($('#param').val() == "edit") {
                    //             return false;
                    //         } else {
                    //             return true;
                    //         }
                    //     }
                    // }
                },
                id_kategori: {
                    required: true
                },
                deskripsi: {
                    required: true
                },
                gambar: {
                    required: {
                        depends: function(element) {
                            if (param_newsletter == 'edit') {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    },
                    extension: 'webp|jpg|jpeg|png',
                    filesize: 1000000
                },
            },
            messages: {
                judul: {
                    required: "Judul harus diisi",
                    // cek_value_judul: "Judul sudah pernah digunakan"
                },
                id_kategori: {
                    required: "Katgegori harus dipilih"
                },
                deskripsi: {
                    required: "Text Berita harus diisi"
                },
                gambar: {
                    required: 'Gambar harus diisi.',
                    extension: 'Unggah file dengan format .WEBP/.PNG/.JPG/.JPEG',
                    filesize: 'File maksimal 1 Mbps.'
                },
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
                if(element.is('#gambar')) {
                    error.insertAfter('#gambar_feed').addClass('has-error');
                } else if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="form-group"]');
                    if(controls.find(':checkbox,:radio').length > 1) 
                        controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        error.addClass('has-error');
                } else if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent('.input-group'));
                } else if(element.is('#deskripsi-sum')) {
                    error.insertAfter('.feedback-deskripsi').addClass('has-error');
                } else if(element.is('#id_kategori')) {
                    error.insertAfter('.kanal_feed').addClass('has-error');
                } else if(element.is('#param_newsletter')) {
                    error.insertAfter('.param_newsletter_feed').addClass('has-error');
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // --------------------------- add & edit data --------------------------- 
            function newsletter(param_newsletter, param_id){

                if (param_newsletter == 'tambah') {

                    $("#form-tambah-data")[0].reset();
                    save_method = 'add';
                    $('#param').val('add');
                    $('#tags').importTags('');
                    $('#gambar_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
 
                } else {

                    $("#form-tambah-data")[0].reset();
                    save_method = 'edit';
                    $('#param').val('edit');

                    $.ajax({
                        method   : 'post',
                        url      : "<?php echo base_url(); ?>admin/blog/blog/get_data",
                        dataType : "json",
                        data     : { id:param_id },
                        success: function(response) {
                         
                            $('#id_data').val(response.data.id_blog);
                            $('#create').val(response.data.date_create);
                            $('#penulis').val(response.penulis);
                            $('#judul').val(response.data.judul);
                            $('#gambar_keterangan').val(response.data.gambar_keterangan);
                            $('#gambar_sumber').val(response.data.gambar_sumber);
                            $("#id_kategori").val(response.data.id_blog_kategori).trigger('change');
                            $('#tags').importTags(response.array_tags);
                            $('#deskripsi-sum').summernote('code', response.data.deskripsi);   

                            if (response.data.gambar == '') {
                                $('#gambar_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                            } else {
                                $('#gambar_preview').attr('src', '<?php echo base_url();?>file_media/file-blog/'+response.data.gambar+'');
                            }  

                        }
                    });
                    return false;
                }

            }

            $('#form-tambah-data').submit(function(e) {
                e.preventDefault();
                if (form_submit.valid()) {
                    $('#btn-simpan').buttonLoader('start');
                    if(save_method == 'add') {
                        url = "<?php echo base_url(); ?>admin/blog/blog/add_data";
                    } else {
                        url = "<?php echo base_url(); ?>admin/blog/blog/edit_data";
                    }

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
                                top.location.href="<?php echo base_url();?>admin/blog/blog";
                            } 
                            else if(response.status == 2) {
                                notif_error('Gagal menambah data.');
                            } 
                            else if(response.status == 3) {
                                top.location.href="<?php echo base_url();?>admin/blog/blog";
                            } 
                            else if(response.status == 4) {
                                notif_error('Gagal edit data.');
                            }
                        }
                    })
                }
            });
        // --------------------------- end add & edit data ---------------------------

    });
    

</script>