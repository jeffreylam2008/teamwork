<!DOCTYPE html>
<html>
    <head>
        <title><?=$title; ?></title>
        <link rel="stylesheet" href="<?=base_url('/assets/css/bootstrap.css');?>">
        <link rel="stylesheet" href="<?=base_url('/assets/css/style.css');?>">
        <link rel="stylesheet" href="<?=base_url('/assets/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.css');?>">
        <!-- validate plugin 1.17.0 -->
        <link rel="stylesheet" href="<?=base_url('/assets/css/css/screen.css');?>">
        

        <!-- import jquery -->
        <script src="<?=base_url('/assets/js/jquery-3.3.1.min.js');?>"></script>
        
        <!-- Format and pagination table -->
        <script src="<?=base_url('/assets/js/jquery.dataTables.min.js');?>"></script>
        <script src="<?=base_url('/assets/js/dataTables.bootstrap4.min.js');?>"></script>

        <!-- jquery page auto save --> 
        <script src="<?=base_url("/assets/js/sisyphus.min.js");?>"></script>
        <!-- require for dropdown button -->
        <script src="<?=base_url("/assets/js/popper.min.js");?>"></script>
        <script src="<?=base_url("/assets/js/bootstrap.js");?>"></script>
        <!-- jquery quicksearch -->
        <script src="<?=base_url("/assets/js/jquery.quicksearch.js");?>"></script>
        <!-- mustache 2.3.0-->
        <script src="<?=base_url("/assets/js/mustache.min.js");?>"></script>
        <!-- validate plugin 1.17.0 -->
        <script src="<?=base_url("/assets/js/jquery.validate.min.js");?>"></script>
        <script src="<?=base_url("/assets/js/additional-methods.min.js");?>"></script>

    </head>
    <body>
        <?=$topNav_view; ?>
        <div class="wrapper">
            <!--<div class="row">
                <div class="col-sm-2">-->
                 <!-- menu section -->
                <?php echo $sideNav_view; ?>
                <!--</div>-->
                <!-- content section -->
                <div id="diy-content">
                <!-- <div class="col-sm-10">-->
        