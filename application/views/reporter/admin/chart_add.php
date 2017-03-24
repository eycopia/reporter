<br>
<div class="col-sm-10">
<form class="form-horizontal" action="<?php echo site_url('admin/chart/add'); ?>" method="POST">
    <div class="form-group">
    <label  class="col-sm-2 control-label">Project</label>
    <div class="col-sm-10">
    <select name="idProject" class="form-control"> 
        <option value='------'>---Select-----</option>
        <?php 
        foreach ($projects as $project) {
            echo "<option value='{$project->idProject}'>{$project->name}</option>";
        }?>
    </select>
    </div>
  </div>
  <div class="form-group">
    <label  class="col-sm-2 control-label">Title</label>
    <div class="col-sm-10">
      <input name="title" type="title" class="form-control" placeholder="Title">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
      <textarea id="description" name="description"></textarea>
    </div>
  </div>  
  <div class="form-group">
  <label class="col-sm-2 control-label"></label>
    <div class="col-sm-10">
      <div class="col-sm-5">
        <label>Reports</label>  
        <select id="select-report" class="form-control">
            <option value="select">----------</option>
        </select>
      </div>
      <div class="col-sm-4">
        <label>Charts</label>  
        <select class="form-control" id="select-typechart"> 
            <option value='salect'>---Select-----</option>
        <?php 
            foreach ($typesCharts as $type) {
                echo "<option value='{$type->idTypeChart}'>{$type->name}</option>";
            }
        ?>
        </select>
      </div>
      <div class="col-sm-2">
        <label></label>
        <button class="btn btn-success" id="add-report">Agregar</button>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">Charts</label>
    <div class="col-sm-10" id="panel-reports">
        <!-- <div class="charts-report panel panel-primary col-sm-4">
            <div class="panel-body">
             Reporte 1, grafico PIE
            </div>
        </div> -->
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </div>
</form>
</div>