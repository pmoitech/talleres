<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Login_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function login_user($taller_codigo,$usuario_usuario,$usuario_password)
	{
		
   		$this->db->where('taller_codigo', $taller_codigo);  
   		$consulta = $this->db->get('talleres');
   		if($consulta->num_rows() == 1) {   //el taller ingresado es correcto
   		$id_taller = $consulta->row('id_taller');
   		$nombre_taller = $consulta->row('taller_nombre');  //toma el dato del combre del taller ingresado

		
		$this->db->where('id_taller',$id_taller);
		$this->db->where('usuario_usuario',$usuario_usuario);
		$this->db->where('usuario_password',$usuario_password);
		$query = $this->db->get('usuarios');

		if($query->num_rows() == 1)  //usuario y password correctos
		{	
			return  $query->row();
		} 

		else {
			$this->session->set_flashdata('usuario_incorrecto','Los datos introducidos son incorrectos');
			redirect(base_url().'login','refresh');
		
		} }
		else {
			$this->session->set_flashdata('usuario_incorrecto','Los datos introducidos son incorrectos');
			redirect(base_url().'login','refresh');
		 } 
	} 

	public function datos_user($check_user)
	{
		$data = array(
	                'is_logued_in' 	=> 		TRUE,
	                'id_usuario' 	=> 		$check_user->id_usuarios,
	                'perfil'		=>		$check_user->usuario_perfil,
	                'username' 		=> 		$check_user->usuario_usuario,
	                'nombre' 		=> 		$check_user->usuario_nombres,
	                'idTaller'		=>		$check_user->id_taller,
            		);
		return $data;
}
}
