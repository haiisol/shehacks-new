<div class="wrap-action">
    <div class="action-area">    
        <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
        <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <select name="fil_kategori" id="fil_kategori" data-placeholder="Pilih Kategori" class="form-control form-control-sm select2-custome" style="width: 500px; max-width: 500px">
                    <option value="0" selected>All Kategori User</option>
                        <option value="Ideasi">Ideasi</option>
                        <option value="Innovate">Innovate</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
        </form>
    </div>
</div>


<div class="card mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-data" class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center py-0"><label class="checkbox-custome"><input type="checkbox" name="check-all-record"></label></th>
                        <th class="text-center">No</th>
                        <th class="text-center">Logo</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Nama Founders</th>
                        <th class="text-center">Nama Usaha</th>
                        <th class="text-center">Video</th>
                        <th class="text-center" width="15%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-data" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <form method="post" id="form-tambah-data" enctype="multipart/form-data" class="cmxform">
                    <input type="hidden" name="param" id="param">
                    <input type="hidden" name="id" id="id">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori" id="kategori" data-placeholder="Pilih paket modul" class="form-control select2-custome">
                                    <option value="Ideasi">Ideasi</option>
                                    <option value="Innovate">Innovate</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nama Founders</label>
                                <input type="text" name="nama_founders" id="nama_founders" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>

                            <div class="form-group">
                                <label>Nama Usaha</label>
                                <input type="text" name="nama_usaha" id="nama_usaha" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>

                            <div class="form-group">
                                <label>Bidang Usaha</label>
                                <input type="text" name="bidang_usaha" id="bidang_usaha" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>

                            <div class="form-group">
                                <label>Wilayah atau Domisili</label>
                                <input type="text" name="domisili" id="domisili" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>

                            <div class="form-group hide-items url">
                                <label>Embed Code Youtube</label>
                                <p class="description"><ion-icon name="information-circle-sharp"></ion-icon> contoh: https://www.youtube.com/watch?v=<b>93FiM3tWT0g</b></p>
                                <input type="text" name="video_upload" id="video_upload" placeholder="Contoh: 93FiM3tWT0g" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Logo Usaha</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail">
                                        <img id="logo_preview" src="<?php echo base_url();?>assets/backoffice/images/no-image.png" alt="" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 100px; max-height: 100px;"></div>
                                    <div id="cover_feed">
                                        <span class="btn btn-md btn-primary btn-file">
                                            <span class="fileupload-new">Pilih gambar</span>
                                            <span class="fileupload-exists">Ganti gambar</span>
                                            <input type="file" name="logo" id="logo" accept="image/*">
                                        </span> 
                                        <a href="#" class="btn btn-xs btn-danger fileupload-exists" data-dismiss="fileupload">Hapus gambar</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi Usaha</label>
                                <textarea name="description" id="description" rows="5" class="form-control summernote"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="block-bottom d-flex justify-content-between">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary"><span class="icon feather icon-x"></span>Batal</button>
                                <button type="submit" id="submit" class="btn btn-sm btn-primary"><span class="icon feather icon-check"></span> Selesai</button>
                            </div>
                        </div>
                    </div>                    
                </form>
            </div>

        </div>
    </div> 
</div>


<?php include APPPATH.'views/admin/component/include_source.php'; ?>

