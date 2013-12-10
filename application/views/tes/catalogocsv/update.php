<script type="text/javascript" src="/resources/js/jquery.form.min.js" /></script>
<script type="text/javascript">
$(document).ready(function(){
    
            $('.check_activar').change(function(){
           $.ajax({
               context : this,
               type: "POST",
               data: {'id':$(this).attr("id"),'catalogo':$(this).attr("catalogo"),'activo':((this.checked) ? 1 : 0)},
               url: '/<?php echo DIR_TES.'/catalogocsv/ActivaEnCatalogo';?>'
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
    
	var options = {
		    target:     '',
		    dataType : 'json',
		    success:    function(data) {
				$('#optcampos').html('<thead><tr><th colspan=4>Datos a Modificar/Agregar</td></tr>');

				var errordatos = false;
		    	$.each(data, function(i, item) {
					//Agregar un TR
			    	$tr = $('<tr></tr>');
			    	if (typeof(item) != 'string')
			    	{
			    		$.each(item, function(i, val) {
						//Agregar un TD
			    		$td = $('<td>'+val+'</td>');
			    		$tr.append($td);
			    		});
			    	}
			    	else
			    	{
			    		$td = $('<td>'+item+'</td>');
			    		$tr.append($td);
			    		errordatos = true;
				    }
			    	
			    	$('#optcampos').append($tr);
		    	});
		    	if (errordatos == false)
		    	{
			    	tfoot = $('<tfoot></tfoot>');
			    	tr = $('<tr></tr>');
			    	td = $('<td colspan=2></td>');
			    	input = $('<input type="button" value="Confirmar cambios"/>');
			    	$(input).click(function(){
				    	subirupdate(true);
					});
			    	$(tfoot).append(tr);
			    	$(tr).append(td);
			    	$(td).append(input);
			    	$('#optcampos').append(tfoot);
				}
		}
	};

	$('#btnload').click(function(){

		subirupdate(false);
	});

	function subirupdate(upd)
	{
		if (upd == false)
			options.url = '/<?php echo DIR_TES.'/catalogocsv/loadupdate/'.$catalogo_item->nombre;?>';
		else
			options.url = '/<?php echo DIR_TES.'/catalogocsv/loadupdate/'.$catalogo_item->nombre.'/true';?>';
		
    	$('#loadcsv').submit();
	}
	
	$('#loadcsv').submit(function() 
	{
		$(this).ajaxSubmit(options);
		return false;
	});
	
});
</script>

<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($catalogo_item))
{
?>
<form method="post" enctype="application/x-www-form-urlencoded" id="loadcsv">
<table>
<tr>
<td><input type="file" name="archivocsv" id="btncsv"/></td>
<td><input type="button" name="btnload" id="btnload" value="Cargar Datos" /></td>
</tr>
</table>
</form>
<table id="optcampos">
</table>
<?php 
if (!empty($datos))
{
?>
<table>
    <thead>
        <tr>
        <?php foreach(array_keys((array)$datos[0]) as $claves) {
            if ($claves != 'activo') {?>
            <td><?php echo $claves;?></td>
            <?php } } ?>
            <td>Activar</td>
        </tr>
    </thead>
    <?php foreach($datos as $dato) { ?>
    <tr>
        <?php foreach($dato as $clave => $item) { 
                if ($clave != 'activo')
                {
        ?>
                    <td><?php echo $item; ?></td>
           <?php }
                
        } ?>
        <td>
            <input class="check_activar" type="checkbox" id="<?php echo $dato->id;?>" catalogo="<?php echo $catalogo_item->nombre; ?>" <?php echo ($dato->activo == false) ? "" : "checked" ; ?> >
            <label for="<?php echo $dato->id;?>"><?php echo ($dato->activo == false) ? "Activar" : "Desactivar" ; ?></label>
        </td>
    </tr>
    <?php } ?>
</table>
<?php
}
}
else
{
	echo "No se ha encontrado el elemento";
}