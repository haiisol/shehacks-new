<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <form method="post" id="form-data" enctype="multipart/form-data" role="form">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="param" id="param">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Heading</label>
                                <input type="text" name="heading" id="heading" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Sub heading</label>
                                <input type="text" name="subheading" id="subheading" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Content</label>
                                <textarea name="content" id="content" rows="5" placeholder="" class="form-control"></textarea>
                                <span class="feedback-content"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Image Desktop</label>
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
                                <label>Button Text</label>
                                <input type="text" name="button_text" id="button_text" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Button Url</label>
                                <input type="text" name="button_url" id="button_url" class="form-control">
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
    $.getScript('<?php echo base_url();?>assets/backoffice/js/custome.js');

    $(document).ready(function() {


        var elContent = $('#content');
        $('#content').summernote({
            height: 300,
            codeviewFilter: true,
            dialogsInBody: true,
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['size', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']], 
                ['color', ['color']],
                ['insert', ['link']],
            ],
            callbacks: {
                onChange: function (contents, $editable) {
                    elContent.val(elContent.summernote('isEmpty') ? "" : contents);
                },
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    bufferText = bufferText.replace(/\r?\n/g, '<br>');
                    document.execCommand('insertHtml', false, bufferText);
                }
            }
        });


        $('#form-data').each(function () {
            if ($(this).data('validator'))
                $(this).data('validator').settings.ignore = '.note-editor *';
        });
        
        var $param_input = $('#param');
        var validate_form = $('#form-data').validate({
            rules: {
                heading: {
                    required: true
                },
                subheading: {
                    required: true
                },
                content: {
                    required: true
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
                image_2: {
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
                    required: 'Heading tidak boleh kosong.'
                },
                subheading: {
                    required: 'Heading tidak boleh kosong.'
                },
                content: {
                    required: 'Content tidak boleh kosong.'
                },
                image: {
                    required: 'Image harus diisi.',
                    extension: 'Unggah file dengan format .PNG/.JPG/.JPEG',
                    filesize: 'File maksimal 1 Mbps.'
                },
                image_2: {
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
                if (element.is('#content')) {
                    error.insertAfter(element.siblings('.note-editor'));
                } else {
                    error.appendTo(element.parent());
                }
            }
        });

        load_data();

        function load_data() {
            $('#param').val('edit');

            $.ajax({
                url : '<?php echo base_url();?>admin/content/home/intro/get_data',
                success : function(response) {
                    $('#id').val(response.id);
                    $('#heading').val(response.heading);
                    $('#subheading').val(response.subheading);
                    $('#button_text').val(response.button_text);
                    $('#button_url').val(response.button_url);
                    $('#content').summernote('code', response.content);
                    $('#image_preview').attr('src', response.image);
                    $('#image_preview_2').attr('src', response.image_2);
                }
            });
        }

        // --------------------------- edit data ---------------------------
            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    
                    $('#submit-form-data').buttonLoader('start');

                    $.ajax({
                        method : 'POST',
                        url    : '<?php echo base_url();?>admin/content/home/intro/edit_data',
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