<h2><?php echo $title ?></h2>
<?php echo $info;?>
<?php 	
	if(!empty($msgResult))
    echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/remember'); ?>
<br />
<table border="1">
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo</label></td>
		<td><input type="text" name="correo" value="<?php echo set_value('correo', ''); ?>"/></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Enviar" /><td>
	</tr>    
</table>