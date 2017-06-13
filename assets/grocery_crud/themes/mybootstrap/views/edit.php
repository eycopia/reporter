<?php
    $this->set_css($this->theme_path.$this->theme.'/css/mybootstrap.css');
	$this->set_css($this->theme_path.$this->theme.'/css/bootstrap.min.css');
    $this->set_css($this->theme_path.$this->theme.'/css/font-awesome.min.css');

    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	$this->set_js_config($this->theme_path.$this->theme.'/js/flexigrid-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="col-lg-6">
<div class="crud-form panel panel-default panel " data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="mDiv panel-heading">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_edit'); ?> <?php echo $subject?>
			</div>
			<div class='clear'></div>
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
<div id='main-table-box' class="panel-body" >

	<?php echo form_open( $update_url, 'method="post" id="crudForm"  enctype="multipart/form-data"'); ?>
	<div class='form-div'>
		<?php
		$counter = 0;
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>
			<div class='form-field-box form-group <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> :
				</div>
				<div class='form-input-box ' id="<?php echo $field->field_name; ?>_input_box">
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

            <button id="form-button-save" type="submit" class="btn btn-success btn-large">
              <i class="fa fa-save"></i>  <?php echo $this->l('form_update_changes'); ?>
            </button>


<?php 	if(!$this->unset_back_to_list) { ?>

        <button type='button'  id="save-and-go-back-button" class="btn btn-large btn-success">
            <i class="fa fa-save"></i> <?php echo $this->l('form_update_and_go_back'); ?>
        </button>


        <button type='button'  class="btn btn-large btn-warning" id="cancel-button" >
            <i class="fa fa-arrow-left"></i>    <?php echo $this->l('form_cancel'); ?>
        </button>

<?php 	} ?>

        <div class='small-loading'  id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>


	</div>
	<?php echo form_close(); ?>
</div>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>
