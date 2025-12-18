<?php 
    $platform = [
        [
            'id' => 'Instagram',
            'name' => 'Instagram'
        ],
        [
            'id' => 'Website',
            'name' => 'Website'
        ],
    ];
?>

<div class="wrap-action">
    <div class="action-area">    
        <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
        <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
    </div>

    <!-- <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
        </form>
    </div> -->
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
                        <th class="text-center">Nama Startup</th>
                        <th class="text-center">Nama Founders</th>
                        <th class="text-center">Sector</th>
                        <th class="text-center">Period</th>
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
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Sector</label>
                                <select name="id_sector" id="id_sector" data-placeholder="Pilih sector" class="form-control select2-custome">
                                    <option value="" selected readonly>Pilih Sector</option>
                                    <?php foreach ($sector as $key) { ?>
                                        <option value="<?php echo $key['id_sector']; ?>"><?php echo $key['nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Periode</label>
                                <select name="period" id="period" data-placeholder="Pilih periode" class="form-control select2-custome">
                                    <option value="" selected readonly>Pilih Periode</option>
                                    <?php foreach (period() as $key) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                                    <?php } ?>
                                </select>
                            </div>  
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Nama Startup</label>
                                <input type="text" name="startup_name" id="startup_name" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Nama Founders</label>
                                <input type="text" name="founders_name" id="founders_name" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Founders URL (Linkedin URL)</label>
                                <input type="text" name="founders_url" id="founders_url" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <!-- <label>Label URL (Contoh: Website / Instagram)</label> -->
                                <!-- <input type="text" name="url_label" id="url_label" class="form-control" autocomplete="off" autocorect="off" autofocus="on"> -->
                                <label>Platform</label>
                                <select name="url_label" id="url_label" data-placeholder="Pilih Platform" class="form-control select2-custome">
                                    <option value="" selected readonly>Pilih Platform</option>
                                    <?php foreach ($platform as $key) { ?>
                                        <option value="<?php echo $key['id']; ?>"><?php echo $key['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>URL Platform</label>
                                <input type="text" name="url" id="url" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Sort Deskripsi</label>
                                <textarea name="sort_description" id="sort_description" rows="5" class="form-control"></textarea>
                            </div>   

                            <div class="form-group">
                                <label>Logo Startup</label>
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
                                <label>Image Thumbnail Startup</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail">
                                        <img id="thumbnail_preview" src="<?php echo base_url();?>assets/backoffice/images/no-image.png" alt="" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 100px; max-height: 100px;"></div>
                                    <div id="thumbnail_feed">
                                        <span class="btn btn-md btn-primary btn-file">
                                            <span class="fileupload-new">Pilih gambar</span>
                                            <span class="fileupload-exists">Ganti gambar</span>
                                            <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
                                        </span> 
                                        <a href="#" class="btn btn-xs btn-danger fileupload-exists" data-dismiss="fileupload">Hapus gambar</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
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
                url : '<?php echo base_url();?>admin/startup/startup/datatables',
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets   : [0, 1, 2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 1, 2, 3, -2, -1],
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

                url_reload = '<?php echo base_url();?>admin/startup/startup/datatables/?' + filter;
                table_data.ajax.url(url_reload).load();

                url_export = '<?php echo base_url();?>admin/startup/startup/export/?' + filter;
                $('#export-excel').attr('href', url_export);

            });
        // --------------------------- filter & export data ---------------------------


        var $param_input = $('#param');
        $("#form-tambah-data").validate({
            rules: {
                founders_name: {
                    required: true
                },
                founders_url: {
                    required: true
                },
                url_label: {
                    required: true
                },
                url: {
                    required: true
                },
                startup_name: {
                    required: true
                },
                sort_description: {
                    required: true
                },
                description: {
                    required: true
                },
                id_sector: {
                    required: true
                },
                period: {
                    required: true
                },
                logo: {
                    extension: "jpg|jpeg|png",
                    filesize: 1000000
                },
                thumbnail: {
                    extension: "jpg|jpeg|png",
                    filesize: 1000000
                }
            },
            messages: {
                founders_name: {
                    required: "Nama founders harus diisi."
                },
                founders_url: {
                    required: "URL founders harus diisi."
                },
                url_label: {
                    required: "Platform harus diisi."
                },
                url: {
                    required: "URL platform harus diisi."
                },
                startup_name: {
                    required: "nama Startup harus diisi."
                },
                sort_description: {
                    required: "Sort Description harus diisi."
                },
                description: {
                    required: "Deskripsi usaha harus diisi."
                },
                id_sector: {
                    required: "Sektor usaha harus dipilih."
                },
                period: {
                    required: "Periode harus dipilih."
                },
                logo: {
                    extension: "Unggah photo dengan format .PNG/.JPG/.JPEG",
                    filesize: "File maksimal 1 Mbps."
                },
                thumbnail: {
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
                $('#thumbnail_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                $('#description').summernote('code', '');
                $('#id_sector').val('').trigger('change');
                $('#period').val('').trigger('change');
                $('#url_label').val('').trigger('change');
            });


            $('#table-data').on('click', '#edit-data', function() {
                save_method = 'edit';
                
                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');
                $('#param').val('edit');

                $("#form-tambah-data")[0].reset();
                $('#logo_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                $('#thumbnail_preview').attr('src', '<?php echo base_url();?>assets/backoffice/images/no-image.png');
                $('#description').summernote('code', '');
                $('#id_sector').val('').trigger('change');
                $('#period').val('').trigger('change');
                $('#url_label').val('').trigger('change');

                $.ajax({
                    url      : '<?php echo base_url();?>admin/startup/startup/get_data',
                    dataType : 'json',
                    data     : { id:id },
                    success: function(response) {

                        $('#modal-data').modal('show');

                        $('#id').val(response.id_startup);
                        $('#id_sector').val(response.id_sector).trigger('change');
                        $('#period').val(response.period).trigger('change');
                        $('#founders_name').val(response.founders_name);
                        $('#founders_url').val(response.founders_url);
                        // $('#url_label').val(response.url_label);
                        $('#url_label').val(response.url_label).trigger('change');
                        $('#url').val(response.url);
                        $('#startup_name').val(response.startup_name);
                        $('#sort_description').val(response.sort_description);
                        $('#description').summernote('code', response.description);
                        $('#logo_preview').attr('src', response.logo);
                        $('#thumbnail_preview').attr('src', response.thumbnail);
                    }
                });
                return false;
            });

            $('#form-tambah-data').submit(function(e) {
                e.preventDefault();
                if (jQuery("#form-tambah-data").valid()) {
                    $('#submit').buttonLoader('start');
                    
                    if(save_method == 'add') {
                        url = "<?php echo base_url();?>admin/startup/startup/add_data";
                    } else {
                        url = "<?php echo base_url();?>admin/startup/startup/edit_data";
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
                    url  : "<?php echo base_url();?>admin/startup/startup/delete_data",
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