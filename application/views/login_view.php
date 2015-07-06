<!DOCTYPE html>
	<html lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/960.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/text.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/reset.css" media="screen" />
		<script src="http://code.jquery.com/jquery-1.8.3.js"></script>

		<style type="text/css">
		 	h1{
		 		font-size: 22px;
		 		text-align: center;
		 		margin: 20px 0px;
		 	}
		 	#login{
		 		background: #fefefe;
		 		min-height: 500px;
		 	}
		 	#formulario_login{
		 		font-size: 14px;
		 		border: 8px solid #112233;		 		
		 	}
		 	label{
		 		display: block;
		 		font-size: 16px;
		 		color: #333333;
		 		font-weight: bold;
		 	}
		 	input[type=text],input[type=password]{
		 		padding: 10px 6px;
		 		width: 400px;
		 	}
		 	input[type=submit]{
		 		padding: 5px 40px;
		 		background: #61399d;
		 		color: #fff;
		 	}
		 	#campos_login{
		 		margin: 50px 0px;
		 	}
		 	p{
		 		color: #f00;
		 		font-weight: bold;
		 	}
		 </style>
	</head>
	
	<!--se crean las variables-->	
	<body>
	<?php
	$taller_codigo = array('name' => 'taller_codigo', 'placeholder' => 'codigo de taller');
	$usuario_usuario = array('name' => 'usuario_usuario', 'placeholder' => 'nombre de usuario');
	$usuario_password = array('name' => 'usuario_password',	'placeholder' => 'introduce tu password');
	$submit = array('name' => 'submit', 'value' => 'Iniciar sesión', 'title' => 'Iniciar sesión');
	?>
	<!--se crea el formulario de ingreso-->	
	<div class="container_12">
		<h1><?php echo $titulo;?></h1>  
		<div class="grid_12" id="login">
			<div class="grid_8 push_2" id="formulario_login">
				<div class="grid_6 push_1" id="campos_login">
					<?=form_open(base_url().'login/new_user')?>
					<label for="taller_codigo">Codigo del taller:</label>
					<?=form_input($taller_codigo)?><p><?=form_error('taller_codigo')?></p>
					<label for="usuario_usuario">Nombre de usuario:</label>
					<?=form_input($usuario_usuario)?><p><?=form_error('usuario_usuario')?></p>
					<label for="usuario_password">Introduce tu password:</label>
					<?=form_password($usuario_password)?><p><?=form_error('usuario_password')?></p>
					<?=form_hidden('token',$token)?>
					<?=form_submit($submit)?>
					<?=form_close()?>
					<?php 
					if($this->session->flashdata('usuario_incorrecto'))
					{
					?>
					<p><?=$this->session->flashdata('usuario_incorrecto')?></p>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>