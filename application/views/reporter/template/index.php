<?php $this->load->view($this->config->item('rpt_template') .'head') ?>
<div id="wrapper">
 <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

         <!-- Topbar -->
        <nav class="navbar navbar-expand topbar mb-4 static-top ">

          <a class="d-flex align-items-center justify-content-center" href="<?php echo base_url();?>" id="bussiness-logo"> 
    		 <div class="sidebar-brand-icon rotate-n-15">
	           <img src="<?php echo base_url('assets/reporter/img/logo.png'); ?>" alt='bussiness logo' title="bussiness logo"> 
		         </div>
	         <div class="sidebar-brand-text mx-3"> <?php echo  $this->session->has_userdata('project') ? $this->session->userdata('project') : $this->config->item('app_name');?></div> 
	        
	       </a> 
          

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

			 <div class="topbar-divider d-none d-sm-block"></div>
            
              <!-- Top Menu Items -->
              <?php $this->load->view( $this->config->item('rpt_template') .'user_menu'); ?>

          </ul>

        </nav>
        <!-- End of Topbar -->
        

        <div class="container-fluid">

		  <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-900"><?php if(isset($title_page)){ echo $title_page; } ?></h1>
           
          </div>
          
          <?php if(isset($breadcrumb)){ ?>
            <ol class="breadcrumb">
                <?php 
                $firstElem = true;
                foreach( $breadcrumb as $section ){ 
                    if($firstElem) { echo '<i class="fa fa-fw fa-home"></i>'; $firstElem = false; }
                    echo "<li>";
                    if(isset($section['link'])){ echo '<a href="'. $section['link'].'">'; } ?>
                        <?php echo $section['title']; ?> <i class="fa fa-fw fa-chevron-right"></i>
                    <?php if(isset($section['link'])){ echo '</a></li>'; }
                }
                echo "<li>$title_page</li>";?>
            </ol>
        <?php } ?>
           <!-- Content Row -->
          <div class="row">
          	<?php

            $gridView = isset($main_content) ? $main_content : ''; //:todo falta definir el else de la vista
            $this->load->view($gridView);

            ?>
          </div>
          
         </div>
         <!--  END FLUID -->
       </div>
    	<!-- End main content -->   
	    
	    <!-- Footer -->
	    <?php $this->load->view($this->config->item('rpt_template') .'footer'); ?>
	 	<!-- End of Footer -->   

	</div>
	<!--  End content wrapper -->
	 
</div>    
<!-- End page wrapper -->
 <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
<!-- Js and aditional resources -->
<?php 
    if(isset($table)){
        echo '<script type="text/javascript" src="'. base_url($this->config->item('rpt_assets') . 'js/grid.js') .'"></script>';
    }

    // load custom js files
    if(isset($js_files)){
        foreach($js_files as $file){
            echo "<script type='text/javascript' src='{$file}'></script>";
        }
    } ?>

    <?php if($this->session->flashdata('message')){
        $type_message = $this->session->flashdata('type_message');
    ?>
        <script type="text/javascript">
            $(function() {
            var flashType = "<?php echo ( isset( $type_message ) ? $type_message : 'info'); ?>";
            var flashMessage = "<?php echo $this->session->flashdata('message');?>";
            showAlert(flashType, flashMessage);
            });
        </script>
    <?php } ?>
    
</body>

</html>
