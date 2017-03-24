<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Jorge Copia">

    <title><?php echo isset($title_page) ? $title_page : 'Generador de Reportes' ?> </title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url('assets/libs/bootstrap-3.3.6/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo  base_url('assets/libs/select2/css/select2.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/libs/bootstrap-3.3.6/css/bootstrap-datetimepicker.min.css');?>" rel="stylesheet">


    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url('assets/css/plugins/morris.css');?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url('assets/libs/bootstrap-3.3.6/fonts/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="<?php echo base_url('assets/libs/datatables1.10.10/css/dataTables.bootstrap.css');?>" rel="stylesheet" type="text/css">

    <!-- Grocery files -->
    <?php
    if(isset($css_files)){
        foreach($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php  endforeach; }?>

    <!-- Custom CSS -->
    <link href="<?php echo base_url('assets/css/sb-admin.css');?>" rel="stylesheet">

</head>
<body>
<!-- jQuery -->
<script type="text/javascript">
    var app_url = "<?php echo site_url(); ?>";
</script>
<script src="<?php echo base_url('assets/js/jquery.js');?>"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url('assets/libs/select2/js/select2.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap-3.3.6/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap-3.3.6/js/moment.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap-3.3.6/js/bootstrap-datetimepicker.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables1.10.10/js/jquery.dataTables.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables1.10.10/js/dataTables.bootstrap.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables1.10.10/extras/Buttons/js/dataTables.buttons.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables1.10.10/extras/Buttons/js/buttons.bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/jszip.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables1.10.10/extras/Buttons/js/buttons.colVis.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables1.10.10/extras/Buttons/js/buttons.html5.min.js');?>"></script>

<?php
// necesario para crud
if(isset($js_files)){
    foreach($js_files as $file){
        echo "<script type='text/javascript' src='{$file}''></script>";
    }
} ?>

<div id="wrapper">

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url();?>"><i class="fa fa-fw fa-database"></i>
                <?php
                echo  APP_NAME;
                ?> </a>
        </div>

        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->

        <!-- /.navbar-collapse -->
    </nav>



    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?php echo $heading; ?>
                    </h1>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div id="container">
                   <div class="alert alert-warning"><?php echo $message; ?></div>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<?php
// Agrega js personalizados
if(isset($custom_js_files)){
    foreach($custom_js_files as $file){
        echo "<script type='text/javascript' src='{$file}'></script>";
    }
}
?>
</body>

</html>

