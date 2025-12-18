<div class="wrap-action">    
    <div class="action-area">    
        <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
        <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <select name="fil_kategori" id="fil_kategori" data-placeholder="Pilih Kategori" class="form-control form-control-sm select2-custome-search">
                    <option value="0" selected>All Kategori User</option>
                        <option value="Ideasi">Ideasi</option>
                        <option value="MVP">MVP</option>
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
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Modul</th>
                        <th class="text-center">Quiz</th>
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
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori" id="kategori" data-placeholder="Pilih paket modul" class="form-control select2-custome">
                                    <option value="Ideasi">Ideasi</option>
                                    <option value="MVP">MVP</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label>Modul</label>
                                <input type="text" name="modul" id="modul" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="deskripsi_modul" id="deskripsi_modul" rows="5" class="form-control summernote"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Image</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail">
                                        <img id="cover_preview" src="<?php echo base_url();?>assets/backoffice/images/no-image.png" alt="" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 100px; max-height: 100px;"></div>
                                    <div id="cover_feed">
                                        <span class="btn btn-md btn-primary btn-file">
                                            <span class="fileupload-new">Pilih gambar</span>
                                            <span class="fileupload-exists">Ganti gambar</span>
                                            <input type="file" name="cover" id="cover" accept="image/*">
                                        </span> 
                                        <a href="#" class="btn btn-xs btn-danger fileupload-exists" data-dismiss="fileupload">Hapus gambar</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Apakah mau pakai Quiz?</label><br>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" id="stquiz1" name="status_quiz" value="1"> Iya
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" id="stquiz0" name="status_quiz" value="0"> Tidak
                                    </label>
                                </div>
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
        $('#deskripsi_modul').summernote({
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
                url : '<?php echo base_url();?>admin/modul/modul/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets: [0, 1, 2, -1, -3, -2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 2, -3, -2, -1],
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

                url_reload = '<?php echo base_url();?>admin/modul/modul/datatables/?' + filter;
                table_data.ajax.url(url_reload).load();

                url_export = '<?php echo base_url();?>admin/modul/modul/export/?' + filter;
                $('#export-excel').attr('href', url_export);
            });
        // --------------------------- filter & export data ---------------------------


        var $param_input = $('#param');
        $("#form-tambah-data").validate({
            rules: {
                kategori: {
                    required: true
                },
                modul: {
                    required: true
                },
                deskripsi_modul: {
                    required: true
                },
                status_quiz: {
                    required: true
                },
                cover: {
                    extension: "jpg|jpeg|png",
                    filesize: 1000000
                }
                
            },
            messages: {
                kategori: {
                    required: "Silahkan pilih kategori modul."
                },
                modul: {
                    required: "Nama modul harus diisi."
                },
                deskripsi_modul: {
                    required: "Deskripsi modul harus diisi."
                },
                status_quiz: {
                    required: "Silahkan pilih status quiz."
                },
                cover: {
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
                $('#image_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                $('#deskripsi_modul').summernote('code', '');
            });


            $('#table-data').on('click', '#edit-data', function() {
                save_method = 'edit';
                
                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');
                $('#param').val('edit');

                $("#form-tambah-data")[0].reset();
                $('#image_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                $('#deskripsi_modul').summernote('code', '');

                $.ajax({
                    url      : '<?php echo base_url();?>admin/modul/modul/get_data',
                    dataType : 'json',
                    data     : { id:id },
                    success: function(response) {

                        $('#modal-data').modal('show');

                        $('#id').val(response.id_modul);
                        $('#kategori').val(response.kategori);
                        $('#modul').val(response.modul);
                        $('#deskripsi_modul').summernote('code', response.deskripsi_modul);
                        $('#cover_preview').attr('src', response.cover);

                        if (response.status_quiz == 1) {
                            $('#stquiz1').prop("checked", true);
                        } else {
                            $('#stquiz0').prop("checked", true);
                        }
                    }
                });
                return false;
            });

            $('#form-tambah-data').submit(function(e) {
                e.preventDefault();
                if (jQuery("#form-tambah-data").valid()) {
                    $('#submit').buttonLoader('start');
                    
                    if(save_method == 'add') {
                        url = "<?php echo base_url();?>admin/modul/modul/add_data";
                    } else {
                        url = "<?php echo base_url();?>admin/modul/modul/edit_data";
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
                    url  : "<?php echo base_url();?>admin/modul/modul/delete_data",
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