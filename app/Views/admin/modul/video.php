
<div class="action-area">    
    <a href="javascript:void(0);" id="add-data" class="btn btn-primary <?php echo $access_add; ?>"><span class="icon feather icon-plus"></span> Tambah</a>
    <a href="javascript:void(0)" id="reload-table" class="btn btn-outline-secondary"><span class="icon feather icon-refresh-cw"></span> Reload</a>
    <a href="javacript:void(0);" id="delete-data-multiple" class="btn btn-danger invisible <?php echo $access_delete; ?>"><span class="icon feather icon-trash"></span> Hapus</a>
</div>

<div class="row mt-3">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center py-0"><label class="checkbox-custome"><input type="checkbox" name="check-all-record"></label></th>
                            <th class="text-center">No</th>
                            <th class="text-center">Judul</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Modul</th>
                            <th class="text-center">File</th>
                            <th class="text-center">Durasi</th>
                            <th class="text-center" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-data" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <form method="post" id="form-tambah-data" enctype="multipart/form-data" class="cmxform">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="id_modul" id="id_modul" value="<?php echo $modul['id_modul']; ?>">
                    <input type="hidden" name="kategori" id="kategori" value="<?php echo $modul['kategori']; ?>">
                    <input type="hidden" name="durasi" id="durasi">
                    <input type="hidden" name="param" id="param">
                    
                    <div class="form-group">
                        <label>Judul Video</label>
                        <input type="text" name="judul" id="judul" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                    </div>

                    <div class="form-group">
                        <label>Upload Video</label><br>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="jupload1" name="jenis" value="url"> Embed Youtube
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="jupload0" name="jenis" value="upload"> Upload File
                            </label>
                        </div>
                    </div>

                    <div class="form-group hide-items url">
                        <label>Embed Code Youtube</label>
                        <p class="description"><ion-icon name="information-circle-sharp"></ion-icon> contoh: https://www.youtube.com/watch?v=<b>93FiM3tWT0g</b></p>
                        <input type="text" name="url" id="url" placeholder="Contoh: 93FiM3tWT0g" class="form-control" autocomplete="off" autocorect="off" autofocus="on">
                    </div>

                    <div class="form-group hide-items upload-video">
                        <label>Upload File Video</label>

                        <input type="file" name="file_video" id="file_video" class="dropify form-control-file-input"  data-allowed-file-extensions="mp4 mpg mpeg mov avi flv wmv" >

                        <div class="progress mt-3 mb-3">
                            <div class="progress-bar progress-bar-striped" id="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="block-bottom d-flex justify-content-between mt-3">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary"><span class="icon feather icon-x"></span>Batal</button>
                        <button type="submit" id="submit" class="btn btn-sm btn-primary">Selesai</button>
                    </div>           
                </form>
            </div>

        </div>
    </div> 
</div>


<?php include APPPATH.'views/admin/component/include_source.php'; ?>

