<?php $this->load->view($this->config->item('rpt_template') .'head') ?>
    <div id="wrapper">

        <nav class="navbar navbar-inverse" role="navigation">
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
                    echo  $this->session->has_userdata('project') ? $this->session->userdata('project') : $this->config->item('app_name');
                    ?> </a>
            </div>
            <!-- Top Menu Items -->
            <?php $this->load->view( $this->config->item('rpt_template') .'user_menu'); ?>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            
            <!-- /.navbar-collapse -->
        </nav>



        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                            if(isset($title_page)){
                                echo "<h1 class='page-header'> $title_page</h1>";
                            }
                        ?>
                        <?php if(isset($breadcrumb)){ ?>
                            <ol class="breadcrumb">
                                <?php foreach( $breadcrumb as $section ){  ?>
                                <li>
<!--                                    <i class="fa fa-dashboard"></i>-->
                                   <?php if(isset($section['link'])){ echo '<a href="'. $section['link'].'">'; } ?>
                                        <?php echo $section['title']; ?>
                                    <?php if(isset($section['link'])){ echo '</a>'; } ?>
                                </li>
                                <?php } ?>
                            </ol>
                        <?php } ?>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <?php

                    $gridView = isset($main_content) ? $main_content : ''; //:todo falta definir el else de la vista
                    $this->load->view($gridView);

                    ?>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>

    <!-- /#wrapper -->
    <?php
    $this->load->view($this->config->item('rpt_template') .'footer');
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
