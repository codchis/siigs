<h2><?php echo $title ?></h2>
<?php echo $error;?>
<?php 	
	if(!empty($msgResult))
	echo "<div class='".$infoclass."'>".$msgResult."</div>";
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/reset'); ?>
<br />


<div class="span6 login">
    <div class="row-fluid contenido" id="content">
<?php if($info!="") echo '<div class="info">'.$info.'</div>';?>
<table width="100%" border="0">
	<tr>
		<td width="208"><label for="nombre_usuario">Nombre de usuario</label></td>
		<td width="212"><input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo</label></td>
		<td><input type="text" name="correo" value="<?php echo set_value('correo', ''); ?>"/></td>
	</tr>
    </table>
    <hr>
<?php if($info2!="") echo '<div class="info">'.$info2.'</div>';?>
    <table width="100%">
    <tr>
		<td width="205"><label for="pass">Nueva Contraseña</label></td>
		<td width="200"><input type="password" name="pass" value="<?php echo set_value('pass', ''); ?>"/></td>
	</tr>
    <tr>
		<td><label for="repiteclave">Repetir Contraseña</label></td>
		<td><input type="password" name="repiteclave" value="<?php echo set_value('repiteclave', ''); ?>" />
        <input type="hidden" name="c" value="<?php echo set_value('c', $c); ?>" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Enviar" class="btn btn-primary" /><td width="9">
	</tr>    
</table>
  </div>
</div>