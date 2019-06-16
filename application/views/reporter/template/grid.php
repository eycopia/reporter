<?php if(isset($report->moreReports )){ ?>
<div id="menu_report" class="nav top-nav">
    <div class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-fw fa-table"></i> <?php echo $report->current_project->name; ?> <b class="caret"></b></a>
    <ul class="dropdown-menu alert-dropdown">
        <?php
        foreach($report->moreReports as $link){
            if($link->idReport == $report->idReport) {continue;}
            echo "<li> <a href='".site_url("report/grid/{$link->idReport}/{$link->idProject}")."'>"
                 ."<i class='fa fa-fw fa-link'></i> ".$link->title
                ."</a></li>";
        }
        ?>
    </ul>
    </div>
</div>
<?php } ?>
<?php if(isset($table['links'])){ ?>
<div class="col-sm-12" >
    <br>
    <?php
        foreach($table['links'] as $link){
            echo "<a href='{$link['fileExtension']}' class='{$link['nameClass']}'>{$link['fileName']}</a>";
        }
    ?>
    <hr>
</div>
<?php } ?>
<div class="col-sm-12" >
<?php if(isset($report->details) && strlen($report->details) > 0 ) { ?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <i class="fa fa-fw fa-plus-circle"> </i>
                        <?php echo $this->lang->line('collapse_legent');?>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <?php echo $report->details; ?>
                </div>
            </div>
        </div>
    </div>

<?php }

if(isset($report->resource) && $report->resource == 'embedded'){
    echo " <iframe src='{$report->url}' height='800px' width='100%'></iframe> ";
}else {?>

    <div class="form-group col-sm-16">
        <form>
        <?php
         if(isset($table['filters'])){ $this->load->view($this->config->item('rpt_template') . 'grid_filters');}
         if(isset($table['utilities'])){ $this->load->view($this->config->item('rpt_template') . 'grid_utilities');}
        ?>
            <input type="reset" class="hidden">
        </form>
    </div>



    <table id="datatable" class="table table-striped table-bordered table-hover table-blue-head" cellspacing="0" width="100%">
        <thead>
            <tr><?php
               foreach ($table['columns'] as $value) {
                   if( (!isset($value['show']) ) OR
                        (isset($value['show']) && $value['show'] == true)
                   ){
                        echo "<th>{$value['dt']}</th>";
                   }
               }
               ?>
            </tr>
        </thead>
    </table>
<?php } ?>
    <script type="text/javascript">
        var data_url = "<?php echo $table['data_url']; ?>";
        var active_pagination = "<?php echo (isset($report->pagination)) ? $report->pagination : 1 ; ?>";
        var items_per_page = <?php echo isset($table['utilities']['items_per_page']) ? $table['utilities']['items_per_page'] : $this->config->item('grid_items_per_page'); ?>;
        var auto_reload= <?php echo isset($table['utilities']['auto_reload']) ? $table['utilities']['auto_reload'] : 0;?>;
        var columns_datables = [
            <?php
            $columns = '';
            foreach ($table['columns'] as $value) {
                if( (!isset($value['show']) ) OR
                    (isset($value['show']) && $value['show'] == true)
                ){
                    $columns .= '{"data" : "' . $value['dt'] . '"},';
                }
            }
            echo substr($columns, 0,-1);
            ?>
        ];
        var text_button_download_view = "<?php echo $this->lang->line('button_download_view'); ?>";
        var text_button_columns_view = "<?php echo $this->lang->line('button_columns_view'); ?>";
        $('.date').datetimepicker({ 'sideBySide': true, format : 'YYYY-MM-DD'});
        $('.datetime').datetimepicker({ 'sideBySide': true, format : 'YYYY-MM-DD HH:mm:ss'});
    </script>

</div>


