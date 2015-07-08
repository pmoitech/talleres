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

<b>Hola .<?php echo $this->session->userdata('nombre')." ".$this->session->userdata('idTaller'); ?> .!!!</b>
<?php
	if($this->session->userdata('perfil')=='admin'){ ?>
		<li><a href='<?php echo site_url('admin/user_management')?>'>Administradores</a></li>
		<li><a href='<?php echo site_url('admin/talleres')?>'>Talleres</a></li>
		<li><a href='<?php echo site_url('admin/user_owner')?>'>Dueños</a></li>
		<li><a href='<?php echo site_url('admin/user_worker')?>'>Trabajadores</a></li>
		<li><a href='<?php echo site_url('admin/close')?>'>Salida segura</a></li>
		<li>&nbsp;&nbsp;&nbsp;&nbsp;
        
<?php 
	}else
	if($this->session->userdata('perfil')=='owner'){ ?>
		<li><a href='<?php echo site_url('admin/user_worker')?>'>Trabajadores</a></li>
		<li><a href='<?php echo site_url('admin/close')?>'>Salida segura</a></li>
		<li>&nbsp;&nbsp;&nbsp;&nbsp;

<?php 	
	}else
	if($this->session->userdata('perfil')=='worker'){?>
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
