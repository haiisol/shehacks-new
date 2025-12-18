<?php   
    $web   = $this->main_model->get_admin_web();
?>
<div class="row g-0 m-0 auth-section">
    <div class="col-xl-6 col-lg-12">
        <div class="login-cover-wrapper">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="<?php echo $this->main_model->url_image($web['logo'], 'image-logo'); ?>" alt="" class="logo-auth img-fluid d-xl-none d-block brand-img">

                        <h4>Sign In</h4>
                        <p>Sign In to your account</p>
                    </div>

                    <div class="feedback-login mb-2"></div>

                    <form method="post" id="form-login" class="form-body row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="email">Email</label>
                                </div>
                                <div class="form-control-group">
                                    <input type="email" name="email_admin" id="email_admin" class="form-control" autofocus autocomplete="off" placeholder="Enter your email address">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="form-control-wrap">
                                    <input type="password" name="password_admin" id="password_admin" class="form-control" autocomplete="off" placeholder="Enter your password">
                                    <div class="right-icon">
                                        <a href="javascript:void(0)" toggle="#password_admin" class="feather icon-eye show-password"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="form-group">
                                <div class="d-grid">
                                    <button type="submit" id="form-login-submit" class="btn btn-padd btn-primary">Sign In</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 col-lg-12">
        <div class="position-absolute top-0 h-100 d-xl-block d-none login-cover-img">
            <div class="text-white p-5 w-100"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        var validate = $('#form-login').validate({
            rules: {
                email_admin: {
                    required: true,
                    email: true
                },
                password_admin: {
                    required: true
                }
            },
            messages: {
                email_admin: {
                    required: 'Email harus diisi.',
                    email: 'Masukan email dengan benar.',
                },
                password_admin: {
                    required: 'Password harus diisi.'
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
                error.insertAfter(element);
            }
        });

        $('#form-login').submit(function(e) {
            e.preventDefault();

            if (validate.valid()) {

                $('#form-login-submit').buttonLoader('start');

                $.ajax({
                    method   : 'post',
                    url      : '<?php echo base_url();?>admin/auth/login/post_login',
                    data     : new FormData(this),
                    dataType : 'json',
                    contentType : false,
                    processData : false,
                    success:function(response) {
                        
                        $('#form-login-submit').buttonLoader('stop');
                        
                        if(response.status == 1) {
                            $('#form-login')[0].reset();
                            $('.feedback-login').alertNotification('success', response.message);

                            setTimeout(function() {
                                top.location.href = response.redirect;
                            }, 1500);

                        }
                        else {
                            $('.feedback-login').alertNotification('danger', response.message);
                        }
                    }
                })
            }
        });
    });
</script>