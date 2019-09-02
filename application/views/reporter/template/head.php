<!DOCTYPE html>
<html lang="<?php echo ($this->config->item('language') == 'english') ? 'en' : 'es'; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Jorge Luis Copia Silva">

    <title><?php echo isset($title_page) ? $title_page : 'Reporter System' ?> </title>

	<link href="<?php echo base_url('assets/libs/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
  	<link href="<?php echo base_url('assets/reporter/css/font.css'); ?>" rel="stylesheet">
  	<link href="<?php echo base_url('assets/reporter/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
  	<link href="<?php echo base_url('assets/reporter/css/main.css'); ?>" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo  base_url('assets/libs/select2/css/select2.min.css');?>" rel="stylesheet"> 
  	<link href="<?php echo base_url('assets/libs/bootstrap-4/bootstrap-datetimepicker.min.css');?>" rel="stylesheet">



    <!-- Morris Charts CSS -->
    <!--    <link href="--><?php //echo base_url('assets/css/plugins/morris.css');?><!--" rel="stylesheet">-->

    <!-- Custom Fonts -->
   

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
    <link href="<?php echo base_url('assets/libs/datatables1.10.18/datatables.min.css');?>" rel="stylesheet" type="text/css">
    
    <link href="<?php echo base_url('assets/libs/pnotify/PNotifyBrightTheme.css');?>" rel="stylesheet" type="text/css">
    <!-- Grocery files -->
    <?php
    if(isset($css_files)){
        foreach($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
 <?php  endforeach; }
        if(!empty($this->config->item('app_main_css'))){  ?>
	    <link href="<?php echo base_url($this->config->item('app_main_css'));?>" rel="stylesheet" type="text/css">
	<?php } ?>

</head>
<body id="page-top">
<!-- jQuery -->
<script type="text/javascript">
    var app_url = "<?php echo site_url(); ?>";
</script>
<script src="<?php echo base_url('assets/libs/jquery/jquery.min.js');?>"></script>
<script src="<?php echo base_url('assets/reporter/js/main.js');?>"></script>

<script src="<?php echo base_url('assets/libs/pnotify/iife/PNotify.js');?>"></script>
<script src="<?php echo base_url('assets/libs/pnotify/iife/PNotifyButtons.js');?>"></script>
<script src="<?php echo base_url('assets/libs/pnotify/iife/PNotifyConfirm.js');?>"></script>
<script src="<?php echo base_url('assets/libs/pnotify/iife/PNotifyCallbacks.js');?>"></script>
<script src="<?php echo base_url('assets/libs/popper.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap-4/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/moment.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap-4/bootstrap-datetimepicker.min.js');?>"></script>
<?php if(isset($table)){ ?>
<script src="<?php echo base_url('assets/libs/select2/js/select2.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables1.10.18/datatables.min.js');?>"></script>
<?php } ?>
