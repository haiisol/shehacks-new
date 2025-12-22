<!-- google recaptcha -->
<script src="https://www.google.com/recaptcha/api.js"></script>

<section class="contact-section section section-sm">
    <div class="container">
        <div class="inner">
            <div class="row justify-content-between">
            	<div class="col-lg-6">
                    <div class="thumb mb-5">
                        <img src="<?php echo base_url();?>assets/front/img/thumb/contact-thumb.png" alt="Contact Thumbnail" class="img-fluid lazyload thumb-img">
                    </div>

                    <div class="section-title">
			            <h2 class="title section-heading">Keep in Touch</h2>
			            <p class="description section-description">Jika masih ada pertanyaan atau hal yang ingin kamu ketahui tentang SheHacks 2025, kamu bisa hubungi kami melalui email <a href="mailto:shehacks@kumpul.id" target="_blank"><strong>shehacks@kumpul.id</strong></a></p>
			        </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-form">
                        <div id="feedback-contact"></div>
                        <form method="post" id="form-contact" class="form-style">
                            <div class="form-group">
                                <label for="name" class="control-label">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Masukan nama lengkap Anda" autocomplete="off" autocorrect="off">
                            </div>

                            
                            <div class="form-group">
                            	<label for="email" class="control-label">Email</label>
                            	<input type="email" name="email" id="email" class="form-control" placeholder="Masukan alamat email Anda" autocomplete="off" autocorrect="off">
                            </div>

                            <div class="form-group">
                            	<label for="telp" class="control-label">Nomor Handphone</label>
                            	<div class="input-group input-telp">
                            		<div class="input-group-addon">
                            			<img data-src="<?php echo base_url();?>assets/front/img/ind.png" alt="Indonesia Flag" class="img-fluid flag-ind lazyload" width="20" height="20"> <span>+62</span>
                            		</div>
                            		<input type="tel" name="phone" id="phone" class="form-control val-telp" placeholder="82140xxxxxx" autocomplete="off" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            	</div>
                            </div>
   
                            <div class="form-group">
                                <label for="subject" class="control-label">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Masukan subjek" autocomplete="off" autocorrect="off">
                            </div>

                            <div class="form-group">
                                <label for="message" class="control-label">Pesan</label>
                                <textarea name="message" id="message" rows="4" class="form-control" placeholder="Tulis pesan Anda disini"></textarea>
                            </div>

                            <div class="g-recaptcha" data-sitekey="6LfAu4MlAAAAADEFYB2Z07C7HlPkEJOCcGYL9o2X"></div>
                            
                            <div class="form-group text-right mt-5">
                                <button type="submit" id="submit-contact" class="btn btn-hover-icon-left"><span class="icon lni lni-envelope"></span> <span>Pesan</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php echo view('component/section/cta'); ?>


<script type="text/javascript">
	$(document).ready(function() {
		// ---------------------------  contact ---------------------------
            $('#phone').on('input propertychange paste', function (e) {
                var val = $(this).val()
                var reg = /^0/gi;
                if (val.match(reg)) {
                    $(this).val(val.replace(reg, ''));
                }
            });

            var validate_contact = $('#form-contact').validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        maxlength: 12
                    },
                    subject: {
                        required: true
                    },
                    message: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: 'Nama harus diisi.'
                    },
                    email: {
                        required: 'Email harus diisi.',
                        email: 'Masukkan email dengan benar.'
                    },
                    phone: {
                        required: 'No handphone harus diisi.',
                        maxlength: 'Masukkan tidak lebih dari 12 karakter.'
                    },
                    subject: {
                        required: 'Subjek harus diisi.'
                    },
                    message: {
                        required: 'Pesan harus diisi.'
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
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $('#form-contact').submit(function(e) {
                e.preventDefault();

                if (validate_contact.valid()) {
                    $('#submit-contact').buttonLoader('start');

                    $.ajax({
                        url     : '<?php echo base_url();?>home/post_contact',
                        method  : 'post',
                        data    : new FormData(this),
                        dataType: 'json',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            $('#submit-contact').buttonLoader('stop');

                            if(response.status == 1) {
                                $('#form-contact')[0].reset();
                                $('#feedback-contact').alertNotification('success', response.message);
                            } 
                            else if (response.status == 2) {
                                $('#feedback-contact').alertNotification('warning', response.message);
                            }
                            else {
                                $('#feedback-contact').alertNotification('danger', response.message);
                            }
                        }
                    })
                }
            });
        // --------------------------- end contact --------------------------- 

    });

</script>