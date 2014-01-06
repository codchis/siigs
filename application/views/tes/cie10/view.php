<script type="text/javascript">
$(document).ready(function(){
    
        $('.check_activar').change(function(){
           $.ajax({
               context : this,
               type: "POST",
               data: {'id':$(this).attr("id"),'catalogo':$(this).attr("catalogo"),'activo':((this.checked) ? 1 : 0)},
               url: '/<?php echo DIR_TES.'/cie10/ActivaEnCatalogo';?>'
           })
             .done(function(result)
               {
	                if (result == 'error')
	                {
                            alert("Ocurrió un error al aplicar los cambios al catálogo");
                            if ($(this).is(':checked'))
                            {
                                $(this).removeAttr("checked");
                            }
                            else
	                	$(this).attr("checked",false);
                        }
                        else
                        {
                            if ($(this).is(':checked'))
                            {
                                $("label[for='"+$(this).attr("id")+"']").html("Desactivar");
                            }
                            else
                            {
	                	$("label[for='"+$(this).attr("id")+"']").html("Activar");
                            }
                        }
	       });
       });
});
</script>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($datos))
{
?>
<div class="table table-striped">
<table>
<thead>
	<tr>
	<th><h2>CIE10</h2></th>
	<th><h2>Descripción</h2></th>
        <th><h2>Activo</h2></th>
	</tr>
</thead>
<?php foreach ($datos as $item): ?>
	<tr>
		<td><?php echo $item->id_cie10 ?></td>
		<td><?php echo $item->descripcion ?></td>
                <td>
                    <input class="check_activar" type="checkbox" id="<?php echo $item->id;?>" catalogo="<?php echo $catalogo; ?>" <?php echo ($item->activo == false) ? "" : "checked" ; ?> >
                    <label for="<?php echo $item->id;?>"><?php echo ($item->activo == false) ? "Activar" : "Desactivar" ; ?></label>
                </td>
	</tr>
<?php endforeach ?>
</table>
</div>
<?php
}
else
{
	echo "No hay datos registrados en el catálogo";
}