<script>
    $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");

    $(document).ready(function() {

        $('.hide-items').hide();

        $('input[name$="jenis"]').change(function() {
            if ($(this).val() == 'url') {
                $('.url').slideDown('slow');
                $('.upload-video').slideUp();
            }
            if ($(this).val() == 'upload') {
                $('.url').slideUp();
                $('.upload-video').slideDown('slow');
            }
        });
        

        var id_modul = '<?php echo $modul['id_modul']; ?>';

        var validate_form = $("#form-tambah-data").validate({
            rules: {
                judul: {
                    required: true
                },
                file_video: {
                    filesize: 100000000
                }
            },
            messages: {
                judul: {
                    required: "Judul video harus diisi."
                },
                file_video: {
                    filesize: "File maksimal 100 Mbps."
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

        var table = $('#table-data').DataTable({
            ajax: {
                url      : '<?php echo base_url();?>admin/modul/video/datatables',
                data     : { id_modul:id_modul },
            },
            order      : [[ 0, 'DESC' ]],
            columnDefs : [
                {
                    className : "text-center",
                    targets   : [0, 1, 3, -3, -2, -1],
                }
            ],
        });

        $('#reload-table').on('click', function() {
            table.ajax.reload();
        });


        // --------------------------- add & edit data --------------------------- 
            $('#add-data').on('click', function() {
                save_method = 'add';

                $('#modal-data').modal('show');
                $('.modal-title').text('Tambah data');
                $('#param').val('add');

                $('#form-tambah-data')[0].reset();
            });

            $('#table-data').on('click', '#edit-data', function() {
                save_method = 'edit';

                var id = $(this).attr('data');

                $('#modal-data').modal('show');
                $('.modal-title').text('Edit data');
                $('#param').val('edit');

                $('#form-tambah-data')[0].reset();

                $.ajax({
                    type     : 'post',
                    url      : '<?php echo base_url();?>admin/modul/video/get_data',
                    dataType : 'json',
                    data     : { id:id },
                    success: function(response) {

                        $('#id').val(response.id_video);
                        $('#judul').val(response.judul);
                        $('#durasi').val(response.durasi);

                        if (response.jenis == 'url') {
                            $('.url').slideDown('slow');
                            $('.upload-video').hide();

                            $('#jupload1').prop('checked', true);
                            $('#url').val(response.url);
                        } 
                        else {
                            $('.url').hide();
                            $('.upload-video').slideDown('slow');

                            $('#jupload0').prop('checked', true);
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
                        url = "<?php echo base_url();?>admin/modul/video/add_data";
                    } else {
                        url = "<?php echo base_url();?>admin/modul/video/edit_data";
                    }

                    $.ajax({
                        method   : 'POST',
                        url      : url,
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType : false,
                        processData : false,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();         
                            xhr.upload.addEventListener('progress', function(element) {
                                if (element.lengthComputable) {
                                    var percentComplete = ((element.loaded / element.total) * 100);
                                    $('#progress-bar').width(Math.round(percentComplete) + '%');
                                    $('#progress-bar').html(Math.round(percentComplete) + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success:function(response) {
                            $('#submit').buttonLoader('stop');
                            $('#modal-data').modal('hide');
                            $('#progress-bar').width(0);
                            $('#progress-bar').html(' 0 %');

                            if(response['status'] == 1 || response['status'] == 3) {
                                notif_success(response['message']);
                                $("#form-tambah-data")[0].reset();
                                table.ajax.reload();
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
                    method   : 'POST',
                    url      : '<?php echo base_url();?>admin/modul/video/delete_data',
                    data     : { id:id, method:method },
                    dataType : 'json',
                    success: function(response) {
                        if(response == 1) {
                            notif_success('Data berhasil dihapus.');
                            Toast.fire({ type: 'success', title: 'Data berhasil dihapus.' });
                        } 
                        else if(response == 2) {
                            notif_success('Data berhasil dihapus.');
                            $('#delete-data-multiple').addClass('invisible', true);
                        } else {
                            notif_error('Gagal menghapus data.');
                        }

                        $('#modal-delete').modal('hide');
                        table.ajax.reload();
                    }
                });
                return false;
            });
        // --------------------------- end delete data ---------------------------


        // --------------------------- get duration ---------------------------
            var myVideos = [];
            window.URL = window.URL || window.webkitURL;

            document.getElementById('file_video').onchange = setFileInfo;

            function setFileInfo() {
                var files = this.files;
                myVideos.push(files[0]);
                var video = document.createElement('video');
                video.preload = 'metadata';

                video.onloadedmetadata = function() {
                    window.URL.revokeObjectURL(video.src);
                    var duration = video.duration;
                    myVideos[myVideos.length - 1].duration = duration;
                    updateInfos();
                }

                video.src = URL.createObjectURL(files[0]);;
            }

            function updateInfos() {

                for (var i = 0; i < myVideos.length; i++) {
                    infos = myVideos[i].duration;
                }

                if (infos > 0) {
                    var hours = parseInt(infos / 3600, 10);
                    var minutes = parseInt(infos / 60, 10);
                    var seconds = Math.floor(infos % 60);  
                }

                if (hours == 0) { HH = '00'; } else { HH = hours; }
                if (minutes == 0) { II = '00'; } else { II = minutes; }
                if (seconds == 0) { SS = '00'; } else { SS = seconds; }

                var output = HH + ':' + II + ':' + SS;

                $('#durasi').val(output);
                // console.log(output);
            }
        // --------------------------- end get duration ---------------------------

    });
</script>
