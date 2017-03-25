<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$namePackege = 'reporter/';

//path to base template for report site
$config['rpt_views'] = $namePackege ;
$config['rpt_template'] = $namePackege.'template/';
$config['rpt_admin_template'] = $namePackege .'admin/';

$config['rpt_assets'] = 'assets/' . $namePackege;
$config['rpt_models'] = $namePackege;
$config['rpt_controllers'] = $namePackege;

//adapter for auth
$config['rpt_auth_adapter'] = $namePackege.'Ion_auth_adapter';
$config['rpt_login'] = 'Auth/login';
