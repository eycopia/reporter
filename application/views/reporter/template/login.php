<?php $this->load->view($this->config->item('rpt_template') .'head') ?>
<div class="container" style="margin-top: 10%">
<div class="row">
<h1 class="text-center">Welcome to <?php echo $this->config->item('app_name'); ?> </h1>
<div class="col-md-4 col-md-offset-4">
<div class="login-panel panel panel-default">
   
    <div class="panel-heading">
        <h3 class="panel-title">Please Sign In</h3>
    </div>
    <div class="panel-body">
        <form role="form" action="auth/autenticate" method="post">
            <fieldset>
                <div class="form-group">
                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                </div>
                <div class="checkbox">
                    <label>
                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                    </label>
                </div>
                <!-- Change this to a button or input when using this as a form -->
                <input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
            </fieldset>
        </form>
    </div>
</div>
</div>
</div>
</div>