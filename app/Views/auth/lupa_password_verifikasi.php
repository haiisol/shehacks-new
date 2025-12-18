<style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<section class="auth-section section section-md">
    <div class="container">

        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 col-sm-10 m-auto">
                <div class="section-title center mb-0">
                    <h2 class="title section-heading mb-2">Verifikasi Email Anda</h2>
                    <p class="description section-description-md">Silahkan periksa <b>Email</b> Anda & masukan <b>4 digit kode yang sudah dikirimkan</b></p>
                </div>

                <div class="inner">
                    <div id="feedback-verifikasi-kode" class="mb-2"></div>

                    <form method="post" id="form-verifikasi-kode" class="form-style">
                        <div class="pin-code form-group">
                            <input type="number" name="kode_verifikasi1" pattern="[0-9]*" inputtype="numeric" maxlength="1" class="form-control" autocomplete="off" autofocus>
                            <input type="number" name="kode_verifikasi2" pattern="[0-9]*" inputtype="numeric" maxlength="1" class="form-control" autocomplete="off">
                            <input type="number" name="kode_verifikasi3" pattern="[0-9]*" inputtype="numeric" maxlength="1" class="form-control" autocomplete="off">
                            <input type="number" name="kode_verifikasi4" pattern="[0-9]*" inputtype="numeric" maxlength="1" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-actions">
                            <button type="submit" id="submit-verifikasi-kode" class="btn btn-accent w-100"><span>Verifikasi</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

	</div>
</section>


<script>
    $(document).ready(function () {

        var validate = $('#form-verifikasi-kode').validate({
            rules: {
                kode_verifikasi1: {
                    required: true
                },
                kode_verifikasi2: {
                    required: true
                },
                kode_verifikasi3: {
                    required: true
                },
                kode_verifikasi4: {
                    required: true
                },
            },
            messages: {
                kode_verifikasi1: {
                    required: ''
                },
                kode_verifikasi2: {
                    required: ''
                },
                kode_verifikasi3: {
                    required: ''
                },
                kode_verifikasi4: {
                    required: ''
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

        $('#form-verifikasi-kode').submit(function(e) {
            e.preventDefault();

            if (validate.valid()) {
                $('#submit-verifikasi-kode').buttonLoader('start');

                var k1 = $('input[name$="kode_verifikasi1"]').val();
                var k2 = $('input[name$="kode_verifikasi2"]').val();
                var k3 = $('input[name$="kode_verifikasi3"]').val();
                var k4 = $('input[name$="kode_verifikasi4"]').val();

                var sess_email      = sessionStorage.getItem('sess_email_reset');
                var kode_verifikasi = k1+k2+k3+k4;

                $.ajax({
                    method   : 'post',
                    url      : '<?php echo base_url();?>auth/lupa_password/post_kode_verifikasi',
                    data     : { email:sess_email, kode:kode_verifikasi },
                    dataType : 'json',
                    success:function(response) {

                        $('#submit-verifikasi-kode').buttonLoader('stop');

                        if(response.status == 1) {
                            top.location.href='<?php echo base_url();?>form-lupa-password';
                        } 
                        else {
                            $('#feedback-verifikasi-kode').alertNotification('danger', response.message);
                        }
                    }
                })
            }
        });
    });
</script>

<script type="text/javascript">
    var pinContainer = document.querySelector(".pin-code");

    pinContainer.addEventListener('keyup', function (event) {
        var target = event.srcElement;

        var maxLength = parseInt(target.attributes["maxlength"].value, 10);
        var myLength = target.value.length;

        if (myLength >= maxLength) {
            var next = target;
            while (next = next.nextElementSibling) {
                if (next == null) break;
                if (next.tagName.toLowerCase() == "input") {
                    next.focus();
                    break;
                }
            }
        }

        if (myLength === 0) {
            var next = target;
            while (next = next.previousElementSibling) {
                if (next == null) break;
                if (next.tagName.toLowerCase() == "input") {
                    next.focus();
                    break;
                }
            }
        }
    }, false);

    pinContainer.addEventListener('keydown', function (event) {
        var target = event.srcElement;
        target.value = "";
    }, false);
</script>