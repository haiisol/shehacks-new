<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form method="post" id="form-data" enctype="multipart/form-data" role="form" class="mb-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Host</label>
                                <input type="text" name="host" id="host" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">SMTP Auth</label>
                                <input type="text" name="smtpauth" id="smtpauth" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email Akun</label>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Password Email</label>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">SMTP Secure</label>
                                <input type="text" name="smtpsecure" id="smtpsecure" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Port</label>
                                <input type="text" name="port" id="port" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Set From Email</label>
                                <input type="text" name="setfrom" id="setfrom" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email Subject</label>
                                <input type="text" name="email_subject" id="email_subject" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 mb-2 <?php echo $access_edit; ?>">
                            <input type="hidden" name="id" id="id">
                            <button type="submit" id="submit-form-data" class="btn btn-primary"><span class="icon feather icon-check"></span>Simpan</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-4 mb-5">
                    <div class="col-md-4">
                        <form method="post" id="form-testing-email" enctype="multipart/form-data" role="form" class="">
                            <div class="form-group">
                                <label class="control-label">Alamat Email Test</label>
                                <input type="text" name="email_test" id="email_test" class="form-control">
                            </div>
                            <button type="submit" id="submit-form-testing-email" class="btn btn-block btn-success px-4">Test Email</button>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label">Response Testing</label>
                            <textarea name="text_response" id="text_response" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.getScript('<?php echo base_url();?>assets/backoffice/js/custome.js');

        load_data();

        var validate_form = $('#form-data').validate({
            rules: {
                host: {
                    required: true
                },
                smtpauth: {
                    required: true
                },
                smtpsecure: {
                    required: true
                },
                email_akun: {
                    required: true
                },
                password: {
                    required: true
                },
                port: {
                    required: true
                },
                setfrom: {
                    required: true
                },
                email_subject: {
                    required: true
                }
            },
            messages: {
                host: {
                    required: 'Host tidak boleh kosong.'
                },
                smtpauth: {
                    required: 'Smtp Auth tidak boleh kosong.'
                },
                smtpsecure: {
                    required: 'Smtp secure tidak boleh kosong.'
                },
                email_akun: {
                    required: 'Email Akun tidak boleh kosong.'
                },
                password: {
                    required: 'Password tidak boleh kosong.'
                },
                port: {
                    required: 'Port tidak boleh kosong.'
                },
                setfrom: {
                    required: 'Set From tidak boleh kosong.'
                },
                email_subject: {
                    required: 'Email Subject tidak boleh kosong.'
                }
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


        function load_data() {
            $.ajax({
                url   : '<?php echo base_url();?>admin/setting/email_akun/get_data',
                success  : function(response) {
                    $('#id').val(response.id);
                    $('#host').val(response.host);
                    $('#smtpauth').val(response.smtpauth);
                    $('#email').val(response.email);
                    $('#password').val(response.password);
                    $('#smtpsecure').val(response.smtpsecure);
                    $('#port').val(response.port);
                    $('#setfrom').val(response.setfrom);
                    $('#email_subject').val(response.email_subject);
                }
            });
        }

        // --------------------------- edit data ---------------------------
            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {

                    $('#submit-form-data').buttonLoader('start');

                    $.ajax({
                        url    : '<?php echo base_url();?>admin/setting/email_akun/edit_data',
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


        // --------------------------- testing ---------------------------
            var validate_form_testing = $('#form-testing-email').validate({
                rules: {
                    email_test: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email_test: {
                        required: 'Email Test tidak boleh kosong.',
                        email: 'Format email tidak benar'
                    }
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

            $('#form-testing-email').submit(function(e) {
                e.preventDefault();

                if (validate_form_testing.valid()) {
                    
                    document.getElementById('text_response').value = '';
                    
                    $('#submit-form-testing-email').buttonLoader('start');

                    $.ajax({
                        method : 'POST',
                        url    : '<?php echo base_url();?>admin/setting/email_akun/testing_kirim',
                        data   : new FormData(this),
                        dataType: 'JSON',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            $('#submit-form-testing-email').buttonLoader('stop');
                            
                            if(response.status == 1) {
                                notif_success(response.message);
                                document.getElementById('text_response').value = response.info;
                            } else {
                                notif_error(response.message);
                                document.getElementById('text_response').value = response.info;
                            }
                        }
                    })
                }
            });
        // --------------------------- end testing ---------------------------

    });
</script>