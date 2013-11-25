<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
    echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/login') ?>
<br />
<table border="1">
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="clave">Clave</label></td>
		<td><input type="password" name="clave" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Entrar" /><td>
	</tr>
    <tr>
		<td colspan=2><a href="http://www.siigs.com/siigs/sendcorreo" id='a1'>¿Olvidaste tu contraseña?</a><td>
	</tr>
</table>
