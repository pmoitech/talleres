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

		$this->load->model('login_model');
		$this->load->database();
		$this->load->helper('url');	
		
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
				redirect(base_url().'login');

					//$this->_admin_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array() , 'op' => '' ));
	}	

function user_management()
	{
		if($this->session->userdata('perfil')=='admin'){ 
		
		$crud = new grocery_CRUD();

		$crud->set_theme('datatables');
		$crud->set_table('usuarios');
		$crud->set_subject('Administrador del sistema');
		$crud->columns('usuario_nombres', 'usuario_usuario', 'usuario_mail', 'usuario_telf');
		//$crud->fields('usuario_nombres', 'usuario_usuario', 'usuario_password','usuario_mail', 'usuario_telf');
		$crud->change_field_type('id_taller', 'hidden', $this->session->userdata('id_taller'));
		$crud->change_field_type('usuario_perfil', 'hidden', 'admin');
		$crud->required_fields('usuario_nombres', 'usuario_usuario', 'usuario_mail', 'usuario_telf');
		$crud->display_as('usuario_nombres','Nombres');
		$crud->display_as('usuario_usuario','Login');
 		$crud->display_as('usuario_mail','Mail');
 		$crud->display_as('usuario_telf','Telf.');
 		$crud->display_as('usuario_password','Password');
 		$crud->change_field_type('usuario_password','password');
  		$crud->unset_read();
        $crud->unset_delete();
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));

    	$crud->callback_edit_field('usuario_perfil',array($this,'set_user_admin'));
    	$crud->callback_add_field('usuario_perfil',array($this,'set_user_admin'));
			
		$crud->callback_edit_field('id_taller',array($this,'set_admin_taller'));
    	$crud->callback_add_field('id_taller',array($this,'set_admin_taller'));
		
		$crud->where('usuario_perfil =', 'admin');

        $output = $crud->render();
        $output -> op = 'user_management';
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
	}else{
			$this->close();			
		}
	}

