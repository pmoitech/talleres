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
		$this->load->library('ajax_grocery_CRUD');  
		$this->output->enable_profiler(false);

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
		$crud->columns('usuario_nombres', 'usuario_usuario','usuario_mail', 'usuario_telf');
		$crud->fields('usuario_nombres', 'usuario_usuario','usuario_password','usuario_mail', 'usuario_telf','id_taller', 'usuario_perfil','id_sucursales' );
		$crud->required_fields('usuario_nombres', 'usuario_usuario', 'usuario_password','usuario_mail', 'usuario_telf');
		$crud->display_as('usuario_nombres','Nombres');
		$crud->display_as('usuario_usuario','Login');
 		$crud->display_as('usuario_mail','Mail');
 		$crud->display_as('usuario_telf','Telf.');
 		$crud->display_as('usuario_password','Password');
 		$crud->change_field_type('usuario_password','password');
 		$crud->change_field_type('id_taller', 'hidden', '4'); //4 es el taller GeoCommerce (admin)
		$crud->change_field_type('usuario_perfil', 'hidden', 'admin');
		$crud->change_field_type('id_sucursales', 'hidden', '0');

  		$crud->unset_read();
        $crud->unset_delete();
        
        $crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));
    		
    	$crud->callback_before_update(array($this,'encrypt_password_callback'));
    	$crud->callback_before_insert(array($this,'encrypt_password_callback'));

    	//$crud->callback_edit_field('usuario_perfil',array($this,'set_user_admin'));
    	//$crud->callback_add_field('usuario_perfil',array($this,'set_user_admin'));
			
		//$crud->callback_edit_field('id_taller',array($this,'set_admin_taller'));
    	//$crud->callback_add_field('id_taller',array($this,'set_admin_taller'));
		
		$crud->where('usuario_perfil =', 'admin');

        $output = $crud->render();
        //$output -> op = 'user_management';
 
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
		$crud->columns('id_taller','usuario_nombres', 'usuario_mail', 'usuario_telf');
		//$crud->fields('usuario_nombres', 'usuario_usuario', 'usuario_password','usuario_mail', 'usuario_telf');
		//$crud->change_field_type('id_taller', 'hidden', );
		$crud->change_field_type('usuario_perfil', 'hidden', 'owner');
		$crud->change_field_type('id_sucursales', 'hidden', '0');
		$crud->required_fields('usuario_nombres','id_taller', 'usuario_usuario', 'usuario_mail', 'usuario_telf');
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
		if($this->session->userdata('perfil')=='admin' || $this->session->userdata('perfil')=='owner' || $this->session->userdata('perfil')=='worker'){ 

		$crud = new ajax_grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('usuarios');  
		$crud->set_subject('Empleados de taller');
		if($this->session->userdata('perfil')=='admin'){
		$crud->columns('id_taller','usuario_nombres','usuario_usuario','id_sucursales','usuario_mail','usuario_telf');	}
		else{
		$crud->columns('usuario_nombres','usuario_usuario','id_sucursales','usuario_mail','usuario_telf');
		}
		$crud->fields('id_taller','id_sucursales','usuario_nombres','usuario_usuario','usuario_password','usuario_mail','usuario_telf','usuario_perfil');
		$crud->required_fields('id_taller','usuario_nombres','usuario_usuario','id_sucursales','usuario_password');
		$crud->display_as('usuario_nombres', 'Nombres');
		$crud->display_as('usuario_usuario', 'Login');
		$crud->display_as('usuario_password', 'Password');
		$crud->display_as('usuario_mail', 'Mail');
		$crud->display_as('id_sucursales', 'Sucursal');
		$crud->display_as('usuario_telf', 'Teléfono');
		$crud->display_as('id_taller', 'Taller');
		$crud->change_field_type('usuario_password','password');
		$crud->change_field_type('usuario_perfil', 'hidden', 'worker');
		$crud->unset_read();

		$crud->where('usuario_perfil = "worker"');

		if($this->session->userdata('perfil')=='admin'){
		$crud->set_relation('id_taller', 'talleres', 'taller_nombre');
		$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre');
			}
		else{
		$crud->set_relation('id_taller', 'talleres', 'taller_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');			
		$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			}

		$crud->callback_edit_field('usuario_password',array($this,'set_password_input_to_empty'));
    	$crud->callback_add_field('usuario_password',array($this,'set_password_input_to_empty'));
				
		$crud->callback_before_update(array($this,'encrypt_password_callback'));
		$crud->callback_before_insert(array($this,'encrypt_password_callback'));
		
		if($this->session->userdata('perfil')<>'admin')
			$crud->where('taller_nombre =', $this->session->userdata('nombre_taller'));
			$output = $crud->render();
			//$output -> op = 'user_management';
			$this->_example_output($output);
		}else{
			$this->close();
		}
	}

	function tipo_trabajos()
	{
		if($this->session->userdata('perfil')=='admin' || $this->session->userdata('perfil')=='owner' || $this->session->userdata('perfil')=='worker'){ 

		$crud = new grocery_CRUD();

        $crud->set_theme('datatables');
		$crud->set_table('tipo_trabajos');
		$crud->set_subject('Tipo de trabajos');

		$crud->columns('id_taller','nombre_trabajo','precio_trabajo','comentarios_trabajo','trabajo_activo');
		$crud->fields('id_taller','nombre_trabajo','trabajo_activo','precio_trabajo','comentarios_trabajo');
		$crud->required_fields('id_taller','nombre_trabajo','precio_trabajo','trabajo_activo');
		$crud->display_as('usuario_nombres', 'Nombres');
		$crud->display_as('usuario_usuario', 'Login');
		$crud->display_as('usuario_password', 'Password');
		$crud->display_as('usuario_mail', 'Mail');
		$crud->display_as('id_sucursales', 'Sucursal');
		$crud->display_as('usuario_telf', 'Teléfono');
		$crud->display_as('id_taller', 'Taller');
		$crud->change_field_type('usuario_password','password');
		if($this->session->userdata('perfil')=='worker') {		
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();
		$crud->where('trabajo_activo = "1"');}

		
		if($this->session->userdata('perfil')=='admin') {
				$crud->set_relation('id_taller', 'talleres', 'taller_nombre');
			}
			else {
				$crud->set_relation('id_taller', 'talleres', 'taller_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			}

		$crud->order_by('nombre_trabajo');

		if($this->session->userdata('perfil')<>'admin')
			$crud->where('taller_nombre =', $this->session->userdata('nombre_taller'));
			$output = $crud->render();
			//$output -> op = 'user_management';
			$this->_example_output($output);
		}else{
			$this->close();
		}
	}

	function car_owner()
	{
		if($this->session->userdata('perfil')=='worker' || $this->session->userdata('perfil')=='admin' || $this->session->userdata('perfil')=='owner'){ 

		$this->load->library('ajax_grocery_CRUD');
		$crud = new ajax_grocery_CRUD();

        $crud->set_theme('datatables');
		$crud->set_table('usuarios');
		$crud->set_subject('Dueños de autos');

		$crud->columns('usuario_nombres','id_taller', 'usuario_mail', 'usuario_telf');
		$crud->fields('id_taller','usuario_nombres', 'usuario_usuario','usuario_mail','usuario_telf', 'usuario_perfil');
		$crud->required_fields('id_taller','id_sucursales','usuario_nombres','usuario_usuario');
		$crud->display_as('usuario_nombres', 'Nombres');
		$crud->display_as('usuario_usuario', 'Cedula');
		$crud->display_as('usuario_mail', 'Mail');
		$crud->display_as('id_sucursales', 'Sucursal');
		$crud->display_as('usuario_telf', 'Teléfono');
		$crud->display_as('id_taller', 'Taller');
		$crud->change_field_type('usuario_perfil', 'hidden', 'car_owner');
		$crud->change_field_type('id_sucursales', 'hidden');
		$crud->change_field_type('usuario_password', 'hidden');		
	

		if($this->session->userdata('perfil')=='admin') {
				$crud->set_relation('id_taller', 'talleres', 'taller_nombre');
				$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre');
			}
			else {
				$crud->set_relation('id_taller', 'talleres', 'taller_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');
				$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');			
			}

		$crud->set_relation_dependency('id_sucursales','id_taller','id_taller');

		//$crud->callback_after_insert(array($this, 'set_code_rutas'));

		$crud->where('usuario_perfil =', 'car_owner');
		$crud->order_by('id_taller,usuario_nombres');

		if($this->session->userdata('perfil')<>'admin')
			$crud->where('taller_nombre =', $this->session->userdata('nombre_taller'));
			$output = $crud->render();
			//$output -> op = 'user_management';
			$this->_example_output($output);
		}else{
			$this->close();
		}
	}

	function cars()
	{
		if($this->session->userdata('perfil')=='worker' || $this->session->userdata('perfil')=='admin' || $this->session->userdata('perfil')=='owner'){ 

		$crud = new grocery_CRUD();

        $crud->set_theme('datatables');
		$crud->set_table('autos');
		$crud->set_subject('Autos');

		$crud->columns('id_taller','id_sucursales','id_usuarios','auto_marca', 'auto_placa','auto_km');
		$crud->fields('id_taller','id_sucursales','id_usuarios','auto_marca', 'auto_placa','auto_km','auto_logindate');
		$crud->required_fields('id_sucursales','id_usuario','auto_marca', 'auto_placa','auto_km');
		$crud->display_as('id_usuarios', 'Dueño');
		$crud->display_as('auto_marca', 'Marca');
		$crud->display_as('auto_placa', 'Placa');
		$crud->display_as('auto_km', 'Kilometraje');
		$crud->display_as('id_sucursales', 'Sucursal');
		$crud->change_field_type('auto_logindate', 'hidden');

		if($this->session->userdata('perfil')=='admin') {
				$crud->set_relation('id_taller', 'talleres', 'taller_nombre');
				$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre');
				$crud->set_relation('id_usuarios', 'usuarios', 'usuario_nombres', 'usuario_perfil IN ("car_owner")');
				}
			else {
				$crud->set_relation('id_taller', 'talleres', 'taller_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');
				$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');			
				$crud->set_relation('id_usuarios', 'usuarios', 'usuario_nombres', 'id_taller IN ("'.$this->session->userdata('id_taller').'") and usuario_perfil IN ("car_owner")');						
				}

		//$crud->callback_add_field('auto_logindate',array($this,'set_password_input_to_empty'));

		$crud->callback_after_insert(array($this, 'log_car_after_insert'));

		$crud->where('usuario_perfil =', 'car_owner');

		$crud->order_by('id_taller,usuario_nombres');

		if($this->session->userdata('perfil')<>'admin')
			$crud->where('taller_nombre =', $this->session->userdata('nombre_taller'));
			$output = $crud->render();
			//$output -> op = 'user_management';
			$this->_example_output($output);
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
		$crud->display_as('id_taller', 'Dueño Taller');
		$crud->unset_read();
			

        $output = $crud->render();
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
	}else{
			redirect(base_url().'login'); 		
		}
	}

function sucursales()
	{
		if($this->session->userdata('perfil')=='admin' || $this->session->userdata('perfil')=='owner' ){ 

		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
		$crud->set_table('sucursales');
		$crud->set_subject('Sucursales');
		$crud->columns( 'id_taller','sucursal_nombre', 'sucursal_telefono', 'sucursal_info');
		$crud->fields( 'id_taller','sucursal_nombre', 'sucursal_telefono', 'sucursal_info');
		$crud->required_fields('id_taller','sucursal_nombre', 'sucursal_telefono', 'sucursal_info');
		$crud->display_as('id_taller', 'Nombre Taller');
		$crud->display_as('sucursal_nombre', 'Nombre Sucursal');
		$crud->display_as('sucursal_telefono', 'Telefono');
		$crud->display_as('sucursal_info', 'Detalles');
		$crud->unset_read();

		if($this->session->userdata('perfil')=='admin') {
			$crud->set_relation('id_taller', 'talleres', 'taller_nombre');
			} else {
			$crud->set_relation('id_taller', 'talleres', 'taller_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			}

			if($this->session->userdata('perfil')<>'admin')
			$crud->where('taller_nombre =', $this->session->userdata('nombre_taller'));
			$output = $crud->render();
			$output -> op = 'user_management';
			$this->_example_output($output);
			}else{
			$this->close();
		}

	
	}

	function estados()
	{
		if($this->session->userdata('perfil')=='admin'){ 

		$crud = new grocery_CRUD();
 
        $crud->set_theme('datatables');
		$crud->set_table('estados');
		$crud->set_subject('Estados para tareas');
		$crud->columns('estado', 'comentarios');
		$crud->fields('estado', 'comentarios');
		$crud->required_fields('estado');
		$crud->unset_read();
			
        $output = $crud->render();
        $crud->order_by('id_estados');
 
        $this->_example_output($output);  
		//$data['titulo'] = 'Bienvenido Administrador';
		//$this->load->view('admin_view',$data);
	}else{
			redirect(base_url().'login'); 		
		}
	}

	function tasks()
	{
		if($this->session->userdata('perfil')=='admin' || $this->session->userdata('perfil')=='owner' || $this->session->userdata('perfil')=='worker' ){ 

		$crud = new ajax_grocery_CRUD();
 
        $crud->set_theme('datatables');
		$crud->set_table('tasks');
		$crud->set_subject('Tareas');
		$crud->columns('id_task','id_tipodetrabajo','auto_marca','auto_placa','km_auto','id_estado','id_usuario','task_begin');
		$crud->fields('id_taller','id_sucursales','auto_placa','id_tipodetrabajo','km_auto','id_estado','task_contact','task_telf','task_correo','task_comments');
		$crud->required_fields('id_taller','id_sucursal','auto_placa','id_tipodetrabajo','km_auto', 'id_estado','task_contact','task_telf');
		$crud->display_as('id_taller', 'Nombre Taller');
		$crud->display_as('id_sucursales', 'Nombre Sucursal');
		$crud->display_as('id_task', 'ID');
		$crud->display_as('auto_placa', 'Placa del auto');
		$crud->display_as('id_tipodetrabajo', 'Tarea');
		$crud->display_as('id_estado', 'Estado del trabajo');
		$crud->display_as('id_usuario', 'Atendido por');
		$crud->display_as('auto_marca', 'Marca vehículo');
		$crud->display_as('id_tipodetrabajo', 'Trabajo');
		$crud->display_as('km_auto', 'Kilometraje');
		$crud->display_as('task_comments', 'Comentarios');
		$crud->display_as('task_contact', 'Contacto');
		$crud->display_as('task_telf', 'Telf. contacto');
		$crud->display_as('task_begin', 'Fecha');
		$crud->change_field_type('task_end', 'hidden');
		if($this->session->userdata('perfil')=='worker') {		
		$crud->unset_delete();}

		$crud->set_relation('id_estado', 'estados', 'estado');
		$crud->set_relation('id_usuario', 'usuarios', 'usuario_nombres');

		$crud->callback_after_insert(array($this, 'log_task_after_insert'));

		$crud->callback_after_update(array($this, 'end_task_after_update'));

		if($this->session->userdata('perfil')=='admin') {
			$crud->set_relation('id_taller', 'talleres', 'taller_nombre');
			$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre');
			$crud->set_relation('id_tipodetrabajo', 'tipo_trabajos', 'nombre_trabajo');
			//$crud->set_relation('id_auto', 'autos', 'auto_marca');
			$crud->set_relation('auto_placa', 'autos', 'auto_placa');
			} else {
			$crud->set_relation('id_taller', 'talleres', 'taller_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');			
			$crud->set_relation('id_tipodetrabajo', 'tipo_trabajos', 'nombre_trabajo','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			//$crud->set_relation('id_auto', 'autos', 'auto_marca','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			$crud->set_relation('auto_placa', 'autos', 'auto_placa','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			//$crud->set_relation_dependency('id_auto','auto_placa','auto_placa');
			}

			$crud->where('estado <> "Finalizado" and estado <> "Cancelado"');

			if($this->session->userdata('perfil')<>'admin')
			$crud->where('taller_nombre =', $this->session->userdata('nombre_taller'));
			$output = $crud->render();
			$output -> op = 'user_management';
			$this->_example_output($output);
			}else{
			$this->close();
		}

	
	}

	function end_tasks()
	{
		if($this->session->userdata('perfil')=='admin' || $this->session->userdata('perfil')=='owner' || $this->session->userdata('perfil')=='worker' ){ 

		$crud = new ajax_grocery_CRUD();
 
        $crud->set_theme('datatables');
		$crud->set_table('tasks');
		$crud->set_subject('Tareas');
		$crud->columns('id_taller','id_sucursales','id_auto','auto_placa','id_tipodetrabajo','km_auto','id_estado', 'task_begin', 'task_end');
		$crud->fields('id_taller','id_sucursales','id_auto','auto_placa','id_tipodetrabajo','km_auto','id_estado');
		$crud->required_fields('id_taller','id_sucursal','id_auto','auto_placa','id_tipodetrabajo','km_auto', 'id_estado');
		$crud->display_as('id_taller', 'Nombre Taller');
		$crud->display_as('id_sucursales', 'Nombre Sucursal');
		$crud->display_as('id_auto', 'Auto');
		$crud->display_as('id_auto', 'Placa');
		$crud->display_as('id_tipodetrabajo', 'Tarea');
		$crud->display_as('id_estado', 'Estado');
		if($this->session->userdata('perfil')=='worker') {		
		$crud->unset_delete();
		$crud->unset_edit();}
		//$crud->unset_read();

		$crud->set_relation('id_estado', 'estados', 'estado');		

		if($this->session->userdata('perfil')=='admin') {
			$crud->set_relation('id_taller', 'talleres', 'taller_nombre');
			$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre');
			$crud->set_relation('id_tipodetrabajo', 'tipo_trabajos', 'nombre_trabajo');
			$crud->set_relation('id_auto', 'autos', 'auto_marca');
			$crud->set_relation('auto_placa', 'autos', 'auto_placa');
			} else {
			$crud->set_relation('id_taller', 'talleres', 'taller_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			$crud->set_relation('id_sucursales', 'sucursales', 'sucursal_nombre','id_taller IN ("'.$this->session->userdata('id_taller').'")');			
			$crud->set_relation('id_tipodetrabajo', 'tipo_trabajos', 'nombre_trabajo','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			$crud->set_relation('id_auto', 'autos', 'auto_marca','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			$crud->set_relation('auto_placa', 'autos', 'auto_placa','id_taller IN ("'.$this->session->userdata('id_taller').'")');
			//$crud->set_relation_dependency('id_auto','auto_placa','auto_placa');
			}

			$crud->where('estado = "Finalizado" or estado = "Cancelado"');

			if($this->session->userdata('perfil')<>'admin')
			$crud->where('taller_nombre =', $this->session->userdata('nombre_taller'));
			$output = $crud->render();
			$output -> op = 'user_management';
			$this->_example_output($output);
			}else{
			$this->close();
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
    	return "<input type='hidden' name='perfil' value='worker' />";
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

	function set_sucursal_taller() {
    	return "<input type='hidden' name='id_taller' value=''/>";
	}



	function set_user_sucursal() {
    	return "<input type='hidden' name='id_taller' value='-1' />";

	}

	function set_admin_taller() {
    	return "<input type='hidden' name='id_taller' value='4' />";

	}

	function log_car_after_insert($post_array,$primary_key) //graba el día en que el carro se crea
	{
    $data = array(
	'auto_logindate' => date('Y-m-d'), //date('Y-m-d H:i:s'),
	);
	$this->db->where('id_auto', $primary_key);
	$this->db->update('autos', $data);
    return true;
	}

	function log_task_after_insert($post_array,$primary_key) //graba el día en que la tarea se crea
	{
		$this->db->where('id_task',$primary_key);
		$consulta = $this->db->get('tasks');
		$auto_placa = $consulta->row('auto_placa');
		$this->db->where('id_auto',$auto_placa);
		$consulta = $this->db->get('autos');
		$auto_marca = $consulta->row('auto_marca');
    $data = array(
	'task_begin' => date('Y-m-d H:i:s'), //date('Y-m-d H:i:s'),
	'id_usuario' => $this->session->userdata('id_usuario'),
	'auto_marca' => $auto_marca,//$auto_marca,
	);
	$this->db->where('id_task', $primary_key);
	$this->db->update('tasks', $data);
    return true;
	}

	function end_task_after_update($post_array,$primary_key) //graba el día en que la tarea finaliza
	{
		$this->db->where('id_task',$primary_key);
		$consulta = $this->db->get('tasks');
		$auto_placa = $consulta->row('auto_placa');
		$this->db->where('id_auto',$auto_placa);
		$consulta = $this->db->get('autos');
		$auto_marca = $consulta->row('auto_marca');
    $data = array(
	'task_end' => date('Y-m-d H:i:s'),//date('Y-m-d H:i:s'),
	'id_usuario' => $this->session->userdata('id_usuario'),
	'auto_marca' => $auto_marca
	);
	$this->db->where('id_task', $primary_key);
	$this->db->update('tasks', $data);
    return true;
	}
	
	public function close()
    {
    	//cerrar sesión
    	$this->session->sess_destroy();
    	redirect(base_url().'login'); 

    }

}
