<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form method="post" id="form-data" enctype="multipart/form-data" role="form">
                    <input type="hidden" name="id" id="id">

                    <div class="row">
                        <div class="col-md-6 pr-md-5">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Unggah Logo</label>
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail">
                                                <img id="logo_preview" src="<?php echo default_image(); ?>">
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                            <div id="logo_feed">
                                                <span class="btn btn-outline-primary btn-file">
                                                    <span class="fileupload-new"><span class="icon feather icon-image"></span> Pilih File</span>
                                                    <span class="fileupload-exists"><span class="icon feather icon-repeat"></span> Ganti</span>
                                                    <input type="file" name="logo" id="logo" accept="image/*">
                                                </span> 
                                                <a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><span class="icon feather icon-trash"></span> Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Unggah Logo Sponsor</label>
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail">
                                                <img id="logo_sponsor_preview" src="<?php echo default_image(); ?>">
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                            <div id="logo_sponsor_feed">
                                                <span class="btn btn-outline-primary btn-file">
                                                    <span class="fileupload-new"><span class="icon feather icon-image"></span> Pilih File</span>
                                                    <span class="fileupload-exists"><span class="icon feather icon-repeat"></span> Ganti</span>
                                                    <input type="file" name="logo_sponsor" id="logo_sponsor" accept="image/*">
                                                </span> 
                                                <a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><span class="icon feather icon-trash"></span> Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Unggah Favicon</label>
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail">
                                                <img id="favicon_preview" src="<?php echo default_image(); ?>">
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                            <div id="favicon_feed">
                                                <span class="btn btn-outline-primary btn-file">
                                                    <span class="fileupload-new"><span class="icon feather icon-image"></span> Pilih File</span>
                                                    <span class="fileupload-exists"><span class="icon feather icon-repeat"></span> Ganti</span>
                                                    <input type="file" name="favicon" id="favicon" accept="image/*">
                                                </span> 
                                                <a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><span class="icon feather icon-trash"></span> Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Nama</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>
                            

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Whatsapp</label>
                                        <input type="text" name="whatsapp" id="whatsapp" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Phone</label>
                                        <input type="text" name="phone" id="phone" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact Person</label>
                                        <input type="text" name="contact_person" id="contact_person" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact Name</label>
                                        <input type="text" name="contact_name" id="contact_name" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Facebook</label>
                                        <input type="text" name="facebook" id="facebook" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Twitter</label>
                                        <input type="text" name="twitter" id="twitter" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Instagram</label>
                                        <input type="text" name="instagram" id="instagram" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Youtube</label>
                                        <input type="text" name="youtube" id="youtube" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label class="control-label">Linkedin</label>
                                <input type="text" name="linkedin" id="linkedin" class="form-control">
                            </div> -->
                            
                            <!-- <div class="form-group">
                                <label class="control-label">Tiktok</label>
                                <input type="text" name="tiktok" id="tiktok" class="form-control">
                            </div> -->
                        </div>

                        <div class="col-md-6 pl-md-5">
                            <div class="form-group">
                                <label class="control-label">Address</label>
                                <textarea name="address" id="address" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Google Maps Address</label>
                                <input type="text" name="maps_address" id="maps_address" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Meta Keywords</label>
                                <textarea name="meta_keywords" id="meta_keywords" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Short Description</label>
                                <textarea name="short_description" id="short_description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Button Register</label><br>
                                <select name="register_button" id="register_button" class="form-control" style="width: 100%;">
                                    <option value="">Pilih "true or false"</option>
                                    <option value="true">true</option>
                                    <option value="false">false</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Voting Running</label><br>
                                <select name="voting_running" id="voting_running" class="form-control" style="width: 100%;">
                                    <option value="">Pilih "true or false"</option>
                                    <option value="true">true</option>
                                    <option value="false">false</option>
                                </select>
                            </div>

                            <!-- <div class="form-group">
                                <label class="control-label">AUTH KEY - Firebase Google</label>
                                <textarea name="auth_key_firebase" id="auth_key_firebase" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label">SET CLIENT ID - SSO Google</label>
                                <textarea name="setClientId" id="setClientId" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label">SET CLIENT SECRET - SSO Google</label>
                                <textarea name="setClientSecret" id="setClientSecret" class="form-control" rows="5"></textarea>
                            </div> -->

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
    $(document).ready(function() {
        $.getScript('<?php echo base_url();?>assets/backoffice/js/custome.js');

        var validate_form = $('#form-data').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true
                },
                phone: {
                    required: true
                },
                // contact_person: {
                //     required: true
                // },
                // contact_name: {
                //     required: true
                // },
                // whatsapp: {
                //     required: true
                // },
                meta_description: {
                    required: true
                },
                meta_keywords: {
                    required: true
                },
                short_description: {
                    required: true
                },
                address: {
                    required: true
                },
                // maps_address: {
                //     required: true
                // },
                // auth_key_firebase: {
                //     required: true
                // },
                // setClientId: {
                //     required: false
                // },
                // setClientSecret: {
                //     required: false
                // }
            },
            messages: {
                name: {
                    required: 'Nama situs tidak boleh kosong.'
                },
                email: {
                    required: 'Email tidak boleh kosong.'
                },
                phone: {
                    required: 'No telp tidak boleh kosong.'
                },
                // contact_person: {
                //     required: 'Contact person tidak boleh kosong.'
                // },
                // contact_name: {
                //     required: 'Contact name tidak boleh kosong.'
                // },
                // whatsapp: {
                //     required: 'Whatsapp tidak boleh kosong.'
                // },
                meta_description: {
                    required: 'Meta Description tidak boleh kosong.'
                },
                meta_keywords: {
                    required: 'Meta Keywords tidak boleh kosong.'
                },
                short_description: {
                    required: 'Short Description tidak boleh kosong.'
                },
                address: {
                    required: 'Address tidak boleh kosong.'
                },
                // maps_address: {
                //     required: 'Maps Address tidak boleh kosong.'
                // },
                // auth_key_firebase: {
                //     required: 'Auth key firebase tidak boleh kosong.'
                // },
                // setClientId: {
                //     required: 'Client ID tidak boleh kosong.'
                // },
                // setClientSecret: {
                //     required: 'Client secret tidak boleh kosong.'
                // }
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
                if(element.is('#logo')) {
                    error.insertAfter('.logo_feed');
                } 
                else if(element.is('#logo_sponsor')) {
                    error.insertAfter('.logo_sponsor_feed');
                }
                else if(element.is('#favicon')) {
                    error.insertAfter('.favicon_feed');
                } 
                else {
                    error.appendTo(element.parent());
                }
            }
        });

        load_data();

        function load_data() {
            $.ajax({
                url      : '<?php echo base_url();?>admin/setting/website/get_data',
                success  : function(response) {
                    $('#id').val(response.query.id);
                    $('#name').val(response.query.name);
                    $('#email').val(response.query.email);
                    $('#email_notifikasi').val(response.query.email_notifikasi);
                    $('#email_notifikasi_2').val(response.query.email_notifikasi_2);
                    $('#phone').val(response.query.phone);
                    $('#contact_name').val(response.query.contact_name);
                    $('#contact_person').val(response.query.contact_person);
                    $('#address').val(response.query.address);
                    $('#maps_address').val(response.query.maps_address);
                    $('#whatsapp').val(response.query.whatsapp);
                    $('#facebook').val(response.query.facebook);
                    $('#twitter').val(response.query.twitter);
                    $('#instagram').val(response.query.instagram);
                    $('#linkedin').val(response.query.linkedin);
                    $('#tiktok').val(response.query.tiktok);
                    $('#youtube').val(response.query.youtube);
                    $('textarea#short_description').val(response.query.short_description);
                    $('textarea#meta_description').val(response.query.meta_description);
                    $('textarea#meta_keywords').val(response.query.meta_keywords);
                    $('textarea#short_description_en').val(response.query.short_description_en);
                    $('textarea#meta_description_en').val(response.query.meta_description_en);
                    $('textarea#meta_keywords_en').val(response.query.meta_keywords_en);
                    $('textarea#short_description_ch').val(response.query.short_description_ch);
                    $('textarea#meta_description_ch').val(response.query.meta_description_ch);
                    $('textarea#meta_keywords_ch').val(response.query.meta_keywords_ch);
                    $('textarea#auth_key_firebase').val(response.query.auth_key_firebase);
                    $('textarea#setClientId').val(response.query.setClientId);
                    $('textarea#setClientSecret').val(response.query.setClientSecret);
                    $("#register_button").val(response.query.register_button).trigger('change');
                    $("#voting_running").val(response.query.voting_running).trigger('change');
                    $('#logo_preview').attr('src', response.link_logo);
                    $('#logo_sponsor_preview').attr('src', response.link_logo_sponsor);
                    $('#favicon_preview').attr('src', response.link_favicon);
                }
            });
        }

        // --------------------------- edit data ---------------------------
            $('#form-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    
                    $('#submit-form-data').buttonLoader('start');

                    $.ajax({
                        url    : '<?php echo base_url();?>admin/setting/website/edit_data',
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