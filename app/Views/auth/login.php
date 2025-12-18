
<?php 
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$ref = filter_var($ref, FILTER_VALIDATE_URL) ? $ref : base_url();
?>

<section class="auth-section section section-md">
    <div class="container">
        
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 col-sm-10 m-auto">
                <div class="section-title center mb-0">
                    <h2 class="title section-heading mb-2">Masuk</h2>
                    <p class="description section-description-md">Masukan data akun Anda</p>
                </div>

                <div class="inner">
                    <div id="feedback-login"></div>

                    <form method="post" id="form-login" class="form-style">
                        <input type="hidden" name="uri_string" value="<?= html_escape($ref); ?>" class="form-control">
                        
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <div class="group-inner s-icon">
                                <span class="group-inner-icon"><i class="lni lni-envelope"></i></span>
                                <input type="text" name="email" id="email-login" placeholder="Masukan email Anda" class="form-control" autocomplete="off" autocorrect="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-md-flex align-items-center justify-content-between">
                                <label for="email" class="control-label">Password</label>
                            </div>
                            <div class="group-inner se-icon">
                                <span class="group-inner-icon"><i class="lni lni-lock-alt"></i></span>
                                <input type="password" name="password" id="password-login" placeholder="Masukan password Anda" class="form-control">
                                <span toggle="#password-login" class="fa fa-eye-slash show-password"></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?php echo base_url();?>lupa-password" class="hover-1 fw-400 control-label">Lupa Password?</a>
                        </div>

                        <div class="form-actions">
                            <button type="submit" id="submit-form-login" class="btn w-100 btn-hover-arrow-right"><span>Masuk</span></button>

                            <div class="text-center mt-4">
                                <p class="section-description-md">
                                    <span>Belum memiliki akun?</span> <a href="<?php echo base_url(); ?>register" class="hover-1 text-accent fw-500 cta-btn-trigger" data="Daftar Disini">Daftar Disini</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>


<script>
    $(document).ready(function() {
        
        // --------------------------- login ---------------------------
            var validate_form = $('#form-login').validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    email: {
                        required: 'Masukan email Anda.',
                        email: 'Masukan format email Anda dengan benar.',
                    },
                    password: {
                        required: 'Masukan password Anda.'
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
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent('.input-group'));
                    } 
                    else {
                        error.appendTo(element.parent());
                    }
                }
            });

            $('#form-login').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    $('#submit-form-login').buttonLoader('start');

                    $.ajax({
                        method   : 'post',
                        url      : '<?php echo base_url(); ?>auth/login/post_login',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#submit-form-login').buttonLoader('stop');

                            if (response.status == 1) {
                                $('#form-login')[0].reset();
                                trigger_cta_event('Login Form');
                                top.location.href = '<?php echo base_url();?>login-verify';
                            } 
                            else {
                                $('#feedback-login').alertNotification('danger', response.message);
                            }
                        }
                    })
                }
            });


            function trigger_cta_event(data)
            {
                $.ajax({
                    method   : 'POST',
                    url      : '<?php echo base_url();?>analytic/post_cta_btn',
                    data     : { data:data, url:getUrlVisit() },
                    dataType : 'json',
                    success:function(response) {
                        
                    }
                });
                return false;
            }
        // --------------------------- end login ---------------------------
        
    });
</script>