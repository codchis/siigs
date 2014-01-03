<h2><?php echo $title ?></h2>
<?php echo $error;?>
<?php 	
	if(!empty($msgResult))
	echo "<div class='".$infoclass."'>".$msgResult."</div>";

	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/update_info'); ?>
<br />
<?php if($info!="") echo '<div class="info">'.$info.'</div>';?>
<div class="span6 login">
    <div class="row-fluid contenido" id="content">

<table width="430" border="0">	
	<tr>
		<td width="153"><label for="correo">Grupo</label></td>
		<td width="189"><?php echo $grupo; ?></td>
	</tr>
    <tr>
		<td><label for="correo">Nombre</label></td>
		<td><?php echo $nombre; ?></td>
	</tr>
	<tr>
		<td><label for="correo">Correo</label></td>
		<td><input type="text" name="correo" value="<?php echo set_value('correo', $correo); ?>"/></td>
	</tr>
    <tr>
		<td><label for="pass">Contraseña Actual</label></td>
		<td><input type="password" name="pass" value="<?php echo set_value('pass', ''); ?>"/></td>
	</tr>
    <tr>
		<td><label for="newpass">Nueva Contraseña</label></td>
		<td><input type="password" name="newpass" value="<?php echo set_value('newpass', ''); ?>"/></td>
	</tr>
    <tr>
		<td><label for="repiteclave">Repetir Contraseña</label></td>
		<td><input type="password" name="repiteclave" value="<?php echo set_value('repiteclave', ''); ?>" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Enviar" class="btn btn-primary" /><td width="10">
	</tr>    
</table>
 </div>
</div>