<!DOCTYPE html>
<html>
    <head>
        <title><?=$title; ?></title>
        <!-- JS -->
        <!-- import jquery -->
        <script src="<?=base_url('/assets/js/jquery-3.4.1.min.js');?>"></script> 
        
        <!-- Format and pagination table -->
        <script src="<?=base_url('/assets/js/jquery.dataTables.min.js');?>"></script>
        <script src="<?=base_url('/assets/js/bootstrap.datatables.min.js');?>"></script>

        <!-- jquery page auto save --> 
        <!--<script src="<?=base_url("/assets/js/sisyphus.min.js");?>"></script>-->
        <!-- require for dropdown button -->
        
        <script src="<?=base_url("/assets/js/bootstrap-4.0.0.min.js");?>"></script>
        <script src="<?=base_url("/assets/js/popper.min.js");?>"></script>

        <!-- jquery quicksearch -->
        <script src="<?=base_url("/assets/js/jquery.quicksearch.js");?>"></script>
        <!-- mustache 2.3.0
        <script src=""></script>-->
        <!-- validate plugin 1.17.0 -->
        <script src="<?=base_url("/assets/js/jquery.validate-1.19.1.min.js");?>"></script>
        <script src="<?=base_url("/assets/js/additional-methods.min.js");?>"></script>

        <script src="<?=base_url('/assets/js/bootstrap.datepicker.min.js');?>"></script>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="<?=base_url('/assets/css/bootstrap.css');?>">
        <link rel="stylesheet" type="text/css" href="<?=base_url('/assets/css/jquery.dataTables.css');?>">
        <link rel="stylesheet" type="text/css" href="<?=base_url('/assets/css/bootstrap-datepicker.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?=base_url('/assets/fontawesome-free-5.13.0-web/css/all.min.css');?>">
        <!--  validate plugin 1.17.0 -->
        <link rel="stylesheet" type="text/css" href="<?=base_url('/assets/css/css/screen.css');?>">
        <link rel="stylesheet" type="text/css" href="<?=base_url('/assets/css/style.css');?>">

    </head>
    <body>
        
        <?=$topNav_view; ?>

        <div class="wrapper">
            <!--div class="row">
                <div class="col-2">-->
                    <!-- menu section -->
                    <?php echo $sideNav_view; ?>

                <!--    <div class="col-10">-->
                    <!-- content section -->
                    <div id="diy-content">

               
                <!-- <div class="col-sm-10">-->
        