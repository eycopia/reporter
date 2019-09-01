<?php 
$searchOptions = '';
for ($i=0; $i < count($table['columns']); $i++ ) {
    if(isset($table['columns'][$i]['table']) && !empty($table['columns'][$i]['table'])){
        $searchOptions .= "<option value='{$i}'> {$table['columns'][$i]['dt']}</option>";
    }
}
?>

<style>
    .select2-container .select2-selection--multiple{
        max-height: 34px!important;
        overflow-y: hidden !important;
        border: 1px solid #aaa;
        clear: right;
        box-shadow: 0 0 2px rgba(0,0,0,.3) inset;
    }
</style>


<div class="row">
    <?php
    $btnSearch = false;
    if(!$table['avoid_basic_filter'] && !empty($searchOptions)){
        $btnSearch = true;  ?>
    <div class="col-sm-3">
        <label class="form-label"><?php echo $this->lang->line('label_search'); ?>:</label>
        <input type="text" id="searchBox" class="form-control" value=""
               placeholder="<?php echo $this->lang->line('input_search'); ?>">
    </div>
    <div class="col-sm-2">
        <label class="form-label"><?php echo $this->lang->line('label_search_by'); ?>:</label>
        <select  id="searchBy" class="form-control">            
            <?php echo $searchOptions; ?>
        </select>
    </div>
    <?php }
    if(is_array($table['filters'])) foreach ($table['filters'] as $filter) {
        $btnSearch = true;
        $this->load->view($table['viewsFilters'][$filter['type']], array('filter' => $filter));
    }
    if($btnSearch){    ?>
    <div class="col-sm-1 row align-items-end">
        	<button class="btn btn-primary" id="runSearch">
            <i class="fa fa-search"></i> <?php echo $this->lang->line('button_search'); ?></button>
    </div>
    <?php } ?>
</div>
