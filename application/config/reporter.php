<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$namePackege = 'reporter/';

$config['app_name'] = "Reporter";
$config['app_main_css'] = "";
$config['app_main_js'] = "";

$config['third_party_name'] = $namePackege;

//config url
$config['pretty_url'] = true;

//path to base template for report site
$config['rpt_views'] = $namePackege;
$config['rpt_template'] = $namePackege."template/";
$config['rpt_base_template'] = $namePackege ."template/index";
$config['rpt_admin_template'] = $namePackege ."admin/";

$config['rpt_assets'] = "assets/{$namePackege}";
$config['rpt_models'] = $namePackege;
$config['rpt_controllers'] = $namePackege;

//adapter for auth
//defaults implements: No_auth_adapter, Ion_auth_adapter, Nette_Security_adapter
$config['rpt_auth_adapter'] = 'Ion_auth_adapter';

$config['rpt_login'] = 'auth/login';

$config['grid_items_per_page'] = 25;

//when enviroment is development or testing, all emails send to this email
$config['tester_email'] = 'eycopia@gmail.com';

//who send emails
$config['sender_email'] = 'support@reporter.com';
$config['sender_name'] = 'Support Report';

$config['config_email'] = array(
    'protocol'      => 'smtp',
    'smtp_host'    => 'ssl://smtp.gmail.com',
    'smtp_port'    => '465',
    'smtp_timeout' => '7',
    'smtp_user'    => 'email@gmail.com',
    'smtp_pass'    => 'your_password',
    'charset'    => 'utf-8',
    'newline'    => "\r\n",
    'mailtype' => 'html');


//DB Driver for the installation
$config['db_default_driver'] = 'mysql';

//DB Drivers supported
$config['db_drivers'] = array(
    'mysql' => 'mysqli',
    'oracle' => 'oci8',
    'postgresql' => 'postgre',
    'sqlserver' => 'odbc'
);