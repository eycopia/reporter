<?php if(isset($report->moreReports )){ ?>
<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="menu-project-report" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-fw fa-plus-square"></i>
        <span class="mr-2 d-none d-lg-inline small">More Reports</span>
    </a>
    
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"  aria-labelledby="menu-project-report">
        
        <?php
        foreach($report->moreReports as $link){
            if($link->idReport == $report->idReport) {continue;}
            echo "<a  class='dropdown-item' href='".site_url("report/grid/{$link->idReport}/{$link->idProject}")."'>"
                 ."<i class='fa fa-fw fa-link'></i> {$link->title}</a>";
        }
        ?>
        
    </div>
</li>
<?php } ?>


<?php if(!is_null($this->reporter_auth->get_user_id())){ ?>
<!-- Nav Item - User Information -->
<li class="nav-item dropdown no-arrow">

<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-fw fa-cog"></i>
    <span class="mr-2 d-none d-lg-inline small"><?php echo $this->lang->line('menu_admin'); ?></span>
</a>
  
<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
    <?php if( $this->reporter_auth->isAdmin() ) { ?>
    <a class="dropdown-item" href="<?php echo site_url('admin/users'); ?>"><i class="fa fa-fw fa-users"></i> <?php echo $this->lang->line('menu_users'); ?></a>
    <a class="dropdown-item" href="<?php echo site_url('admin/project'); ?>"><i class="fa fa-fw fa-desktop"></i> <?php echo $this->lang->line('menu_project'); ?></a>
    <a class="dropdown-item" href="<?php echo site_url('admin/report'); ?>"><i class="fa fa-fw fa-table"></i> <?php echo $this->lang->line('menu_report'); ?></a>
    <a class="dropdown-item" href="<?php echo site_url('admin/Notify_Report'); ?>"><i class="fa fa-fw fa-paper-plane"></i>Notificar</a>
     <?php } if( $this->reporter_auth->isDeveloper()) { ?>
    <a class="dropdown-item" href="<?php echo site_url('admin/server'); ?>"><i class="fa fa-fw fa-server"></i> <?php echo $this->lang->line('menu_server'); ?></a>
    <a class="dropdown-item" href="<?php echo site_url('admin/components'); ?>"><i class="fa fa-fw fa-plug"></i> <?php echo $this->lang->line('menu_component'); ?></a>
	 <?php }  ?>
    <a class="dropdown-item" href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-fw fa-sign-out-alt"></i><?php echo $this->lang->line('menu_logout'); ?></a>
</div>
</li>
<?php } ?>
