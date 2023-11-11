<!DOCTYPE html>
<html class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>
        <?=$title;?>
    </title>
    <link rel="icon" href="<?=$Database->site('LinkIcon');?>">

    <!-- Thư viện js SweetAlert2 để hiển thị pop-up tùy chỉnh -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default/default.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/fontawesome-free/css/all.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Thư viện Tempusdominus Bootstrap 4 để dùng DatePicker, TimePicker, DateTimePicker -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <!-- iCheck jquery để dùng checkbox và radio button -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <!-- Thư viện JQVMap để tạo, định dạng và hiển thị bản đồ thế giới (khu vực) dưới dạng các đối tượng vector -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/jqvmap/jqvmap.min.css">

    <!-- Bộ giao diện người dùng AdminLTE để xây dựng trang admin dashboard -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>dist/css/adminlte.min.css">

    <!-- Plugin OverlayScrollbars cung cấp thanh cuộn tùy chỉnh có khả năng tương thích cao -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/daterangepicker/daterangepicker.css">

    <!-- Summernote là một trình soạn thảo văn bản dựa trên web, cho phép người dùng tạo, chỉnh sửa và định dạng văn bản trực quan. -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/summernote/summernote-bs4.css">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Ekko Lightbox là một plugin JavaScript dùng để tạo và quản lý cửa sổ lightbox, để hiển thị hình ảnh, video hoặc nội dung khác trong một cửa sổ được phóng to -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/ekko-lightbox/ekko-lightbox.css">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

    <!-- DataTables BS4 -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

    <!-- Responesive BS4 -->
    <link rel="stylesheet" href="<?=BASE_URL('template/');?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

    <!-- VanillaToasts -->
    <link href="<?=BASE_URL('template/');?>vanillatoasts.css" rel="stylesheet">
    <script src="<?=BASE_URL('template/');?>vanillatoasts.js"></script>

    <!-- Upload file to cloudinary -->
    <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>

</head>

<?php
// Kiểm tra người dùng có phải admin không?
CheckAdmin();
?>