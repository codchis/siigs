<script type="text/javascript" src="/resources/js/jquery.form.min.js" /></script>
<script type="text/javascript">
$(document).ready(function(){
	
	var options = {
		    target:     '',
		    dataType : 'json',
		    success:    function(data) {
				$('#optcampos').html('<thead><tr><th colspan=4>Datos a Modificar/Agregar</td></tr>');

				var errordatos = false;
                                var eserror = false;
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
                                if (!eserror)
                                    eserror = (item == 'Error');
                                
		    		$td = $('<td>'+((item != 'Error' && item != 'Ok') ? '<div class="'+((eserror) ? 'warning' : 'info')+'">' : '<div>') + item +'</div></td>');
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
			    	input = $('<input type="button" value="Confirmar cambios"  class="btn btn-primary"/>');
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
			options.url = '/<?php echo DIR_SIIGS.'/catalogo/loadupdate/'.$catalogo_item->nombre;?>';
		else
			options.url = '/<?php echo DIR_SIIGS.'/catalogo/loadupdate/'.$catalogo_item->nombre.'/true';?>';
		
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
<h2><?php echo $title; ?></h2>
<?php
if (!empty($catalogo_item))
{
?>
<div class="table table-striped">
<form method="post" enctype="application/x-www-form-urlencoded" id="loadcsv">
<table>
<tr>
<td><input type="file" name="archivocsv" id="btncsv"/></td>
<td><button type="button" name="btnload" id="btnload" class="btn btn-primary">Cargar Datos<i class="icon-upload"></i></button></td>
</tr>
</table>
</form>
<table id="optcampos">
</table>
    <?php echo form_open(DIR_SIIGS.'/catalogo/update/'.$catalogo_item->nombre) ?>
    Comentarios: <textarea name="comentario"><?php echo $catalogo_item->comentario; ?></textarea><br/><br/>
    <button type="submit" name="submit" class="btn btn-primary">Guardar<i class="icon-hdd"></i></button>
    <button type="button" name="cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/catalogo/'" class="btn btn-primary" >Cancelar<i class="icon-arrow-left"></i></button>
    </form> 
<?php 
if (!empty($datos))
{
?>
    <table id="tabla">
    <thead>
    <thead><tr><th colspan="<?php echo count((array)$datos[0]);?>">Datos del catalogo</td></tr>
       </thead>
        <tr>
        <?php foreach(array_keys((array)$datos[0]) as $claves) { ?>
            <td><h2><?php echo $claves;?></h2></td>
            <?php } ?>
        </tr>
    </thead>
    <?php foreach($datos as $dato) { ?>
    <tr>
        <?php foreach($dato as $clave => $item) { ?>
                    <td><?php echo $item; ?></td>
           <?php } ?>
    </tr>
    <?php } ?>
<tfoot>
        <tr><td>
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
</tfoot>
</table>
<?php
}
}
else
{
	echo '<div class="error">Registro no encontrado</div>';
}
?>
</div>