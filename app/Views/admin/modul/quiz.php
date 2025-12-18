<div class="wrap-action">
    <div class="action-area">    
        <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
        <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
        <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
    </div>

    <div class="filter-area">
        <form method="post" id="form-filter" class="form-style">
            <div class="form-group mb-2">
                <select name="fil_kategori" id="fil_kategori" data-placeholder="Pilih Kategori" class="form-control form-control-sm select2-custome-search" style="width: 180px;">
                    <option value="0" selected>All Kategori</option>
                    <option value="Ideasi">Ideasi</option>
                    <option value="MVP">MVP</option>
                </select>
            </div>
            <div class="form-group mr-2">
                <select name="fil_modul" id="fil_modul" class="form-control form-control-sm select2-custome-search" style="width: 180px;">
                    <option value="0" selected>Semua Modul</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" name="filter" class="btn btn-padd-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Submit Filter"><span class="icon feather icon-search"></span></button>
            </div>
            <div class="form-group">
                <a type="submit" href="<?php echo base_url();?>admin/modul/quiz/export" name="export" id="export_excel" class="btn btn-padd-xs btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Export"><span class="icon feather icon-file"></span></a>
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
                        <th class="text-center">Paket Modul</th>
                        <th class="text-center">Modul</th>
                        <th class="text-center">Pertanyaan</th>
                        <th class="text-center">Jawaban Benar</th>
                        <th class="text-center" width="15%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-data" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <form method="post" id="form-tambah-data" enctype="multipart/form-data" class="cmxform">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="id_a" id="id_a">
                    <input type="hidden" name="id_b" id="id_b">
                    <input type="hidden" name="id_c" id="id_c">
                    <input type="hidden" name="id_d" id="id_d">
                    
                     <div class="form-group">
                        <label>Modul</label>
                        <select name="modul" id="modul" data-placeholder="Pilih modul" class="form-control select2-custome-search">
                            <option value="" selected readonly>Pilih Modul</option>
                            <?php foreach ($modul as $key) { ?>
                                <option value="<?php echo $key['id_modul']; ?>"><?php echo $key['modul']; ?> <small>(<?php echo $key['kategori']; ?>)</small></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <textarea name="pertanyaan" id="pertanyaan" rows="4" class="form-control summernotex"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mt-2">
                                <div class="card-body p-3">
                                    <div class="form-group">
                                        <label>Pilihan (A)</label>
                                        <textarea name="pilihan_a" id="pilihan_a" rows="3" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label mb-0">
                                                <input type="radio" class="form-check-input" name="optionjawaban" id="optionjawabana" value="a">
                                                Jawaban Benar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mt-3">
                                <div class="card-body p-3">
                                    <div class="form-group">
                                        <label>Pilihan (B)</label>
                                        <textarea name="pilihan_b" id="pilihan_b" rows="3" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label mb-0">
                                                <input type="radio" class="form-check-input" name="optionjawaban" id="optionjawabanb" value="b">
                                                Jawaban Benar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mt-2">
                                <div class="card-body p-3">
                                    <div class="form-group">
                                        <label>Pilihan (C)</label>
                                        <textarea name="pilihan_c" id="pilihan_c" rows="3" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label mb-0">
                                                <input type="radio" class="form-check-input" name="optionjawaban" id="optionjawabanc" value="c">
                                                Jawaban Benar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mt-3">
                                <div class="card-body p-3">
                                    <div class="form-group">
                                        <label>Pilihan (D)</label>
                                        <textarea name="pilihan_d" id="pilihan_d" rows="3" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label mb-0">
                                                <input type="radio" class="form-check-input" name="optionjawaban" id="optionjawaband" value="d">
                                                Jawaban Benar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="block-bottom d-flex justify-content-between">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary"><span class="icon feather icon-x"></span>Batal</button>
                                <button type="submit" class="btn btn-sm btn-primary"><span class="icon feather icon-check"></span> Selesai</button>
                            </div>
                        </div>
                    </div>                    
                </form>
            </div>

        </div>
    </div> 
</div>

<?php include APPPATH.'views/admin/component/include_source.php'; ?>

<script>

    $(document).ready(function() {
        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");
        
        save_method = '';

        var table_data = $('#table-data').DataTable({
            ajax: {
                url : '<?php echo base_url();?>admin/modul/quiz/datatables',
                complete: function(data, type) {
                    json = data.responseJSON;
                },
            },
            columnDefs : [
                {
                    className: 'text-center',
                    targets   : [0, 1, 2, -1],
                },
                {
                    orderable: false,
                    targets  : [0, 2, -1],
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

                url_reload = '<?php echo base_url();?>admin/modul/quiz/datatables/?' + filter;
                table_data.ajax.url(url_reload).load();

                url_export = '<?php echo base_url();?>admin/modul/quiz/export/?' + filter;
                $('#export').attr('href', url_export);

            });
        // --------------------------- end filter & export data ---------------------------


        var validate_form = $('#form-tambah-data').validate({
            rules: {
                modul: {
                    required: true
                },
                pertanyaan: {
                    required: true
                },
                pilihan_a: {
                    required: true
                },
                pilihan_b: {
                    required: true
                },
                pilihan_c: {
                    required: true
                },
                pilihan_d: {
                    required: true
                },
                optionjawaban: {
                    required: true
                }
            },
            messages: {
                modul: {
                    required: 'Silahkan pilih modul.'
                },
                pertanyaan: {
                    required: 'Pertanyaan harus diisi.'
                },
                pilihan_a: {
                    required: 'Pilihan A harus diisi.'
                },
                pilihan_b: {
                    required: 'Pilian B harus diisi.'
                },
                pilihan_c: {
                    required: 'Pilian C harus diisi.'
                },
                pilihan_d: {
                    required: 'Pilian D harus diisi.'
                },
                optionjawaban: {
                    required: 'Jawaban benar harus diisi.'
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
                } else if(element.is('input[type=file]')) {
                    error.insertAfter(element.parent());
                } else if(element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                } else {
                    error.appendTo(element.parent());
                }
            }
        });

        // --------------------------- add & edit data --------------------------- 
            $('#add-data').on('click', function() {
                save_method = 'add';

                $('#modal-data').modal('show');
                $('.modal-title').text('Tambah data');

                $('#form-tambah-data')[0].reset();
            });

            $('#table-data').on('click', '#edit-data', function() {
                // console.log('yes')
                save_method = 'update';

                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');

                $('#form-tambah-data')[0].reset();

                $.ajax({
                    type     : 'post',
                    url      : '<?php echo base_url();?>admin/modul/quiz/get_data',
                    dataType : 'json',
                    data     : { id:id },
                    success: function(response) {

                        $('#id').val(response.id);
                        $('#pertanyaan').val(response.pertanyaan);
                        $('#modul').val(response.id_modul);

                        /*Pilian ganda*/
                        $('#id_a').val(response.id_a);
                        $('#pilihan_a').val(response.jawaban_a);

                        $('#id_b').val(response.id_b);
                        $('#pilihan_b').val(response.jawaban_b);

                        $('#id_c').val(response.id_c);
                        $('#pilihan_c').val(response.jawaban_c);

                        $('#id_d').val(response.id_d);
                        $('#pilihan_d').val(response.jawaban_d);

                        if (response.pilihan_ganda == 'a') {
                            $('#optionjawabana').prop('checked', true);
                        } else if (response.pilihan_ganda == 'b') {
                            $('#optionjawabanb').prop('checked', true);
                        } else if (response.pilihan_ganda == 'c') {
                            $('#optionjawabanc').prop('checked', true);
                        } else {
                            $('#optionjawaband').prop('checked', true);
                        }
                    }
                });
                return false;
            });

            $('#form-tambah-data').submit(function(e) {
                e.preventDefault();

                if (validate_form.valid()) {
                    
                    if (save_method == 'add') {
                        url = '<?php echo base_url();?>admin/modul/quiz/add_data';
                    } else {
                        url = '<?php echo base_url();?>admin/modul/quiz/edit_data';
                    }

                    $.ajax({
                        url    : url,
                        method : 'post',
                        data   : new FormData(this),
                        dataType: 'json',
                        contentType : false,
                        processData : false,
                        success:function(response) {
                            
                            $('#submit').buttonLoader('stop');
                            $('#modal-data').modal('hide');

                            if(response['status'] == 1 || response['status'] == 3) {
                                notif_success(response['message']);
                                $('#form-tambah-data')[0].reset();
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
                    url  : "<?php echo base_url();?>admin/modul/quiz/delete_data",
                    dataType : "JSON",
                    data : { id:id, method:method },
                    success: function(response) {
                        /*console.log(response);*/
                        if(response == 1) {
                            notif_success('Data berhasil dihapus.');
                            Toast.fire({ type: 'success', title: 'Data berhasil dihapus.' });
                        } else if(response == 2) {
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

        $(document).on('change', '#fil_kategori', function() {
            var value = $(this).val();
    
            if(value) {
            
                $.ajax({
                    type : "POST",
                    url  : "<?php echo base_url();?>admin/modul/modul/get_modul",
                    data : { id:value },
                    success: function(response) {
                        $('#fil_modul').html(response);
                    }
                });
                return false;
            }
        });
    });
</script>
