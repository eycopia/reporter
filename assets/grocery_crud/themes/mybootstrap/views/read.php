<?php
	$this->set_js_lib($this->theme_path.$this->theme.'/js/jquery.form.js');
	$this->set_js_config($this->theme_path.$this->theme.'/js/flexigrid-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="col-sm-12">
<div class="row justify-content-md-center">
<div class="col-lg-6">
<div class="card shadow mb-4">
<div class="card-header py-3">

<h6 class="m-0 font-weight-bold text-primary">
	<?php echo $this->l('list_record'); ?> <?php echo $subject?>
</h6>
                </div>
<div class="card-body">
<div class="col-lg-12" data-unique-hash="<?php echo $unique_hash; ?>">
	
<div id='main-table-box' class="panel-body">
	<?php echo form_open( $read_url, 'method="post" id="crudForm"  enctype="multipart/form-data"'); ?>
	<div class='form-div'>
		<?php
		$counter = 0;
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>
			<div class='row <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='text-right col-sm-4' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> :
				</div>
				<div class='col-sm-4' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>
				<div class='clear'></div>
			</div>
		<?php }?>
		<?php if(!empty($hidden_fields)){?>
		<!-- Start of hidden inputs -->
			<?php
				foreach($hidden_fields as $hidden_field){
					echo $hidden_field->input;
				}
			?>
		<!-- End of hidden inputs -->
		<?php }?>
		<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>
	</div>
	<div class="pDiv">
		<div class='form-button-box'>
			<button type='button'  class="btn btn-large btn-primary back-to-list" id="cancel-button" >
                <i class="fa fa-arrow-left"></i> <?php echo $this->l('form_back_to_list'); ?>
            </button>
		</div>
		<div class='form-button-box'>
			<div class='small-loading' style="display: none" id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>

	</div>
	<?php echo form_close(); ?>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>
