<div class="card card-style">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-5">
                <div class="section-title">
                    <h2 class="title">Change Password</h2>
                    <p class="section-description-md">Amankan akun Anda dengan kombinasi password yang baik</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-5 col-md-6 col-sm-8 m-lg-0 m-auto">

                <div id="feedback-password" class="mb-3"></div>

                <form method="post" id="form-password" class="form-style">
                    <div class="form-group">
                        <input type="password" name="password_lama" id="password_lama" class="form-control" placeholder="Password lama" autocomplete="off" autocorrect="off">
                        <span toggle="#password_lama" class="fa fa-eye show-password"></span>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Password baru" autocomplete="off" autocorrect="off">
                        <span toggle="#password_baru" class="fa fa-eye show-password"></span>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_konfirmasi" id="password_konfirmasi" class="form-control" placeholder="Konfirmasi password baru" autocomplete="off" autocorrect="off">
                        <span toggle="#password_konfirmasi" class="fa fa-eye show-password"></span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" id="submit-password" class="btn"><span>Perbarui Password</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function () {

		// --------------------------- change password --------------------------- 
            $.validator.addMethod('cek_password', function(value, element) {
                $.ajax({
                    method : 'POST',
                    url    : '<?php echo base_url();?>auth/login/cek_password_lama',
                    data   : { value:value },
                    dataType : 'json',
                    async  : false,
                    success: function(response) {
                        if(response.status == 1) {
                            result = true;
                        } else {
                            result = false;
                        }
                    },
                });
                return result;
            });

            $.validator.addMethod('cek_password_conf', function(value, element) {
                var re = /^(?=.*\d)(?=.*[@#$%^&*?~;+\(/):_|{}[-])(?=.*[^!])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
                var sr = /^[^!]*$/;

                if (re.test(value) == true) {
                    if (sr.test(value) == true) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            });

            var validate_form = $('#form-password').validate({
                rules: {
                    password_lama: {
                        required: true,
                        cek_password: true
                    },
                    password_baru: {
                        required: true,
                        cek_password_conf: true
                    },
                    password_konfirmasi: {
                        required: true,
                        equalTo: '#password_baru',
                        cek_password_conf: true
                    }
                },
                messages: {
                    password_lama: {
                        required: 'Password lama harus diisi.',
                        cek_password: 'Password lama tidak sesuai.'
                    },
                    password_baru: {
                        required: 'Password baru harus diisi.',
                        cek_password_conf: 'Password harus memiliki minimal 8 karakter yang terdiri dari kombinasi huruf besar, huruf kecil, angka dan symbol (kecuali !=<>"]).'
                    },
                    password_konfirmasi: {
                        required: 'Konfirmasi password harus diisi.',
                        equalTo: 'Konfirmasi password tidak cocok.',
                        cek_password_conf: 'Password harus memiliki minimal 8 karakter yang terdiri dari kombinasi huruf besar, huruf kecil, angka dan symbol (kecuali !=<>"]).'
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

            $('#form-password').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    $('#submit-password').buttonLoader('start');

                    $.ajax({
                        method  : 'POST',
                        url     : '<?php echo base_url();?>dashboard/dashboard/post_change_password',
                        data    : new FormData(this),
                        dataType: 'json',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            $('#submit-password').buttonLoader('stop');

                            if(response.status == 1) {
                                $('#form-password')[0].reset();
                                trigger_cta_event('Password Update Form');

                                $('#feedback-password').alertNotification('success', response.message);

                                window.setTimeout(function () {
                                    top.location.href='<?php echo base_url();?>logout';
                                }, 3000);
                            }
                            else {
                            	$('#feedback-password').alertNotification('danger', response.message);
                            }
                        }
                    })
                }
            });
        // --------------------------- end change password ---------------------------
    });
</script>