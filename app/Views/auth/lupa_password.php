<?php 
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$ref = filter_var($ref, FILTER_VALIDATE_URL) ? $ref : base_url();
?>

<section class="auth-section section section-md">
    <div class="container">
        
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 col-sm-10 m-auto">
                <div class="section-title center mb-0">
                    <h2 class="title section-heading mb-2">Lupa Password?</h2>
                    <p class="description section-description-md">Masukkan alamat email akun Anda</p>
                </div>

                <div class="inner">
                    <div id="feedback-reset" class="mb-2"></div>

                    <form method="post" id="form-reset" class="form-style">
                        <input type="hidden" name="uri_string" value="<?= html_escape($ref); ?>" class="form-control">
                        
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <div class="group-inner s-icon">
                                <span class="group-inner-icon"><i class="lni lni-envelope"></i></span>
                                <input type="text" name="email" id="email-reset" placeholder="Masukan email Anda" class="form-control">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" id="submit-form-reset" class="btn btn-accent btn-hover-icon-left w-100"><span class="icon lni lni-envelope"></span><span>Kirim Email</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    $(document).ready(function() {

        var validate_form = $('#form-reset').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: 'Alamat email harus diisi.',
                    email: 'Masukkan alamat email dengan benar.'
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

        $('#form-reset').submit(function(e) {
            e.preventDefault();

            if (validate_form.valid()) {
                $('#submit-form-reset').buttonLoader('start');

                var email = $('input[name$="email"]').val();

                $.ajax({
                    method   : 'post',
                    url      : '<?php echo base_url(); ?>auth/lupa_password/post_reset_password',
                    data     : new FormData(this),
                    dataType : 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#submit-form-reset').buttonLoader('stop');

                        if (response.status == 1) {
                            $('#form-reset')[0].reset();

                            // set session
                            sessionStorage.setItem('sess_email_reset', email);

                            // redirect
                            top.location.href='<?php echo base_url();?>verifikasi-lupa-password';

                            $('#feedback-reset').alertNotification('success', response.message);
                        } 
                        else {
                            $('#feedback-reset').alertNotification('danger', response.message);
                        }
                    }
                })
            }
        });
    });
</script>