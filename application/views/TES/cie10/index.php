<script type="text/javascript" src="/resources/js/jquery.form.min.js" /></script>
<script type="text/javascript">
$(document).ready(function(){
    
        $('.check_catalogo').change(function(){
           $.ajax({
               context : this,
               type: "POST",
               data: {'id':$(this).attr("id"),'catalogo':$(this).attr("catalogo"),'activo':((this.checked) ? 1 : 0)},
               url: '/<?php echo DIR_TES.'/cie10/AgregaEnCatalogo';?>'
           })
             .done(function(result)
               {
	                if (result == 'error')
	                {
                            alert("Ocurrió un error al aplicar los cambios al catálogo");
                            if ($(this).checked == true)
                            {
                                $(this).removeAttr("checked");
                            }
                            else
	                	$(this).attr("checked",false);
                        }
                        else
                        {
                            if ($(this).checked == true)
                            {
                                $(this).removeAttr("checked");
                            }
                            else
	                	$(this).attr("checked",false);
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
			options.url = '/<?php echo DIR_TES.'/cie10/insert/';?>';
		else
			options.url = '/<?php echo DIR_TES.'/cie10/insert/true';?>';
		
    	$('#loadcsv').submit();
	}
	
	$('#loadcsv').submit(function() 
	{
		$(this).ajaxSubmit(options);
		return false;
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
        <th>Modificar</th>
	<th>
            <a href="/<?php echo DIR_TES; ?>/cie10/view/eda">
            Catálogo EDA
            </a>
        </th>
	<th>
            <a href="/<?php echo DIR_TES; ?>/cie10/view/ira">
            Catálogo IRA
            </a>
        </th>
        <th>
            <a href="/<?php echo DIR_TES; ?>/cie10/view/consultas">
            Consultas
            </a>
        </th>
	</tr>
</thead>
<?php foreach ($datos as $item): ?>
	<tr>
		<td><?php echo $item->cie10 ?></td>
		<td><?php echo $item->descripcion ?></td>
                <td><a href="/<?php echo DIR_TES; ?>/cie10/update/<?php echo $item->id ?>">Modificar</a></td>
                <td>
                    <input class="check_catalogo" type="checkbox" id="eda<?php echo $item->id;?>" catalogo="eda" <?php echo ($item->eda == false) ? "" : "checked" ; ?> >
                    <label for="eda<?php echo $item->id;?>"><?php echo ($item->eda == false) ? "Agregar" : "Quitar" ; ?></label>
                </td>
                <td>
                    <input class="check_catalogo" type="checkbox" id="<?php echo $item->id;?>" catalogo="ira" <?php echo ($item->ira == false) ? "" : "checked" ; ?> >
                    <label for="ira<?php echo $item->id;?>"><?php echo ($item->ira == false) ? "Agregar" : "Quitar" ; ?></label>
                </td>
                <td>
                    <input class="check_catalogo" type="checkbox" id="<?php echo $item->id;?>" catalogo="consulta" <?php echo ($item->consulta == false) ? "" : "checked" ; ?> >
                    <label for="consulta<?php echo $item->id;?>"><?php echo ($item->consulta == false) ? "Agregar" : "Quitar" ; ?></label>
                </td>
		<!--td><a href="/<?php //echo DIR_TES; ?>/cie10/delete/<?php echo $item->cie10 ?>" onclick="if (confirm('Realmente desea eliminar este registro?')) { return true; } else {return false;}">Eliminar</a></td-->
	</tr>
<?php endforeach ?>
<tr>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
</tr>
<tr>
	<td colspan=6 >
            <form method="post" enctype="application/x-www-form-urlencoded" id="loadcsv">
            <table>
            <tr>
            <td><input type="file" name="archivocsv" id="btncsv"/></td>
            <td><input type="button" name="btnload" id="btnload" value="Cargar Datos"/></td>
            </tr>
            </table>
            </form>
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
            <form method="post" enctype="application/x-www-form-urlencoded" id="loadcsv">
            <table>
            <tr>
            <td><input type="file" name="archivocsv" id="btncsv"/></td>
            <td><input type="button" name="btnload" id="btnload" value="Cargar Datos"/></td>
            </tr>
            </table>
            </form>
	</td>
</tr>
</table>
<?php } ?>
<table id="optcampos">
</table>