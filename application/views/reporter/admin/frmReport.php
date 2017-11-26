<style>
form   {
    clear: both;
    margin-bottom: 150px;
}
    footer{
        bottom: 0;
        position: relative;
    }
</style>
<form class="form-horizontal col-sm-9 center-block" action="<?php echo site_url('admin/report/save'); ?> " method="POST"  style="float:none">
    <?php if(isset($report)){
          echo "<input name='report' value='{$report->idReport}' type='hidden'>";
    } ?>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#information" aria-controls="information" role="tab" data-toggle="tab">Details</a></li>
    <li role="presentation" class=""><a href="#sqlReport" aria-controls="sqlReport" role="tab" data-toggle="tab">SQL</a></li>
    <li role="presentation" class=""><a href="#customice" aria-controls="customice" role="tab" data-toggle="tab">Custom Grid</a></li>
    <li role="presentation" class=""><a href="#notify" aria-controls="notify" role="tab" data-toggle="tab">Notifications</a></li>
    <?php if(isset($report)) {
        $path = (empty($report->url)) ? "report/grid/{$report->idReport }" : $report->url;
        $link = site_url($path);
        echo "<li><a href='{$link}'
             class='btn btn-success btn-preview' target='_blank'>
            <i class='fa fa-fw fa-external-link'></i>
            {$this->lang->line('show')} {$this->lang->line('report')}</a></li>";
    }?>
    <li role="" class=""><a href="<?php echo site_url('admin/report/add'); ?>" class="btn btn-primary btn-preview">
            <span class="fa fa-plus-circle"></span>
            <?php echo $this->lang->line('btn_new_report'); ?></a></li>
</ul>

<div class="hidden alert alert-danger alert-dismissible fade in" role="alert" id="errorValidation">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <strong>Validation Erros!</strong>
    <div id="textValidation"></div>
</div>

