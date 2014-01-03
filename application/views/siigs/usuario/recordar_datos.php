<link href="/resources/css/alert.css" rel="stylesheet" type="text/css" />
<h2><?php echo $title ?></h2>
<?php echo $info;?>
<?php 	
if(!empty($msgResult))
	echo "<div class='".$infoclass."'>".$msgResult."</div>";

	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/remember'); ?>
<br />
<div class="span6 login">
    <div class="row-fluid contenido" id="content">
<table width="416" border="0">
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo</label></td>
		<td><input type="text" name="correo" value="<?php echo set_value('correo', ''); ?>"/></td>
	</tr>
	<tr>
		<td colspan=2><input class="btn btn-primary"  type="submit" name="submit" value="Enviar" /><td>
	</tr>    
</table>
  </div>
</div>