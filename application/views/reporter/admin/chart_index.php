<div class="col-sm-12" >
<br>
<p>
    <a href="<?php echo site_url('admin/chart/add'); ?> " class="btn btn-primary">
       <i class="fa fa-fw fa-plus"></i> Add Chart</a>
</p>
<table id="datatable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
           <?php
           foreach ($data['columns'] as $value) {
                echo "<th>{$value['dt']}</th>";
           }
           ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <?php
           foreach ($data['columns'] as $value) {
                echo "<th>{$value['dt']}</th>";
           }
           ?>
        </tr>
    </tfoot>
</table>
</div>

<script type="text/javascript">
  var elementId = 'grid';
  var pageView = 'admin/chart';
  var columns_datables = [
              <?php
                  $columns = '';
                  foreach ($data['columns'] as $value) {
                   $columns .= '{"data" : "'.$value['db'].'"},';
                  }
                  echo substr($columns, 0,-1);
              ?>
          ];

</script>