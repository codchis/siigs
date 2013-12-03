<script>
$(document).ready(function(){
    $('.check_catalogo').change(function(){
           $.ajax({
               context : this,
               type: "POST",
               data: {'id':$(this).attr("id"),'catalogo':$(this).attr("catalogo"),'activo':this.checked},
               url: '/<?php echo DIR_TES.'/cie10/AgregaEnCatalogo';?>'
           })
             .done(function(result)
               {
	                if (result == 'error')
	                {
                            if ($(this).checked == true)
                            {
                                alert($(this).checked);
                                $(this).removeAttr("checked");
                            }
                            else
	                	$(this).attr("checked",false);
                        }
	       });
            });
    });   
</script>

<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<?php if ( !empty($datos) && !count($datos) == 0) { ?>
<table>
<thead>
	<tr>
	<th>CIE10</th>
	<th>Descripción</th>
	<th>Catálogo EDA</th>
	<th>Catálogo IRA</th>
        <th>Consultas</th>
	</tr>
</thead>
<?php foreach ($datos as $item): ?>
	<tr>
		<td><?php echo $item->cie10 ?></td>
		<td><?php echo $item->descripcion ?></td>
                <td>
                    <input class="check_catalogo" type="checkbox" id="<?php echo $item->id;?>" catalogo="eda" <?php echo ($item->eda == false) ? "" : "checked" ; ?> >
                    <?php echo ($item->eda == false) ? "Agregar" : "Quitar" ; ?>
                </td>
                <td>
                    <input class="check_catalogo" type="checkbox" id="<?php echo $item->id;?>" catalogo="ira" <?php echo ($item->ira == false) ? "" : "checked" ; ?> >
                    <?php echo ($item->ira == false) ? "Agregar" : "Quitar" ; ?>
                </td>
                <td>
                    <input class="check_catalogo" type="checkbox" id="<?php echo $item->id;?>" catalogo="consulta" <?php echo ($item->consulta == false) ? "" : "checked" ; ?> >
                    <?php echo ($item->consulta == false) ? "Agregar" : "Quitar" ; ?>
                </td>
		<!--td><a href="/<?php //echo DIR_TES; ?>/cie10/update/<?php echo $item->cie10 ?>">Modificar</a></td>
		<td><a href="/<?php //echo DIR_TES; ?>/cie10/delete/<?php echo $item->cie10 ?>" onclick="if (confirm('Realmente desea eliminar este registro?')) { return true; } else {return false;}">Eliminar</a></td-->
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<a href="/<?php echo DIR_TES; ?>/cie10/update">Cargar Datos</a>
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
	<td>
	<a href="/<?php echo DIR_SIIGS; ?>/cie10/insert">Crear Nuevo</a>
	</td>
</tr>
</table>
<?php } ?>