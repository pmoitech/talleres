<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('login_model');
		$this->load->library(array('session','form_validation'));
		$this->load->helper(array('url','form'));
		$this->load->database('default');
		//if($userconfig = $this->session->userdata('userconfig')){	
		//	redirect(base_url().'admin'); 
		//}

    }
    // determinamos el perfil del usuario o le enviamos al login
   public function index()
	{	

		switch ($this->session->userdata('perfil')) {
			case '':
				$data['token'] = $this->token();
				$data['titulo'] = 'Ingreso al sistema de talleres';
				$this->load->view('login_view',$data);
				break;
			case 'admin':
				redirect(base_url().'admin/user_management');
				break;
			case 'owner':
				redirect(base_url().'admin/user_worker');
				break;	
			case 'worker':
				redirect(base_url().'admin/user_worker');
				break;
			default:		
				$data['titulo'] = 'Login con roles de usuario en codeigniter';
				$this->load->view('login_view',$data);
				break;		
		}
	}

	public function token()
	{
		$token = md5(uniqid(rand(),true));
		$this->session->set_userdata('token',$token);
		return $token;
	}
	
	public function new_user()
	{
		if($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token'))
		{
            $this->form_validation->set_rules('usuario_usuario', 'nombre de usuario', 'required'); //|trim|min_length[2]|max_length[150]|xss_clean');
            $this->form_validation->set_rules('usuario_password', 'password', 'required'); //|trim|min_length[6]|max_length[150]|xss_clean');
 
            //lanzamos mensajes de error si es que los hay
            $this->form_validation->set_message('required', 'El %s es requerido');
            $this->form_validation->set_message('min_length', 'El %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El %s debe tener al menos %s carácteres');
			if($this->form_validation->run() == FALSE)
			{
				$this->index();
			}else{
				$taller_codigo = $this->input->post('taller_codigo');
				$usuario_usuario = $this->input->post('usuario_usuario');
				$usuario_password = sha1($this->input->post('usuario_password'));
				$check_user = $this->login_model->login_user($taller_codigo,$usuario_usuario,$usuario_password);

				if($check_user == TRUE)
				{
				$datos = $this->login_model->datos_user($check_user);
		
					$this->session->set_userdata($datos);
					//redirect(base_url().'admin');
					$this->index();
				}
			}
		}else{
			redirect(base_url().'login');
		}
	}

	public function logout_ci()
	{
		$this->session->sess_destroy();
		$this->index();
	}
}
