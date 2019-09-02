<!DOCTYPE html>
<html lang="<?php echo ($this->config->item('language') == 'english') ? 'en' : 'es'; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Jorge Luis Copia Silva">

    <title><?php echo isset($title_page) ? $title_page : 'Reporter System' ?> </title>
  	<link href="<?php echo base_url('assets/reporter/css/font.css'); ?>" rel="stylesheet">
  	<link href="<?php echo base_url('assets/reporter/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
  	<link href="<?php echo base_url('assets/reporter/css/main.css'); ?>" rel="stylesheet">
  	<link href="<?php echo base_url('assets/libs/pnotify/PNotifyBrightTheme.css');?>" rel="stylesheet" type="text/css">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row" style="heigth: 500px">
              <div class="col-lg-6 d-none d-lg-block reporter-login-jpg"></div>
              <div class="col-lg-6">
                <div class="p-5" style="margin: 50px 0; ">
                  <div class="text-center">
                    <h1 class="h2 text-gray-900 mb-4">Welcome to <?php echo $this->config->item('app_name'); ?> </h1>
                  </div>
                  <form role="form" action="<?php echo site_url('auth/login');?>" method="post">
                    <div class="form-group">
                      <input type="text" name='username' class="form-control form-control-user"  placeholder="username">
                    </div>
                    <div class="form-group">
                      <input type="password" name='password' class="form-control form-control-user"  placeholder="Password">
                    </div>
                    <input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
                    
                  </form>
                  
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</body>
<script src="<?php echo base_url('assets/libs/jquery/jquery.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/pnotify/iife/PNotify.js');?>"></script>
<script src="<?php echo base_url('assets/reporter/js/main.js');?>"></script>
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
</html>