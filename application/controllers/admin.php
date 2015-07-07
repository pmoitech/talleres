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
		$this->output->enable_profiler(TRUE);
		//if(!$this->userconfig = $this->session->userdata('userconfig')){
		//	redirect(base_url().'login'); 
		//	}
		}
	
	function index() // perfiles: admin, owner, worker
	{
		if($this->session->userdata('perfil')=='admin')
			$this->user_management();
		else
			if($this->session->userdata('perfil')=='owner')
				//$this->_admin_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array() , 'op' => '' ));
				$this->user_owner();

			else
				if($this->session->userdata('perfil')=='worker')
					$this->user_worker();

			else
				redirect(base_url().mal);

					//$this->_admin_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array() , 'op' => '' ));
	}	


function user_management()
	{
		if($this->session->userdata('perfil')=='admin'){ 
		
		echo 'hola admin';
		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
        $crud->set_table('Administradores')
        ->set_subject('Usuarios')
        ->columns('usuario_nombres', 'usuario_usuario', 'usuario_perfil','id_taller', 'usuario_mail', 'usuario_telf')
        ->display_as('usuario_nombres','Usuarios')
        ->display_as('usuario_mail','Mail')
        ->display_as('usuario_telf','Telf.')
        ->display_as('usuario_perfil','Perfil')
        ->display_as('id_taller','Taller')
        ->unset_read()
        ->change_field_type('usuario_password','password');

        $crud->set_relation('id_taller','talleres','taller_nombre');
        //$crud->set_relation_n_n('tallerName','sucursales', 'talleres', 'id_sucursales', 'id_taller', 'taller_nombre', null);

        $crud->add_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf', 'tallerName');
        $crud->edit_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf', 'tallerName');
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));
		
		$crud->callback_after_insert(array($this, 'set_code_rutas'));

		$crud->where('usuario_perfil =', 'admin');

        $output = $crud->render();
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
	}else{
			$this->close();			
		}
	}

function user_owner()
	{
		if($this->session->userdata('perfil')=='owner'){ 
		
		echo 'hola owner';
		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
        $crud->set_table('Usuarios')
        ->set_subject('Usuarios')
        ->columns('usuario_nombres', 'usuario_usuario', 'usuario_perfil','id_taller', 'usuario_mail', 'usuario_telf')
        ->display_as('usuario_nombres','Usuarios')
        ->display_as('usuario_mail','Mail')
        ->display_as('usuario_telf','Telf.')
        ->display_as('usuario_perfil','Perfil')
        ->display_as('id_taller','Taller')
        ->unset_read()
        ->change_field_type('usuario_password','password');

        //$crud->set_relation('id_sucursales','sucursales','sucursal_nombre');
        //$crud->set_relation_n_n('tallerName','sucursales', 'talleres', 'id_sucursales', 'id_taller', 'taller_nombre', null);

        $crud->add_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf', 'tallerName');
        $crud->edit_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf', 'tallerName');
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));
		
		$crud->callback_after_insert(array($this, 'set_code_rutas'));

		$crud->where('usuario_perfil <>', 'admin');

        $output = $crud->render();
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
	}else{
			$this->close();			
		}
	}

	function user_worker()
	{
		if($this->session->userdata('perfil')=='admin'){ 
		
		echo 'hola worker';
		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
        $crud->set_table('usuarios')
        ->set_subject('Usuarios')
        ->columns('usuario_nombres', 'usuario_usuario', 'usuario_perfil','id_taller', 'usuario_mail', 'usuario_telf')
        ->display_as('usuario_nombres','Usuarios')
        ->display_as('usuario_mail','Mail')
        ->display_as('usuario_telf','Telf.')
        ->display_as('usuario_perfil','Perfil')
        ->display_as('id_taller','Taller')
        ->unset_read()
        ->change_field_type('usuario_password','password');

        $crud->set_relation('id_sucursales','sucursales','sucursal_nombre');
        $crud->set_relation_n_n('tallerName','sucursales', 'talleres', 'id_sucursales', 'id_taller', 'taller_nombre', null);

        $crud->add_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf', 'tallerName');
        $crud->edit_fields('usuario_nombres', 'usuario_password', 'usuario_mail', 'usuario_telf', 'tallerName', 'id_sucursales');
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));
		
		$crud->callback_after_insert(array($this, 'set_code_rutas'));



        $output = $crud->render();
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
	}else{
			redirect(base_url().'login'); 		
		}
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

    function set_user_call() {
    	return "<input type='hidden' name='perfil' value='CALL' />";
	}
	
	function set_user_cust() {
    	return "<input type='hidden' name='perfil' value='CUST' />";
	}

	function set_user_admin() {
    	return "<input type='hidden' name='perfil' value='ADMIN' />";
	}

	function set_user_sucursal() {
    	return "<input type='hidden' name='idsucursal' value='-1' />";

	}

	//public function close()
    //{
    	//cerrar sesión
    //	$this->session->sess_destroy();
    //	redirect(base_url().'login'); 

    //}

}
