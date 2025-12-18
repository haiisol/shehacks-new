<section class="auth-section section section-md">
    <div class="container">

        <div id="feedback-register"></div>

        <!-- fieldset step -->
        <div class="wrap-step animated fadeIn">
            <div class="auth-form">
                <div class="section-title">
                    <h2 class="title section-heading-sm">Create An Account</h2>
                    <p class="section-description-md"><span>Already have an account?</span> <a href="<?php echo base_url();?>login" class="hover-1 text-accent fw-500">Login</a></p>
                </div>

                <form method="post" id="form-personal" class="form-style">
                    <div class="form-group">
                        <label for="nama" class="control-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" id="nama" placeholder="Masukan nama lengkap Anda" class="form-control val-capitalize">
                    </div>

                    <div class="form-group">
                        <label for="telp" class="control-label">Nomer Handphone <span class="required">*</span></label>
                        <div class="input-group input-telp">
                            <div class="input-group-addon">
                                <img src="<?php echo base_url();?>assets/front/img/ind.png" class="img-fluid flag-ind lazyload"> <span>+62</span>
                            </div>
                            <input type="tel" name="telp" id="telp" class="form-control val-telp" placeholder="82140xxxxxx" autocomplete="off" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="control-label">Email <span class="required">*</span></label>
                        <input type="text" name="email" id="email" placeholder="Masukan email Anda" class="form-control val-lowercase" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Password <span class="required">*</span></label>
                        <div class="group-inner e-icon">
                            <input type="password" name="password" id="password" placeholder="Masukan password Anda" class="form-control" autocomplete="off">
                            <span toggle="#password" class="fa fa-eye-slash show-password"></span>
                        </div>
                    </div>

                    <div class="form-check xform-check-inline custome-checkbox">
                        <input class="form-check-input" type="checkbox" name="agreement_check" id="agreement_check">
                        <label class="form-check-label section-description-sm" for="agreement_check">Saya setuju bahwa data pribadi saya akan dikumpulkan, disimpan, dan diproses untuk keperluan pelaksanaan program SheHacks sesuai dengan kebijakan privasi yang berlaku.</label>
                    </div>

                    <div class="form-actions d-flex justify-content-between">
                        <button type="submit" id="submit-form-personal" class="btn"><span>DAFTAR SEKARANG</span></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<div class="modal modal-style fade" id="modal-success-registration" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="ModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="inner-modal py-5 text-center">
                <div class="icon mb-4">
                        <svg width="158" height="158" viewBox="0 0 158 158" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M106.894 21.8374C81.5953 11.7081 50.8933 17.4841 33.0538 37.1524L33.0523 37.154C24.2695 46.8323 18.7609 59.0376 17.3111 72.032C15.8613 85.0264 18.5441 98.1474 24.9775 109.527C31.411 120.906 41.2671 129.964 53.1424 135.41C65.0178 140.856 78.307 142.413 91.1179 139.859L91.1201 139.859C116.896 134.728 136.107 111.399 141.235 85.734C142.152 81.1459 146.611 78.1704 151.194 79.088C155.778 80.0056 158.751 84.4689 157.834 89.057C151.677 119.872 128.344 149.725 94.4218 156.478C78.1177 159.727 61.2051 157.745 46.0917 150.814C30.9776 143.883 18.4335 132.355 10.2455 117.872C2.05744 103.389 -1.35706 86.6897 0.488163 70.1513C2.3333 53.6137 9.34364 38.0803 20.521 25.7627M20.5225 25.7611C43.6251 0.291737 82.0543 -6.35739 113.18 6.10521C117.52 7.84293 119.631 12.7734 117.895 17.1177C116.159 21.462 111.234 23.5751 106.894 21.8374" fill="#EC008C"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M155.273 24.2625C158.713 27.4583 158.927 32.8557 155.752 36.3179L87.9524 110.255C86.388 111.961 84.2004 112.952 81.8944 112.998C79.5884 113.045 77.3632 112.143 75.7323 110.501L47.4823 82.0638C44.1726 78.7321 44.1726 73.3304 47.4823 69.9988C50.792 66.6671 56.158 66.6671 59.4677 69.9988L81.4805 92.1576L143.298 24.7447C146.472 21.2826 151.834 21.0667 155.273 24.2625Z" fill="#EC008C"/>
                        </svg>
                    </svg>
                </div>

                <h4 class="section-heading-sm text-second2">Sukses Mendaftar</h4>
                <p class="section-description-md">Terima kasih telah melengkapi informasi pendaftaran. Silahkan cek email terdaftar di <span class="fw-600">Kotak Masuk / Folder Spam</span> untuk melakukan verifikasi akun Anda.</p>
                <!-- <a href="<?php echo base_url();?>" class="btn btn-accent mt-3">Home</a> -->
            </div>
        </div>
    </div>
</div>

<script defer src="<?php echo base_url();?>assets/front/vendor/select2/js/select2.min.js"></script>
<script defer src="<?php echo base_url();?>assets/front/vendor/input-file/input-file.js"></script>
<!-- <script defer src="<?php echo base_url();?>assets/front/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script> -->
<script defer src="<?php echo base_url();?>assets/front/vendor/input-mask/input-mask.min.js"></script>

<script src="<?php echo base_url();?>assets/front/js/function.js"></script>

<script>
    $(document).ready(function() {

        // --------------------------- register voting ---------------------------
            $.validator.addMethod('cek_password', function(value, element) {

                var re = /^(?=.*\d)(?=.*[@#$%^&*?])(?=.*[^!])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
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

            var validate_form_personal = $('#form-personal').validate({
                rules: {
                    nama: {
                        required: true
                    },
                    telp: {
                        required: true,
                        maxlength: 12
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        cek_password: true
                    },
                    agreement_check: {
                        required: true,
                    }
                },
                messages: {
                    nama: {
                        required: 'Nama lengkap harus diisi.'
                    },
                    telp: {
                        required: 'Nomor telepon harus diisi.',
                        maxlength: 'Masukkan tidak lebih dari 12 karakter.',
                    },
                    email: {
                        required: 'Alamat email harus diisi.',
                        email: 'Masukkan alamat email dengan benar.',
                    },
                    password: {
                        required: 'Password harus diisi.',
                        cek_password: 'Password harus memiliki minimal 8 karakter yang terdiri dari kombinasi huruf besar, huruf kecil, angka dan symbol (kecuali !).'
                    },
                    agreement_check: {
                        required: ''
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
                    else if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="form-group"]');
                        if(controls.find(':checkbox,:radio').length > 1) 
                            controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        error.addClass('has-error');
                    }
                    else {
                        error.appendTo(element.parent());
                    }
                }
            });

            $('#form-personal').submit(function(e) {
                e.preventDefault();

                if (validate_form_personal.valid()) {
                    $('#submit-form-personal').buttonLoader('start');

                    $.ajax({
                        method   : 'post',
                        url      : '<?php echo base_url(); ?>auth/register_voting/post_register_voting',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#submit-form-personal').buttonLoader('stop');

                            if (response.status == 1) {
                                $('#form-personal')[0].reset();
                                $('#modal-success-registration').modal('show');

                                setTimeout(function() {
                                    top.location.href = '<?php echo base_url();?>login-verify';
                                }, 1500);
                            } 
                            else {
                                $('#feedback-register').alertNotification('danger', response.message);
                            }
                        }
                    })
                }
            });
        // --------------------------- end register voting ---------------------------
    });
</script>