<script type="text/javascript">

    $(document).ready(function() {
        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

        // summernote
        var summernoteElement = $('.summernote');
        $('#description').summernote({
            placeholder: '',
            fontNames: ['Roboto', 'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
            fontNamesIgnoreCheck: ['Roboto'],
            lineHeight: 30,
            tabsize: 2,
            height: 250,
            callbacks: {
                onChange: function (contents, $editable) {
                    summernoteElement.val(summernoteElement.summernote('isEmpty') ? "" : contents);
                }
            }
        });

        var table_data = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/voting/voting/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets   : [0, 1, 2, 3, -2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 2, 3, -2, -1],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table_data.ajax.reload();
        });

        // --------------------------- filter & export data ---------------------------
            $('#form-filter').submit(function (e) {
                e.preventDefault();

                filter = $(this).serialize();

                url_reload = '<?php echo base_url();?>admin/voting/voting/datatables/?' + filter;
                table_data.ajax.url(url_reload).load();

                url_export = '<?php echo base_url();?>admin/voting/voting/export/?' + filter;
                $('#export-excel').attr('href', url_export);

            });
        // --------------------------- filter & export data ---------------------------


        var $param_input = $('#param');
        $("#form-tambah-data").validate({
            rules: {
                kategori: {
                    required: true
                },
                nama_founders: {
                    required: true
                },
                nama_usaha: {
                    required: true
                },
                bidang_usaha: {
                    required: true
                },
                description: {
                    required: true
                },
                domisili: {
                    required: true
                },
                video_upload: {
                    required: true
                },
                logo: {
                    extension: "jpg|jpeg|png",
                    filesize: 1000000
                }
                
            },
            messages: {
                kategori: {
                    required: "Silahkan pilih kategori."
                },
                nama_founders: {
                    required: "Nama founders harus diisi."
                },
                nama_usaha: {
                    required: "Nama Usaha harus diisi."
                },
                bidang_usaha: {
                    required: "Bidang usaha harus diisi."
                },
                description: {
                    required: "Deskripsi usaha harus diisi."
                },
                domisili: {
                    required: "Domisili usaha harus diisi."
                },
                video_upload: {
                    required: "Code video youtube harus diisi."
                },
                logo: {
                    extension: "Unggah photo dengan format .PNG/.JPG/.JPEG",
                    filesize: "File maksimal 1 Mbps."
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
                if (element.is(':radio')) {
                    var controls = element.closest('div[class*="form-group"]');
                    controls.append(error);
                } 
                else if(element.is('input[type=file]')) {
                    error.insertAfter(element.parent());
                } 
                else if(element.is('#deskripsi_modul')) {
                    error.insertAfter(element.siblings(".note-editor"));
                }
                else {
                    error.appendTo(element.parent());
                }
            }
        });

        // --------------------------- add & edit data --------------------------- 
            $('#add-data').on('click', function() {
                save_method = 'add';

                $('#modal-data').modal('show');
                $('.modal-title').text('Tambah data');
                $('#param').val('add');
                
                $("#form-tambah-data")[0].reset();
                $('#logo_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                $('#description').summernote('code', '');
            });


            $('#table-data').on('click', '#edit-data', function() {
                save_method = 'edit';
                
                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');
                $('#param').val('edit');

                $("#form-tambah-data")[0].reset();
                $('#logo_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                $('#description').summernote('code', '');

                $.ajax({
                    url      : '<?php echo base_url();?>admin/voting/voting/get_data',
                    dataType : 'json',
                    data     : { id:id },
                    success: function(response) {

                        $('#modal-data').modal('show');

                        $('#id').val(response.id_voting);
                        $('#kategori').val(response.kategori);
                        $('#nama_founders').val(response.nama_founders);
                        $('#nama_usaha').val(response.nama_usaha);
                        $('#domisili').val(response.domisili);
                        $('#bidang_usaha').val(response.bidang_usaha);
                        $('#video_upload').val(response.video_upload);
                        $('#description').summernote('code', response.description);
                        $('#logo_preview').attr('src', response.logo);
                    }
                });
                return false;
            });

            $('#form-tambah-data').submit(function(e) {
                e.preventDefault();
                if (jQuery("#form-tambah-data").valid()) {
                    $('#submit').buttonLoader('start');
                    
                    if(save_method == 'add') {
                        url = "<?php echo base_url();?>admin/voting/voting/add_data";
                    } else {
                        url = "<?php echo base_url();?>admin/voting/voting/edit_data";
                    }

                    $.ajax({
                        url      : url,
                        method   : 'post',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            $('#submit').buttonLoader('stop');
                            $('#modal-data').modal('hide');

                            if(response['status'] == 1 || response['status'] == 3) {
                                notif_success(response['message']);
                                $("#form-tambah-data")[0].reset();
                                table_data.ajax.reload();
                            } 
                            else if(response['status'] == 2 || response['status'] == 4) {
                                notif_error(response['message']);
                            }
                        }
                    })
                }
            });
        // --------------------------- end add & edit data ---------------------------

            
        // --------------------------- delete data ---------------------------
            // delete sigle
            $('#table-data').on('click', '#delete-data', function() {
                var id = $(this).attr('data');
                $('#modal-delete').modal('show');
                $('#id3').val(id);
                $('#method').val('single');
            });

            // post delete
            $('#button-delete').on('click', function() {
                var id = $('#id3').val();
                var method = $('#method').val();

                $.ajax({
                    type : "POST",
                    url  : "<?php echo base_url();?>admin/voting/voting/delete_data",
                    dataType : "JSON",
                    data : { id:id, method:method },
                    success: function(response) {
                        if(response.status == 1) {
                            notif_success('Data berhasil dihapus.');
                        } else if(response.status == 2) {
                            notif_success('Data berhasil dihapus.');
                            $('#delete-data-multiple').addClass('invisible', true);
                        } else {
                            notif_error('Gagal menghapus data.');
                        }

                        $('#modal-delete').modal('hide');
                        table_data.ajax.reload();
                    }
                });
                return false;
            });
        // --------------------------- end delete data ---------------------------

    });
</script>