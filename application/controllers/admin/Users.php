<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Users
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Users extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->reporter_auth->isLogin();
        $this->reporter_auth->checkUserAccess(Permission::$ADMIN);
        $this->load->library('grocery_CRUD');
    }

    /**
     * CRUD Configure for table project
     * @return html
     */
    public function index()
    {
        try{
            $crud = new grocery_CRUD();
            $crud->set_theme('mybootstrap');
            $crud->unset_delete();//grid
            $crud->set_table('base_user');
            $crud->set_subject('Reporter Users');
            $crud->fields('idUser', 'username', 'first_name', 'last_name', 'permission');
            $crud->columns('idUser', 'username', 'first_name', 'last_name', 'permission');
            $crud->required_fields('IdUser','username', 'permission');
            $crud->callback_field('permission',array($this,'fn_permission'));
            $crud->unset_export();
            $crud->unset_print();
            $output = $crud->render();
            $output->title_page = 'Lists of Users  ';
            $output->main_content =  $this->config->item('rpt_views') . 'admin';
            $this->load->view( $this->config->item('rpt_base_template'),$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function fn_permission($value)
    {
        $permission = [
            'Reader' => Permission::$READER,
            'Writer' => Permission::$WRITER,
            'Deleted' => Permission::$DELETED,
            'Administrator' => Permission::$ADMIN,
            'Desarrollador' => Permission::$DEVELOPER,
        ];
        $select = "<select name='permission'>";
        foreach($permission as $key => $val){
            $selected = ($value == $val) ? "selected='selected'" : '';
            $select .= "<option value='$val' $selected>$key</option>";
        }
        return $select . "</select>";
    }
}
