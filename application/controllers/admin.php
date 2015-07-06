<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class Admin extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->library('grocery_CRUD');  
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'superadmin')
		{
			redirect(base_url().'login');	
		}
		
		echo 'hola admin';
		$crud = new grocery_CRUD();
 
        $crud->set_table('usuarios')
        ->set_subject('Usuarios')
        ->columns('usuario_nombres', 'usuario_perfil','id_taller', 'usuario_mail', 'usuario_telf')
        ->display_as('usuario_nombres','Usuarios')
        ->display_as('usuario_mail','Mail')
        ->display_as('usuario_telf','Telf.')
        ->display_as('usuario_perfil','Perfil')
        ->display_as('id_taller','Taller')
        ->change_field_type('usuario_password','password');

        $crud->set_relation('id_taller','talleres','taller_nombre');

        $crud->add_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf');
        $crud->edit_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf', 'id_taller');
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));
		
		$crud->callback_after_insert(array($this, 'set_code_rutas'));



        $output = $crud->render();
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
	}

 
    function _example_output($output = null)
    {
 
        $this->load->view('example.php',$output);  
    }    

	function set_password_input_to_empty() {
    	return "<input type='password' name='usuario_password' value='' />";
	}

	function encrypt_password_callback($post_array) {

		if(!empty($post_array['usuario_password']))
		{
		    $post_array['usuario_password'] = sha1($post_array['usuario_password']);
		}
		else
		{
		    unset($post_array['usuario_password']);
		}
	    return $post_array;

    }

	function set_code_rutas($post_array,$primary_key)
	{
	    $this->db->update('usuarios',array('usuario_password' => $primary_key),array('id_usuarios' => $primary_key));
    	return true;
	}	

	public function close()
    {
    	//cerrar sesión
    	$this->session->sess_destroy();
    	redirect(base_url().'login'); 

    }

}

