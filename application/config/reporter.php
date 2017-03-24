<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$templateDirectory = 'reporter/';

//path to base template for report site
$config['rpt_views'] = $templateDirectory ;
$config['rpt_template'] = $templateDirectory.'template/';
$config['rpt_admin_template'] = $templateDirectory .'admin/';

$config['rpt_assets'] = 'assets/' . $templateDirectory;
$config['rpt_models'] = $templateDirectory;
$config['rpt_controllers'] = $templateDirectory;
