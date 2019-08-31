<?php if(!is_null($this->reporter_auth->get_user_id())){ ?>
<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-fw fa-cog"></i>
    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $this->lang->line('menu_admin'); ?></span>
</a>
  
<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
    <?php if( $this->reporter_auth->isAdmin() ) { ?>
    <a class="dropdown-item" href="<?php echo site_url('admin/users'); ?>"><i class="fa fa-fw fa-users"></i> <?php echo $this->lang->line('menu_users'); ?></a>
    <a class="dropdown-item" href="<?php echo site_url('admin/project'); ?>"><i class="fa fa-fw fa-desktop"></i> <?php echo $this->lang->line('menu_project'); ?></a>
    <a class="dropdown-item" href="<?php echo site_url('admin/report'); ?>"><i class="fa fa-fw fa-table"></i> <?php echo $this->lang->line('menu_report'); ?></a>
    <a class="dropdown-item" href="<?php echo site_url('admin/Notify_Report'); ?>"><i class="fa fa-fw fa-paper-plane"></i>Notificar</a>
     <?php } if( $this->reporter_auth->isDeveloper()) { ?>
    <a class="dropdown-item" href="<?php echo site_url('admin/server'); ?>"><i class="fa fa-fw fa-server"></i> <?php echo $this->lang->line('menu_server'); ?></a>
     <?php }  ?>
	<a class="dropdown-item" href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-fw fa-sign-out-alt"></i><?php echo $this->lang->line('menu_logout'); ?></a>
</div>
<?php } ?>
