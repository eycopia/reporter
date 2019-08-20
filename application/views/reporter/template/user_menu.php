<?php if(!is_null($this->reporter_auth->get_user_id())){ ?>
<ul class="nav navbar-right top-nav">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-dashboard"></i> <?php echo $this->lang->line('menu_admin'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu alert-dropdown">
          <?php if( $this->reporter_auth->isAdmin() ) { ?>
            <li>
                <a href="<?php echo site_url('admin/users'); ?>"><i class="fa fa-fw fa-users"></i> <?php echo $this->lang->line('menu_users'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/project'); ?>"><i class="fa fa-fw fa-desktop"></i> <?php echo $this->lang->line('menu_project'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/report'); ?>"><i class="fa fa-fw fa-table"></i> <?php echo $this->lang->line('menu_report'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/Notify_Report'); ?>"><i class="fa fa-fw fa-send"></i>Notificar</a>
            </li>
          <?php } if( $this->reporter_auth->isDeveloper()) { ?>
              <li>
                <a href="<?php echo site_url('admin/server'); ?>"><i class="fa fa-fw fa-cloud"></i> <?php echo $this->lang->line('menu_server'); ?></a>
            </li>
          <?php }  ?>
            <li>
                <a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-fw fa-sign-out"></i><?php echo $this->lang->line('menu_logout'); ?></a>
            </li>
        </ul>
    </li>
</ul>
<?php } ?>
