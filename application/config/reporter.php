<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$namePackege = 'reporter/';

$config['app_name'] = "Generador de Reportes";
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
$config['rpt_auth_adapter'] = 'No_auth_adapter';
//$config['rpt_auth_adapter'] = $namePackege.'Ion_auth_adapter';

$config['rpt_login'] = 'Auth/login';


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