<div class="tab-content">
 <div role="information" class="tab-pane active" id="information">
  <div class="col-lg-12 col-sm-12">
        <div class="form-group">
            <div class="">
                <label  class="control-label">Title</label>
                <input name="title" type="title" class="form-control" placeholder="Title"
                    <?php if(isset($report)){ echo "value = \"$report->title\""; } ?>
                >
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label  class="control-label">Project</label>
                <select name="project" class="form-control">
                    <option>---Select-----</option>
                    <?php
                    foreach ($projects as $project) {
                        if(isset($report) && $report->idProject==$project->idProject){
                            echo "<option value='{$project->idProject}' selected='selected'>{$project->name}</option>";
                        }else{
                            echo "<option value='{$project->idProject}'>{$project->name}</option>";
                        }
                    }?>
                </select>
            </div>
            <div class="col-sm-6">
                <label  class="control-label">Server Connection</label>
                <select name="connection" class="form-control">
                    <option>---Select-----</option>
                    <?php
                    foreach ($servers as $server) {
                        if(isset($report) && $report->idServerConnection==$server->idServerConnection) {
                            echo "<option value='{$server->idServerConnection}' selected='selected'>{$server->name}</option>";
                        }else{
                            echo "<option value='{$server->idServerConnection}'>{$server->name}</option>";
                        }
                    }?>
                </select>
            </div>
        </div>

      <div class="form-group">
          <div class="">
              <label class="control-label">Description</label>
              <textarea name="description" class="form-control" placeholder="Why you create this report?"><?php echo isset($report->description) ? $report->description : '';  ?></textarea>
          </div>
      </div>

      <div class="form-group">
            <div class="">
                <label class="control-label">Leyend for the report</label>
                <textarea id="details" class="form-control">
                    <?php echo isset($report->details) ? $report->details : '';  ?>
                </textarea>
            </div>
      </div>

  </div>
 </div>
 <div role="sqlReport" class="tab-pane" id="sqlReport">
   <div class="col-lg-12 col-sm-10 center-block " style="float:none">
       <div class="form-group">
           <div class="">
               <label class="control-label">Custom Report</label>
                   <input name="url" class="form-control" placeholder="Put url if you have a custom url"
                          value="<?php echo isset($report->url) ? $report->url : '';  ?>">

           </div>
       </div>
       <div class="form-group">
           <div class="">
               <label class="control-label">Query Sql</label>
               <div class="form-control" id="sql"><?php if(isset($report->sql)){
                       echo $report->sql; } ?> </div>
           </div>
           <br>
           <button id="btnConfigureVars" class="btn btn-success">Configure Vars</button>
       </div>

       <div class="form-group <?php if(isset($vars) && count($vars) == 0) echo "hidden"; ?>" id="configureVars">
           <h4>Configure Vars of SQL</h4>
           <div class="row">
               <div class="col-sm-3 form-label">Variable</div>
               <div class="col-sm-3 form-label">Tipo de Variable</div>
               <div class="col-sm-3 form-label">Valor por defecto</div>
           </div>
           <div class="" id="sqlVars">
               <?php if( isset($vars) && count($vars) > 0 ){ ?>
              <?php foreach ($vars as $var) { ?>

                   <div class="row"><div class="col-sm-3">
                           <label class="form-label"><?php echo $var->name; ?></label>
                   </div>
                   <div class="col-sm-3">
                       <select class="form-control selectVars" id="<?php echo $var->name; ?>_select">
                           <?php foreach($type_vars as $type ){
                               if($var->idVarType == $type->idVarType){
                                   echo "<option value='{$type->idVarType}' selected='selected'>{$type->name}</option>";
                               }else{
                                   echo "<option value='{$type->idVarType}'>{$type->name}</option>";
                               }
                           } ?>
                       </select>
                   </div>
                       <div class="col-sm-3"> <input value='<?php echo $var->default ; ?>' class="form-control" id="<?php echo $var->name; ?>_default"></div></div>
               <?php } }//end foreach and if ?>
           </div>
       </div>
   </div>
 </div>
 <div role="customice" class="tab-pane" id="customice">
        <div class="col-lg-12 col-sm-10 center-block " style="float:none">
            <div class="form-group">
                <br>
                <div class="col-sm-3">
                    <div class="col-sm-4">
                        <label  class="control-label">Items:</label>
                        </div>
                    <div class="col-sm-8"><input name="items" class="form-control" type="number"
                              value="<?php echo  isset($report->items_per_page)? $report->items_per_page : 10;?>">
                    </div>
                </div>
                <div class="col-sm-4">
                    <label  class="control-label">Auto reload:</label>

                        <input name="reload" type="radio" value="1"
                            <?php echo ( isset($report->auto_reload) && $report->auto_reload == 1) ? "checked='checked' autocomplete=\"off\"" : ''; ?>
                        > Si
                        <input name="reload" type="radio" value="0"
                            <?php echo (isset($report->auto_reload) && $report->auto_reload == 0) ? "checked='checked' autocomplete=\"off\"" : ''; ?>
                        > No

                </div>
                <?php if(isset($columns)){ ?>
                <div class="col-sm-12">
                    <p><br></p><h4>Configure Columns:</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>DB Name</th>
                            <th>Table/Alias Table</th>
                            <th>Grid Name</th>
                            <th>Display on Grid</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($columns as $column){
                            $checked = (isset($column['show']) && $column['show'] == 1 ) ? 'checked="checked"' : '' ;
                          echo "<tr class='grid_column'><td>{$column['db']}</td>
                                    <td>
                                    <input value ='{$column['db']}' class='column_name' type='hidden'>
                                    <input value ='{$column['table']}' class='form-control alias_table'
                                      type='text' placeholder='Coloca el nombre de la tabla donde esta declarada esta columna'/>
                                    </td>
                                    <td><input type='text' value='{$column['dt']}' class='form-control alias_column'/></td>
                                    <td><input type='checkbox'  {$checked} value='1' autocomplete='off' class='form-control show_column'/></td>
                                </tr>";
                        }?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
</div>
    <div role="notify" class="tab-pane" id="notify">
        <div class="form-group">
            <div class="col-sm-4">
                <label  class="control-label">Format for email:</label>
                <select name="format_notify" class="form-control" >
                    <?php
                        $formats = array('Excel', 'HTML');
                        foreach($formats as $f){
                            $selected = (isset($report->format_notify) && $report->format_notify == strtolower($f)) ? 'selected="selected"' : '';
                            echo "<option $selected  value='".strtolower($f)."'>$f</option>";
                        }
                    ?>
                    ?>


                </select>
            </div>
            <div class="col-sm-12">
                <label  class="control-label">Select emails to notify:</label>
                <select name="emails" multiple="multiple" id="select_emails" class="form-control" style="width: 100%;">
                    <?php foreach($people as $p){
                        echo "<option value='$p->idNotify' selected='selected'>$p->full_name $p->email</option>";
                    } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary pull-right" id="btn-save">Save</button>
        </div>
    </div>

</form>

<script type="text/javascript">
    var app_url = '<?php echo base_url('admin'); ?>';
    //Available Type of Vars for Report
    var varTypes = [ <?php

        foreach($type_vars as $var){
            echo "{ name : '{$var->name}', id: '{$var->idVarType}'},";
        } ?>
    ];
</script>