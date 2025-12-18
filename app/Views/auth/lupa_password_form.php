<section class="auth-section section section-md">
    <div class="container">
        
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 col-sm-10 m-auto">
                <div class="section-title center mb-0">
                    <h2 class="title section-heading mb-2">Password Baru</h2>
                    <p class="description section-description-md">Setel ulang kata sandi Anda untuk pemulihan dan masuk ke akun Anda</p>
                </div>

                <div class="inner">
                    <div id="feedback-change-password" class="mb-2"></div>

                    <form method="post" id="form-change-password" class="form-style">
                        <div class="form-group">
                            <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Password baru" autocomplete="off" autocorrect="off">
                            <span toggle="#password_baru" class="fa fa-eye show-password"></span>
                        </div>

                        <div class="form-group">
                            <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Konfirmasi password baru" autocomplete="off" autocorrect="off">
                            <span toggle="#konfirmasi_password" class="fa fa-eye show-password"></span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" id="submit-change-password" class="btn btn-accent w-100"><span>Simpan</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

	</div>
</section>


<script>
    $(document).ready(function () {

        $.validator.addMethod('cek_password', function(value, element) {

            var re = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

            return re.test(value);

        });

        var validate = $('#form-change-password').validate({
            rules: {
                password_baru: {
                    required: true,
                    cek_password: true
                },
                konfirmasi_password: {
                    required: true,
                    equalTo: '#password_baru'
                }
            },
            messages: {
                password_baru: {
                    required: 'Password baru harus diisi.',
                    cek_password: 'Password harus memiliki minimal 8 karakter yang terdiri dari kombinasi huruf besar, huruf kecil, symbol dan angka.'
                },
                konfirmasi_password: {
                    required: 'Konfirmasi password harus diisi.',
                    equalTo: 'Konfirmasi password tidak cocok.'
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
                error.insertAfter(element);
            }
        });

        $('#form-change-password').submit(function(e) {
            e.preventDefault();

            if (validate.valid()) {

                $('#submit-change-password').buttonLoader('start');

                var password = $('input[name$="password_baru"]').val();

                $.ajax({
                    method   : 'post',
                    url      : '<?php echo base_url();?>auth/lupa_password/post_ganti_password',
                    data     : { password:password },
                    dataType : 'json',
                    success:function(response) {
                        $('#submit-change-password').buttonLoader('stop');
                        
                        if(response.status == 1) {

                            top.location.href='<?php echo base_url();?>login';
                        } 
                        else if(response.status == 2) {
                            $('#feedback-change-password').alertNotification('danger', response.message);

                            window.setTimeout(function () {
                                top.location.href='<?php echo base_url();?>';
                            }, 6500);
                        } 
                        else {
                            $('#feedback-change-password').alertNotification('danger', response.message);
                        }
                    }
                })
            }
        });
    });
</script>