<style>
    .select2-container .select2-selection--multiple{
        max-height: 34px!important;
        overflow-y: hidden !important;
        border: 1px solid #aaa;
        clear: right;
        box-shadow: 0 0 2px rgba(0,0,0,.3) inset;
    }
</style>
<div class="col-sm-12">
    <?php
    $btnSearch = false;
    if(!$table['avoid_basic_filter']){
        $btnSearch = true;  ?>
    <div class="col-sm-3">
        <label class="form-label"><?php echo $this->lang->line('label_search'); ?>:</label>
        <input type="text" id="searchBox" class="form-control" value=""
               placeholder="<?php echo $this->lang->line('input_search'); ?>">
    </div>
    <div class="col-sm-2">
        <label class="form-label"><?php echo $this->lang->line('label_search_by'); ?>:</label>
        <select  id="searchBy" class="form-control">
            <option value="all"><?php echo $this->lang->line('option_search_by'); ?></option>
            <?php
            for ($i=0; $i < count($table['columns']); $i++ ) {
                if(isset($table['columns'][$i]['table']) && !empty($table['columns'][$i]['table'])){
                    echo "<option value='{$i}'> {$table['columns'][$i]['dt']}</option>";
                }
            }
            ?>
        </select>
    </div>
    <?php }
    if(is_array($table['filters'])) foreach ($table['filters'] as $filter) {
        $btnSearch = true;
        if($filter['type'] == 'multiple'){
            echo "<div class=\"col-sm-4\"><label class='form-label'>{$filter['label']}:</label><br>";
            $options = explode(',', $filter['value']);
            echo "<select class='form-control {$filter['class']}' multiple name='{$filter['name']}'>";
            foreach($options as $option){
                echo "<option value='{$option}' selected='selected'>$option</option>";
            }
            echo "</select></div>";
        }else if($filter['type'] == 'select'){
            echo "<div class=\"col-sm-2\"><label class='form-label'>{$filter['label']}:</label><br>";
            $options = explode(',', $filter['value']);
            echo "<select class='form-control {$filter['class']}'  name='{$filter['name']}'>";
            echo "<option value='' selected='selected'>Todos</option>";
            foreach($options as $option){
                echo "<option value='{$option}'>$option</option>";
            }
            echo "</select></div>";
        }else{
            ?>
            <div class="col-sm-2">
                <label class='form-label'><?php echo $filter['label'];?>:</label>
                <input type="text" id="<?php echo $filter['name']; ?>" name="<?php echo $filter['name']; ?>"
                       class="form-control <?php echo $filter['class']; ?>" value="<?php echo $filter['value']; ?>"
                       placeholder="<?php echo $filter['label'];?>">
            </div>
        <?php }}
    if($btnSearch){    ?>
    <div class="col-lg-1 col-sm-2">
        <div clas=""></div>
        <label class="form-label clear"><br></label>
        <button class="btn btn-primary" id="runSearch">
            <i class="fa fa-search"></i> <?php echo $this->lang->line('button_search'); ?></button>
    </div>
    <?php } ?>
</div>
