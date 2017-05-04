<?php
$CI =& get_instance();
if( ! isset($CI))
{
	$CI = new CI_Controller();
}
$CI->load->helper('url');
include_once  APPPATH ."views/". $CI->config->item('rpt_template') .'head.php' ;
?>

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
            <a class="navbar-brand" href="<?php echo base_url() ;?>"><i class="fa fa-fw fa-database"></i>
                <?php
                echo  $CI->config->item('app_name');
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
					<div class="alert alert-danger"><?php echo $message; ?></div>
				</div>
			</div>
			<!-- /.row -->

		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

</body>

</html>

