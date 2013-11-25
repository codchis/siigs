<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<?php if (!empty($controladores) && !count($controladores) == 0) { ?>

<script type="text/javascript">

 function buscar(obj)
 {
	 val = $('select[name="entorno"]').val();
	 if (val == 0)
		 return false;
		 //window.open("/controlador/","_self");
	 else
		 return true;
		// window.open("/entorno/" + val + "/controlador/","_self");
 }

</script>

<fieldset>
    <legend><strong>Opciones de filtrado</strong></legend>
 		<?php echo form_open(DIR_SIIGS.'/controlador'); ?>
	         Entorno:
	        <?php  echo  form_dropdown('id_entorno', $entornos, $id_entorno); ?>
	        <input type="submit" name="btnFiltrar" id="btnFiltrar" value="Filtrar" onclick="return buscar();" />
   		</form>
</fieldset>

<table>
<thead>
	<tr>
	<th>Entorno</th>
	<th>Nombre</th>
	<th>Descripci&oacute;n</th>
	<th>Clase</th>
	<th>Detalles</th>
	<th>Acciones</th>
	<th>Modificar</th>
	<th>Eliminar</th>
	</tr>
</thead>
<?php foreach ($controladores as $controlador_item): ?>
	<tr>
	<td><a href="/<?php echo DIR_SIIGS; ?>/entorno/view/<?php echo $controlador_item->id_entorno ?>"><?php echo $controlador_item->entorno;?></a></td>
		<td><?php echo $controlador_item->nombre ?></td>
		<td><?php echo $controlador_item->descripcion ?></td>
		<td><?php echo $controlador_item->clase ?></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/controlador/view/<?php echo $controlador_item->id ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/controlador/accion/<?php echo $controlador_item->id ?>">Ver acciones</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/controlador/update/<?php echo $controlador_item->id ?>">Modificar</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/controlador/delete/<?php echo $controlador_item->id ?>" onclick="if (confirm('Realmente desea eliminar este controlador?')) { return true; } else {return false;}">Eliminar</a></td>
		<td></td>
	</tr>
<?php endforeach ?>
<tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
</tfoot>
<tr>
	<td colspan=8 >
	<a href="/<?php echo DIR_SIIGS; ?>/controlador/insert/<?php echo $id_entorno;?>">Crear Nuevo</a>
	</td>
</tr>
</table>
<?php } else {?>
<table>
<thead>
<tr>
	<th>No se encontraron registros</th>
</tr>
</thead>
<tr>
	<td >
	<a href="/<?php echo DIR_SIIGS; ?>/controlador/insert/<?php echo $id_entorno;?>">Crear Nuevo</a>
	</td>
</tr>
</table>
<?php } ?>
