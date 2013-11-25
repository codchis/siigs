<h2><?php echo $title ?></h2>
<?php echo $error;?>
<?php 	
	if(!empty($msgResult))
    echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/sendcorreo/reset'); ?>
<br />
<h6><?php echo $info;?></h6>
<table border="1">
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo</label></td>
		<td><input type="text" name="correo" value="<?php echo set_value('correo', ''); ?>"/></td>
	</tr>
    </table>
    <hr>
<h6><?php echo $info2;?></h6>    
    <table>
    <tr>
		<td><label for="pass">Nueva Contraseña</label></td>
		<td><input type="password" name="pass" value="<?php echo set_value('pass', ''); ?>"/></td>
	</tr>
    <tr>
		<td><label for="repiteclave">Repetir Contraseña</label></td>
		<td><input type="password" name="repiteclave" value="<?php echo set_value('repiteclave', ''); ?>" />
        <input type="hidden" name="c" value="<?php echo set_value('c', $c); ?>" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Enviar" /><td>
	</tr>    
</table>