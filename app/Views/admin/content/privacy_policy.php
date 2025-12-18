<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <form method="post" id="form-data" enctype="multipart/form-data" role="form">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Content</label>
                                <textarea name="content" id="content" rows="5" placeholder="" class="form-control" required></textarea>
                                <span class="feedback-content"></span>
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

        // summernote
        var elContent = $('#content');
        $('#content').summernote({
            height: 450,
            codeviewFilter: true,
            dialogsInBody: true,
            toolbar: [
                ['font', ['bold', 'underline', 'strikethrough']],
                ['size', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']], 
                ['color', ['color']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']],
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

        var validate_form = $('#form-data').validate({
            ignore: '.note-editor *',
            rules: {
                content: {
                    required: true
                },
            },
            messages: {
                content: {
                    required: 'Content tidak boleh kosong.'
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
            $.ajax({
                url : '<?php echo base_url();?>admin/content/privacy_policy/get_data',
                success  : function(response) {
                    $('#id').val(response.query.id);
                    $('#heading').val(response.query.heading);
                    $('#content').summernote('code', response.query.content);
                }
            });
        }

        // --------------------------- edit data ---------------------------
            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    
                    $('#submit-form-data').buttonLoader('start');

                    $.ajax({
                        url    : '<?php echo base_url();?>admin/content/privacy_policy/edit_data',
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