function user_owner()
	{
		if($this->session->userdata('perfil')=='admin') { 
		
		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
		$crud->set_table('usuarios');
		$crud->set_subject('Dueños de talleres');
		$crud->columns('usuario_nombres', 'usuario_usuario','id_taller', 'usuario_mail', 'usuario_telf');
		//$crud->fields('usuario_nombres', 'usuario_usuario', 'usuario_password','usuario_mail', 'usuario_telf');
		//$crud->change_field_type('id_taller', 'hidden', );
		$crud->change_field_type('usuario_perfil', 'hidden', 'owner');
		$crud->required_fields('usuario_nombres', 'usuario_usuario', 'usuario_mail', 'usuario_telf');
		$crud->display_as('usuario_nombres','Nombres dueño');
		$crud->display_as('usuario_usuario','Login');
 		$crud->display_as('usuario_mail','Mail');
 		$crud->display_as('usuario_telf','Telf.');
 		$crud->display_as('usuario_password','Password');
 		$crud->display_as('id_taller','Nombre Taller');
 		$crud->change_field_type('usuario_password','password');
  		$crud->unset_read();
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));

    	$crud->set_relation('id_taller','talleres','taller_nombre');
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));

    	$crud->callback_edit_field('usuario_perfil',array($this,'set_user_owner'));
    	$crud->callback_add_field('usuario_perfil',array($this,'set_user_owner'));
			
		//$crud->callback_edit_field('id_taller',array($this,'set_admin_taller'));
    	//$crud->callback_add_field('id_taller',array($this,'set_admin_taller'));
		
		$crud->where('usuario_perfil =', 'owner');

        $output = $crud->render();
        $output -> op = 'user_management';
 
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

		$crud = new grocery_CRUD();

        $crud->set_theme('datatables');
		$crud->set_table('usuarios');
		$crud->set_subject('Empleados de taller');
		$crud->columns('usuario_nombres', 'id_taller','usuario_mail', 'usuario_telf');
		//$crud->fields('usuario_nombres', 'usuario_usuario', 'usuario_password','usuario_mail', 'usuario_telf');
		//$crud->change_field_type('id_taller', 'hidden', );
		$crud->change_field_type('usuario_perfil', 'hidden', 'worker');
		$crud->required_fields('usuario_nombres', 'usuario_usuario', 'usuario_mail', 'usuario_telf');
		$crud->display_as('usuario_nombres','Nombres empleado');
		$crud->display_as('usuario_usuario','Login');
 		$crud->display_as('usuario_mail','Mail');
 		$crud->display_as('usuario_telf','Telf.');
 		$crud->display_as('usuario_password','Password');
 		$crud->display_as('id_taller','Nombre Taller');
 		$crud->change_field_type('usuario_password','password');
  		$crud->unset_read();
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));

    	$crud->set_relation('id_taller','talleres','taller_nombre');
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));

    	$crud->callback_edit_field('usuario_perfil',array($this,'set_user_worker'));
    	$crud->callback_add_field('usuario_perfil',array($this,'set_user_worker'));
			
		//$crud->callback_edit_field('id_taller',array($this,'set_admin_taller'));
    	//$crud->callback_add_field('id_taller',array($this,'set_admin_taller'));
		
		$crud->where('usuario_perfil =', 'worker');

        $output = $crud->render();
        $output -> op = 'user_management';
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
		}  elseif($this->session->userdata('perfil')=='owner') 
		{

		$this->db->where('id_taller',$this->session->userdata('idTaller'));  
   		$consulta = $this->db->get('talleres');
   		$nombre_taller = $consulta->row('taller_nombre');
   		$id_taller = $consulta->row('id_taller');

		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
		$crud->set_table('usuarios');
		$crud->set_subject('Empleados - '.$nombre_taller);
		$crud->columns('usuario_nombres','id_taller','usuario_mail', 'usuario_telf');
		//$crud->fields('usuario_nombres', 'usuario_usuario', 'usuario_password','usuario_mail', 'usuario_telf');
		$crud->change_field_type('id_taller', 'hidden', $id_taller);
		$crud->change_field_type('usuario_perfil', 'hidden', 'worker');
		$crud->required_fields('usuario_nombres', 'usuario_usuario', 'usuario_mail', 'usuario_telf');
		$crud->display_as('usuario_nombres','Nombres empleado');
		$crud->display_as('usuario_usuario','Login');
 		$crud->display_as('usuario_mail','Mail');
 		$crud->display_as('usuario_telf','Telf.');
 		$crud->display_as('usuario_password','Password');
 		$crud->display_as('id_taller','Nombre Taller');
 		$crud->change_field_type('usuario_password','password');
  		$crud->unset_read();
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));

    	$crud->set_relation('id_taller','talleres','taller_nombre');
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));

    	$crud->callback_edit_field('usuario_perfil',array($this,'set_user_worker'));
    	$crud->callback_add_field('usuario_perfil',array($this,'set_user_worker'));
			
		$crud->callback_edit_field('id_taller',array($this,'set_user_taller'));
    	$crud->callback_add_field('id_taller',array($this,'set_user_taller'));
		
		$crud->where('usuario_perfil =', 'worker');
		$crud->where('taller_nombre =', $nombre_taller);

        $output = $crud->render();
        $output -> op = 'user_management';
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);				
		}else{

		$this->close();
		}
	}

 	function talleres()
	{
		if($this->session->userdata('perfil')=='admin'){ 

		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
		$crud->set_table('talleres');
		$crud->set_subject('Talleres');
		$crud->columns('taller_nombre', 'taller_codigo','taller_info');
		$crud->fields('taller_nombre','taller_codigo','taller_info');
		$crud->required_fields('taller_codigo','taller_nombre','taller_info');
		$crud->display_as('taller_codigo', 'Codigo Taller');
		$crud->display_as('taller_nombre', 'Nombre');
		$crud->display_as('taller_info', 'Detalles');
		$crud->unset_read();

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

    function set_user_call() {
    	return "<input type='hidden' name='perfil' value='CALL' />";
	}
	
	function set_user_cust() {
    	return "<input type='hidden' name='perfil' value='CUST' />";
	}

	function set_user_admin() {
    	return "<input type='hidden' name='usuario_perfil' value='admin'/>";
	}

	function set_user_owner() {
    	return "<input type='hidden' name='usuario_perfil' value='owner'/>";
	}

	function set_user_worker() {
    	return "<input type='hidden' name='usuario_perfil' value='worker'/>";
	}

	function set_user_taller() {
    	return "<input type='hidden' name='id_taller' value=''/>";
	}

	function set_user_sucursal() {
    	return "<input type='hidden' name='id_taller' value='-1' />";

	}

	function set_admin_taller() {
    	return "<input type='hidden' name='id_taller' value='4' />";

	}

	public function close()
    {
    	//cerrar sesión
    	$this->session->sess_destroy();
    	redirect(base_url().'login'); 

    }

}
