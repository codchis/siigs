<script type="text/javascript" src="/resources/js/jquery.form.min.js" /></script>
<script type="text/javascript">
$(document).ready(function(){
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
}
else
{
	echo "No se ha encontrado el elemento";
}