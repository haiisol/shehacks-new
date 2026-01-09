<!DOCTYPE html>
<html>

<head>
    <?php
    $meta_title = strip_tags($title);
    $meta_description = strip_tags($description);
    $meta_keywords = strip_tags($keywords);
    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $meta_title; ?> | <?php echo $meta_description; ?></title>
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <meta name="author" content="CV Bantu Teknologi Indonesia">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?php echo $site_name; ?>">
    <link rel="apple-touch-icon" href="<?php echo $favicon_img; ?>">
    <link rel="shortcut icon" href="<?php echo $favicon_img; ?>">

    <!-- icon -->
    <link href="<?php echo base_url();?>assets/backoffice/css/icons.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/backoffice/plugins/feather/css/iconfont.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" type="text/css"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link href="<?php echo base_url();?>assets/backoffice/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"> -->
    <link href="<?php echo base_url();?>assets/backoffice/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/backoffice/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/backoffice/plugins/select2/css/select2.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/backoffice/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/backoffice/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/backoffice/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/backoffice/plugins/notifications/css/lobibox.min.css" rel="stylesheet"/>
    
    <!-- theme -->
    <link href="<?php echo base_url();?>assets/backoffice/css/dark-theme.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/backoffice/css/header-colors.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- datepicker & date range picker -->
    <link href="<?php echo base_url();?>assets/backoffice/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/backoffice/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- tags -->
    <link href="<?php echo base_url();?>assets/backoffice/plugins/jquery-tags-input/jquery.tagsinput.min.css" rel="stylesheet">

    <!-- summernote -->
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/backoffice/plugins/summernote/summernote.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- style -->
    <link href="<?php echo base_url();?>assets/backoffice/css/style.css" rel="stylesheet">

    <!-- jquery -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/jquery/jquery.min.js" type="text/javascript"></script>

    <!-- select2 -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/select2/js/select2.min.js" type="text/javascript"></script>
    
    <!-- perfect scrollbar -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/perfect-scrollbar/js/perfect-scrollbar.js" type="text/javascript"></script>

    <!-- jquery validate -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/jquery-validation/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/backoffice/plugins/jquery-validation/additional-methods.js" type="text/javascript"></script>

    <!-- plugins -->
    <script src="<?php echo base_url();?>assets/backoffice/js/plugins.js"></script>

    <!-- custome -->
    <script src="<?php echo base_url();?>assets/backoffice/js/custome.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php echo view('admin/component/csrf_handle'); ?>

        <?php if ($page == 'admin/auth/login_verify' OR $page == 'admin/auth/login' OR $page == 'admin/report/invoice' OR $page == 'privacy_policy') { ?>
            <?php echo view($page); ?>
        <?php } else { ?>

            <!-- navigation -->
            <?php include 'component/navigation.php'; ?>
            
            <div class="page-content-wrapper">
                <div class="page-content">

                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3"><?php echo $meta_title; ?></div>
                    </div>

                    <?php echo view($page); ?>
                </div>
            </div>

        <?php } ?>
    </div>

    <!-- simplebar -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/simplebar/js/simplebar.min.js"></script>

    <!-- metismenu -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/metismenu/js/metisMenu.min.js"></script>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <!-- <script defer src="<?php echo base_url();?>assets/backoffice/plugins/bootstrap/js/bootstrap.min.js"></script> -->
    
    <!-- datepicker & date range picker -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url();?>assets/backoffice/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url();?>assets/backoffice/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>

    <!-- ionicon -->
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>

    <!-- chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <!-- <script src="<?php echo base_url();?>assets/backoffice/plugins/chartjs/chart.min.js"></script> -->
    
    <!-- datatables -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>assets/backoffice/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

    <!-- bootstrap-fileupload -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>

    <!--notification js -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/notifications/js/lobibox.min.js"></script>
    <script src="<?php echo base_url();?>assets/backoffice/plugins/notifications/js/notifications.min.js"></script>
    <script src="<?php echo base_url();?>assets/backoffice/plugins/notifications/js/notification-custom-script.js"></script>

    <!-- summernote -->
    <!-- <script src="<?php echo base_url();?>assets/backoffice/plugins/summernote/summernote-bs4.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- main js -->
    <script src="<?php echo base_url();?>assets/backoffice/js/main.js"></script>

    <!-- tags-input -->
    <script src="<?php echo base_url();?>assets/backoffice/plugins/jquery-tags-input/jquery.tagsinput.min.js"></script>
    
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        window.setTimeout(function() {
            $(".alert-fade").fadeTo(800, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
</body>
</html>

