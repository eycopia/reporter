<?php
/** Boostrap CSS */
$this->set_css($this->theme_path.$this->theme.'/css/bootstrap.min.css');
$this->set_css($this->theme_path.$this->theme.'/css/font-awesome.min.css');

$this->set_js_lib($this->default_javascript_path . '/' . grocery_CRUD::JQUERY);

$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.noty.js');
$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/config/jquery.noty.config.js');
$this->set_js_lib($this->default_javascript_path . '/common/lazyload-min.js');

if (!$this->is_IE7()) {
    $this->set_js_lib($this->default_javascript_path . '/common/list.js');
}

$this->set_js($this->theme_path.$this->theme . '/js/cookies.js');
$this->set_js($this->theme_path.$this->theme . '/js/flexigrid.js?a=1');

$this->set_js($this->default_javascript_path . '/jquery_plugins/jquery.form.min.js');

$this->set_js($this->default_javascript_path . '/jquery_plugins/jquery.numeric.min.js');
$this->set_js($this->theme_path.$this->theme . '/js/jquery.printElement.min.js');

/** Fancybox */
$this->set_css($this->default_css_path . '/jquery_plugins/fancybox/jquery.fancybox.css');
$this->set_js($this->default_javascript_path . '/jquery_plugins/jquery.fancybox-1.3.4.js');
$this->set_js($this->default_javascript_path . '/jquery_plugins/jquery.easing-1.3.pack.js');

/** Jquery UI */
$this->load_js_jqueryui();

?>
<script type='text/javascript'>
    var base_url = '<?php echo base_url();?>';

    var subject = '<?php echo addslashes($subject); ?>';
    var ajax_list_info_url = '<?php echo $ajax_list_info_url; ?>';
    var unique_hash = '<?php echo $unique_hash; ?>';

    var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";
