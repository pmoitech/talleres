<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
</head>
<body>
	<div>

<b><?php echo  $this->session->userdata('nombre_taller'); ?></b>
<br/>
<b><?php echo "Cod: " . $this->session->userdata('codigo_taller'). " - Perfil: ".$this->session->userdata('perfil'); ?></b>
<br/>
<b>Hola <?php echo $this->session->userdata('nombre'); ?> !!!</b>
<?php
	if($this->session->userdata('perfil')=='admin'){ ?>
		<li><a href='<?php echo site_url('admin/user_management')?>'>Administradores</a></li>
		<li><a href='<?php echo site_url('admin/talleres')?>'>Talleres</a></li>
		<li><a href='<?php echo site_url('admin/user_owner')?>'>Dueños</a></li>
		<li><a href='<?php echo site_url('admin/sucursales')?>'>Sucursales</a></li>
		<li><a href='<?php echo site_url('admin/user_worker')?>'>Trabajadores</a></li>
		<li><a href='<?php echo site_url('admin/tipo_trabajos')?>'>Tipo de trabajos</a></li>
		<li><a href='<?php echo site_url('admin/estados')?>'>Estados</a></li>
		<li><a href='<?php echo site_url('admin/car_owner')?>'>Dueños de autos</a></li>
		<li><a href='<?php echo site_url('admin/cars')?>'>Autos</a></li>
		<li><a href='<?php echo site_url('admin/tasks')?>'>Trabajos</a></li>
		<li><a href='<?php echo site_url('admin/end_tasks')?>'>Trabajos finalizados</a></li>
		<li><a href='<?php echo site_url('admin/close')?>'>Salida segura</a></li>
		<li>&nbsp;&nbsp;&nbsp;&nbsp;
        
<?php 
	}else
	if($this->session->userdata('perfil')=='owner'){ ?>
		<li><a href='<?php echo site_url('admin/sucursales')?>'>Sucursales</a></li>
		<li><a href='<?php echo site_url('admin/user_worker')?>'>Trabajadores</a></li>
		<li><a href='<?php echo site_url('admin/tipo_trabajos')?>'>Tipo de trabajos</a></li>
		<li><a href='<?php echo site_url('admin/car_owner')?>'>Dueños de autos</a></li>
		<li><a href='<?php echo site_url('admin/cars')?>'>Autos</a></li>
		<li><a href='<?php echo site_url('admin/tasks')?>'>Trabajos</a></li>
		<li><a href='<?php echo site_url('admin/end_tasks')?>'>Trabajos finalizados</a></li>
		<li><a href='<?php echo site_url('admin/close')?>'>Salida segura</a></li>
		<li>&nbsp;&nbsp;&nbsp;&nbsp;

<?php 	
	}else
	if($this->session->userdata('perfil')=='worker'){?>
		<li><a href='<?php echo site_url('admin/tipo_trabajos')?>'>Tipo de trabajos</a></li>
		<li><a href='<?php echo site_url('admin/car_owner')?>'>Dueños de autos</a></li>
		<li><a href='<?php echo site_url('admin/cars')?>'>Autos</a></li>
		<li><a href='<?php echo site_url('admin/tasks')?>'>Trabajos</a></li>
		<li><a href='<?php echo site_url('admin/end_tasks')?>'>Trabajos finalizados</a></li>
		<li><a href='<?php echo site_url('admin/close')?>'>Salida segura</a></li>
		<li>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
	}
	?>

	</div>
	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>
</body>
</html>
