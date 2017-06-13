<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Server
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class Server extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->reporter_auth->isLogin();
		$this->reporter_auth->checkAdmin();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
	}

	/**
	 * CRUD configure  for table server_connection
	 * @return html
	 */
	public function index()
	{
		try{
			$crud = new grocery_CRUD();

            $crud->unset_delete();//grid

			$crud->set_table('server_connection');
			$crud->set_subject('Server Connections');
			$crud->field_type('password', 'password');
			$crud->fields('name','dbName','host','user', 'password', 'port', 'oracle', 'status');
			$crud->required_fields('name', 'dbName','host', 'user', 'port');
			$crud->callback_before_insert(array($this,'encrypt_password_callback'));
    		$crud->callback_before_update(array($this,'encrypt_password_callback'));
    		$crud->callback_edit_field('password',array($this,'decrypt_password_callback'));
			$crud->unset_texteditor('oracle');
			$output = $crud->render();
			$output->title_page = $this->lang->line('admin_server_title');
            $output->main_content = $this->config->item('rpt_views') . 'admin';
            $this->load->view( $this->config->item('rpt_template') . 'index',$output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}


	public function type_chart(){
		try{
			$crud = new grocery_CRUD();

            $crud->unset_delete();//grid

			$crud->set_table('type_chart');
			$crud->set_subject('Types of Charts');
			$crud->fields('name','description', 'status');
			$crud->required_fields('name');
			$output = $crud->render();
			$output->title_page = $this->lang->line('admin_type_chart');
			$this->renderView($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function encrypt_password_callback($post_array, $primary_key = null)
	{
		$this->load->library('encrypt');
	    $key = $this->config->item('encryption_key');
	    if(trim($post_array['password']) != ''){
        	$post_array['password'] = $this->encrypt->encode($post_array['password'], $key);
        }
	    return $post_array;
	}

	function decrypt_password_callback($value)
	{
	    $this->load->library('encrypt');
	    $key = $this->config->item('encryption_key');
	    $decrypted_password = $this->encrypt->decode($value, $key);
	    return "<input type='password' name='password' value='$decrypted_password' />";
	}

}