</script>
<div id='list-report-error' class='report-div error text-danger'></div>
<?php if ($success_message !== null) { ?>
    <div id='list-report-success' class='report-div success report-list text-success alert alert-success'
       <p><?php echo $success_message; ?></p>
    </div>
<?php } ?>
<!-- flexigrid -->
<div class="flexigrid" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
    <div id="hidden-operations" class="hidden-operations"></div>
    <div class="mDiv">
        <div class="ftitle">
            &nbsp;
        </div>
        <div title="<?php echo $this->l('minimize_maximize'); ?>" class="ptogtitle">
            <span></span>
        </div>
    </div>
    <div id='main-table-box' class="main-table-box">

        <?php if (!$unset_add || !$unset_export || !$unset_print) { ?>

            <div class="btn-group">

                <?php if (!$unset_add) { ?>



                    <a href='<?php echo $add_url ?>' title='<?php echo $this->l('list_add'); ?> <?php echo $subject ?>' class='btn btn-success'>

                        <i class="fa fa-plus-circle"></i> <?php echo $this->l('list_add'); ?> <?php echo $subject ?>

                    </a>

                <?php } ?>

                <?php if (!$unset_export) { ?>
                    <a class="btn btn-default  export-anchor" data-url="<?php echo $export_url; ?>" target="_blank">

                        <i class="fa fa-file-excel-o export"></i> <?php echo $this->l('list_export'); ?>


                    </a>






                <?php } ?>

                <?php if (!$unset_print) { ?>
                    <a class="btn btn-default   print-anchor" data-url="<?php echo $print_url; ?>">
                        <i class="fa fa-print"></i> <?php echo $this->l('list_print'); ?>

                    </a>

                <?php } ?>

            </div>
            <hr>

            <p></p>
        <?php } ?>

        <?php echo form_open($ajax_list_url, 'method="post" id="filtering_form" class="filtering_form" autocomplete = "off" data-ajax-list-info-url="' . $ajax_list_info_url . '"'); ?>

        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-3">
				<span class="pcontrol form-inline">
					<?php list($show_lang_string, $entries_lang_string) = explode('{paging}', $this->l('list_show_entries')); ?>
                    <label class="form-inline"><?php echo $show_lang_string; ?>
                        <select name="per_page" id='per_page' class="per_page form-control input-sm">
                            <?php foreach ($paging_options as $option) { ?>
                                <option value="<?php echo $option; ?>"
                                        <?php if ($option == $default_per_page){ ?>selected="selected"<?php } ?>><?php echo $option; ?>
                                    &nbsp;&nbsp;</option>
                            <?php } ?>
                        </select>
                        <?php //echo $entries_lang_string; ?>
                    </label>
                    <input type='hidden' name='order_by[0]' id='hidden-sorting' class='hidden-sorting'
                           value='<?php if (!empty($order_by[0])) { ?><?php echo $order_by[0] ?><?php } ?>'/>
					<input type='hidden' name='order_by[1]' id='hidden-ordering' class='hidden-ordering'
                           value='<?php if (!empty($order_by[1])) { ?><?php echo $order_by[1] ?><?php } ?>'/>
				</span>
                </div>

                <div class="col-sm-9 ">
                    <div class="row">
                        <div class="sDiv quickSearchBox  form-inline" id='quickSearchBox'>
                            <div class="form-group">
                                <?php echo $this->l('list_search'); ?>:
                                <input type="text" class="form-control search_text" name="search_text" size="20"
                                       id='search_text'>
                            </div>
                            <div class="form-group">
                                <select name="search_field" id="search_field" class="form-control">
                                    <option value=""><?php echo $this->l('list_search_all'); ?></option>
                                    <?php foreach ($columns as $column) { ?>
                                        <option value="<?php echo $column->field_name ?>"><?php echo $column->display_as ?>
                                            &nbsp;&nbsp;</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit"  class="crud_search btn  btn-primary" id='crud_search'>
                                    <i class="fa fa-search"> </i> <?php echo $this->l('list_search'); ?>
                                </button>

                    <span style="display:none" class="ajax_refresh_and_loading" id='ajax_refresh_and_loading'>
                        <i class="fa fa-spinner fa-spin"></i>
                            </div>


                            <button type="button"  id='search_clear' class="search_clear btn btn-default"    >
                                <i class="fa fa-eraser"> </i> <?php echo $this->l('list_clear_filtering'); ?>
                            </button>
                        </div>

                    </div>

                </div>


            </div>

        </div>

        <p></p>

        <div id='ajax_list' class="ajax_list">
            <?php echo $list_view ?>
        </div>




        <div class="col-sm-12">

            <ul class="pagination">
                <li>

                    <a class="pFirst pButton first-button">
                        <i class="fa fa-angle-double-left"></i>
                    </a>
                </li>

                <li>
                    <a class="pPrev pButton prev-button">
                        <i class="fa fa-angle-left"></i>
                    </a>
                </li>

                <li>
                                        <span class="pcontrol"><?php echo $this->l('list_page'); ?> <input name='page' type="text" value="1"
                                                                                                           size="4" id='crud_page'
                                                                                                           class="crud_page">
                                            <?php echo $this->l('list_paging_of'); ?>
                                            <span id='last-page-number'
                                                  class="last-page-number"><?php echo ceil($total_results / $default_per_page) ?></span>
                                            </span>
                </li>

                <li>

                    <a class="pNext pButton next-button">
                        <i class="fa fa-angle-right"></i>
                    </a>

                </li>
                <li>

                    <a class="pLast pButton last-button">
                        <i class="fa fa-angle-double-right"></i>
                    </a>
                </li>

                <li>
                    <a  class="pReload pButton ajax_refresh_and_loading" id='ajax_refresh_and_loading'>
                        <i class="fa fa-refresh"></i>
                    </a>
                </li>

                <li>

                            <span class="pPageStat">
                            <?php $paging_starts_from = "<span id='page-starts-from' class='page-starts-from'>1</span>"; ?>
                            <?php $paging_ends_to = "<span id='page-ends-to' class='page-ends-to'>" . ($total_results < $default_per_page ? $total_results : $default_per_page) . "</span>"; ?>
                            <?php $paging_total_results = "<span id='total_items' class='total_items'>$total_results</span>" ?>
                            <?php echo str_replace(array('{start}', '{end}', '{results}'),
                                array($paging_starts_from, $paging_ends_to, $paging_total_results),
                                $this->l('list_displaying')
                            ); ?>
                        </span>
                </li>
            </ul>

        </div>
















        <?php echo form_close(); ?>

    </div>

</div>
