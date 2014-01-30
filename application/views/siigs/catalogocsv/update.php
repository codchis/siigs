<script type="text/javascript" src="/resources/js/jquery.form.min.js" /></script>
<script type="text/javascript">
$(document).ready(function(){
    
            $('.check_activar').change(function(){
           $.ajax({
               context : this,
               type: "POST",
               data: {'id':$(this).attr("id"),'catalogo':$(this).attr("catalogo"),'activo':((this.checked) ? 1 : 0)},
               url: '/<?php echo DIR_SIIGS.'/catalogocsv/ActivaEnCatalogo';?>'
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
			    	input = $('<input type="button" class="btn btn-primary" value="Confirmar cambios"/>');
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


            $('#alert').removeClass('warning');
            $('#alert').html('');
            $('#optcampos').html('');
                
            var filename = $("#btncsv").val();
            var extension = filename.replace(/^.*\./, '');
            if (extension == filename || extension.toLowerCase() != 'csv')
            {
                $('#alert').addClass('warning');
                $('#alert').html('Solo se aceptan archivos con extensión csv');
                //alert('Solo se aceptan archivos con extensión csv');
                return false;
            }
            subirupdate(false);
	});

	function subirupdate(upd)
	{
		if (upd == false)
			options.url = '/<?php echo DIR_SIIGS.'/catalogocsv/loadupdate/'.$catalogo_item->nombre;?>';
		else
			options.url = '/<?php echo DIR_SIIGS.'/catalogocsv/loadupdate/'.$catalogo_item->nombre.'/true';?>';
		
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
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<a href="<?php echo site_url().DIR_SIIGS; ?>/catalogocsv/" class="btn btn-primary">Regresar al listado<i class="icon-arrow-left"></i></a>
<br/>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($catalogo_item))
{
?>
<form method="post" enctype="application/x-www-form-urlencoded" id="loadcsv">
<div class="table table-striped">
<table>
<tr>
<td>[Archivo csv separado por comas]<input type="file" name="archivocsv" class="btn btn-primary" id="btncsv"/></td>
<td>
    <button type="button" name="btnload" id="btnload" class="btn btn-primary">Cargar Datos<i class="icon-upload"></i></button>
</td>
</tr>
<tr>
    <td colspan="2">
    <!--div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div-->
    <div id="alert"></div>
        <!--input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php //echo site_url().DIR_SIIGS; ?>/catalogocsv'" class="btn btn-primary" /-->
    </td>
</tr>
</table>
</form>
<table id="optcampos">
</table>
<?php 
if (!empty($datos))
{
?>
<div class='table table-striped'>
<table>
    <thead>
        <thead><tr><th colspan ="<?php echo count((array)$datos[0]);?>">Datos del catalogo</td></tr></thead>
        <tr>
        <?php foreach(array_keys((array)$datos[0]) as $claves) {
            if ($claves != 'activo') {?>
            <td><h2><?php echo $claves;?></h2></td>
            <?php } } ?>
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
        <?php if (!empty($dato->id)) {?>
        <td>
            <input class="check_activar" type="checkbox" id="<?php echo $dato->id;?>" catalogo="<?php echo $catalogo_item->nombre; ?>" <?php echo ($dato->activo == false) ? "" : "checked" ; ?> >
            <label for="<?php echo $dato->id;?>"><?php echo ($dato->activo == false) ? "Activar" : "Desactivar" ; ?></label>
        </td>
        <?php } ?>
    </tr>
    <?php } ?>
<tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
</tfoot>
</table>
</div>
</div>
<?php
}
}
else
{
	echo "No se ha encontrado el elemento";
}