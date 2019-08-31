
<?php 

	$column_width = (int)(80/count($columns));
	
	if(!empty($list)){
?><div class="bDiv" >
		<table cellspacing="0" cellpadding="0" border="0" id="flex1" class="table table-striped table-hover table-bordered dt-responsive nowrap">
		<thead>
			<tr class='hDiv'>
				<?php foreach($columns as $column){?>
				<th width='<?php echo $column_width?>%'>
					<div class="text-left field-sorting <?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo $order_by[1]?><?php }?>" 
						rel='<?php echo $column->field_name?>'>
						<?php echo $column->display_as?>
					</div>
				</th>
				<?php }?>
				<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th align="left" abbr="tools" axis="col1" class="" width='20%'>
					<div class="text-center">
						<?php echo $this->l('list_actions'); ?>
					</div>
				</th>
				<?php }?>
			</tr>
		</thead>		
		<tbody>

		
<?php foreach($list as $num_row => $row){ ?>        
		<tr  <?php if($num_row % 2 == 1){?>class="erow"<?php }?>>
			<?php foreach($columns as $column){?>
			<td width='<?php echo $column_width?>%' class='<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>'>
				<div class='text-left'><?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
			</td>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td align="left" width='20%'>
				<div class='tools'>




                    <?php
                    if(!empty($row->action_urls)){ ?>

                        <?php foreach($row->action_urls as $action_unique_id => $action_url){
                            $action = $actions[$action_unique_id];
                            ?>
                            <span>
										<a href="<?php echo $action_url; ?>" class="<?php echo $action->css_class; ?> crud-action btn btn-default btn-xs" >

                                            <?php if(!empty($action->image_url)){?>
                                                <i class="<?php echo $action->image_url; ?>"></i>
                                            <?php } ?>
                                            <?php echo $action->label?>
                                        </a>
                                    </span>
                        <?php }
                    }
                    ?>

						

						
						  <?php if(!$unset_read){?>

                        <a  class="btn btn-xs btn-default" href="<?php echo $row->read_url?>"  title="<?php echo $this->l('list_view')?> <?php echo $subject?>" >
                            <i class="fa fa-search"></i> <?php echo $this->l('list_view')?></a>


                        <?php }?>
						
                            

                                <?php if(!$unset_edit){?>
								<span>
                                    <a class="btn btn-primary btn-xs" href='<?php echo $row->edit_url?>' title='<?php echo $this->l('list_edit')?> <?php echo $subject?>'  >
                                        <i class="fa fa-edit"></i>  <?php echo $this->l('list_edit')?>
                                    </a>
									</span>
                                <?php }?>
                            
                            
                                <?php if(!$unset_delete){?>
								<span>
                                    <a class="delete-row btn btn-danger btn-xs" href='<?php echo $row->delete_url?>' title='<?php echo $this->l('list_delete')?> <?php echo $subject?>'  >
                                        <i class="fa fa-trash"></i> <?php echo $this->l('list_delete')?>
                                    </a>
									</span>
                                <?php }?>
                            













                    <div class='clear'></div>
				</div>
			</td>
			<?php }?>
		</tr>
<?php } ?>        
		</tbody>
		</table>
	</div>
<?php }else{?>
	<br/>
	&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->l('list_no_items'); ?>
	<br/>
	<br/>
<?php }?>	
