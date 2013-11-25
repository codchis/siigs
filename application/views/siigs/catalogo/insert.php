<script type="text/javascript" src="/resources/js/jquery.form.min.js" /></script>
<script type="text/javascript">
      $(document).ready(function() {

    	  $('#nombre').keyup(function(){

   	       $.ajax({
               type: "GET",
               url: '/<?php echo DIR_SIIGS.'/catalogo/view/';?>cat_'+$('#nombre').val(),
           })
             .done(function(dato)
               {
	                if (dato == 'false')
	                {
	                	$('#llaves').val('1');
	                }
	                else
	                {
	                	$('#llaves').val('0');
	                }
	                });

        	  });

    		var options = {
    			    target:     '#datoscsv',
    			    url:        '/<?php echo DIR_SIIGS.'/catalogo/load';?>',
    			    dataType : 'json',
    			    success:    function(data) {

						$('#optcampos').html('<thead><tr><th colspan=4>Campos del catálogo</th></tr></thead><tr><td>Columna</td><td>Llave</td><td>Tipo</td><td>Tamaño</td></tr>');

    			    	$.each(data, function(i, item) {
							//Agregar el TR
        			    	$tr = $('<tr></tr>');

        			    	//Agregar el TD y el checkbox para el campo
        			    	$td = $('<td></td>');
        			    	$tr.append($td);
        			    	$input = $('<input type="checkbox" id="campos" name="campos[]" value="'+item.columnName+'" id="'+item.columnName+'" checked> '+item.columnName+ '</checkbox>');
        			    	$td.append($input);
        			    	$input.change(function(){
            			    	 var disabled = $(this).is(':checked');
            			    	 if (disabled == false)
            			    	 {
                			    	 $('input[name=pk'+item.columnName+']').attr('disabled' , 'disabled');
                			    	 $('select[name=type'+item.columnName+']').attr('disabled' , 'disabled');
                			    	 $('input[name=len'+item.columnName+']').attr('disabled' , 'disabled');
                			     }
            			    	 else
            			    	 {
            			    		 $('input[name=pk'+item.columnName+']').removeAttr('disabled');
            			    		 $('select[name=type'+item.columnName+']').removeAttr('disabled');
            			    		 $('input[name=len'+item.columnName+']').removeAttr('disabled');
                			     }
            			    	});

        			    	//Agregar el TD y el checkbox para llaves primarias
        			    	$td = $('<td></td>');
        			    	$tr.append($td);
        			    	$input = $('<input type="checkbox" name="pk'+item.columnName+'" id="pkcampo">Llave primaria</checkbox>');
        			    	$td.append($input);
        			    	//$('#enviardatos').append($input);
							
							$input.change(function(){
								
								var datos = "";
								$('#pkcampo:checked').each(function(i, item) {
									datos += $(item).attr("name").substr(2)+'|';
								});	
								
								datos = datos.substring(0,datos.length-1);
													
								$.ajax({
								   type: "GET",
								   url: '/<?php echo DIR_SIIGS.'/catalogo/checkpk/';?>'+datos,
								  })
								 .done(function(dato)
							   		{
									if (dato == 'false')
									{
										alert('La llave primaria contiene registros repetidos, por favor corrija sus datos e intente de nuevo.');									    	            			    	 $('#submit').attr('disabled' , 'disabled');
									}
									else
										$('#submit').removeAttr("disabled");	
								});
							});					
							
        			    	//Agregar el TD y el combo para tipo de dato
        			    	$td = $('<td></td>');
        			    	$tr.append($td);
        			    	$input = $('<select name="type'+item.columnName+'" id="typecampo"></select>');
        			    	$td.append($input);
								$.each(item.tiposDato , function(i,item2){
									$opt = $('<option value="'+item2.valor+'">'+item2.valor+'</option>');
									$input.append($opt);
									});

        			    	//Agregar el TD y el checkbox para llaves primarias
        			    	$td = $('<td></td>');
        			    	$tr.append($td);
        			    	$input = $('<input type="textbox" name="len'+item.columnName+'" id="lencampo">');
        			    	$td.append($input);

        			    	$('#optcampos').append($tr);
    			    	});
    			}
			};
    	    $('#loadcsv').submit(function() {

    	        $(this).ajaxSubmit(options);
				return false;
    	    });

    	    $('#enviardatos').submit(function() {


    	        if ($('#nombre').val() == "")
    	        {
        	        alert('Debe especificar el nombre del catálogo');
        	        $('#nombre').focus();
					return false;
    	        }
    	        else
    	        {
        	        if ($('#llaves').val() == '0')
        	        {
        	        	alert('Este catálogo ya existe.');
            	        $('#nombre').focus();
    					return false;
                	}
        	    }

    	        if ($('#campos:checked').length == 0)
    	        {
        	        alert('No hay campos registrados para este catálogo');
        	        return false;
        	    }

    	        var bandera = true;
        	    $('#campos:checked').each(function(){
            	    if ($('input[name=len'+$(this).val()+']').val() =="")
            	    {
            	    alert('El campo de la longitud no puede estar vacio');
        	   		bandera = false;
        	   		$('input[name=len'+$(this).val()+']').focus();
        	   		return false;
            	    }
            	    else
            	    {

                	    if ($('select[name=type'+$(this).val()+']').val() =="decimal")
                	    {
                    	    if (!$('input[name=len'+$(this).val()+']').val().match(/^[1-9]{1,2},[1-9]{1,2}$/))
                    	    {
	                	    	alert('El dato para la longitud no es correcto');
	                	   		bandera = false;
	                	   		$('input[name=len'+$(this).val()+']').focus();
	                	   		return false;
                    	    }
                      	}
                	    else
                	    {
                    	    if (isNaN($('input[name=len'+$(this).val()+']').val()))
                    	    {
                    	    	alert('El dato para la longitud debe ser un valor entero positivo');
	                	   		bandera = false;
	                	   		$('input[name=len'+$(this).val()+']').focus();
	                	   		return false;
                            }
                      	}
                    }
            	    });

        	    if (bandera == false)
            	    return false;

    	        if ($('#pkcampo:checked').length == 0)
    	        {
        	        alert('No se ha asignado una llave primaria para el catálogo');
        	        return false;
        	    }
				else if ($('#pkcampo:checked').length > 1)
				{
					if (!confirm('Ha seleccionado una llave compuesta, desea continuar?'))
						return false;
					else
						$('#llaves').val('2');
				}
    	    });

    	});
</script>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<?php echo validation_errors(); ?>

<form method="post" enctype="application/x-www-form-urlencoded" id="loadcsv">
<table>
<tr>
<td><input type="file" name="archivocsv" id="btncsv"/></td>
<td><input type="button" name="btnload" id="btnload" value="Cargar Datos" onclick="$('#loadcsv').submit();"/></td>
</tr>
</table>
</form>

<?php echo form_open(DIR_SIIGS.'/catalogo/insert' , array('id' => 'enviardatos')) ?>
<table>
	<tr>
		<td><label for="nombre">Nombre (cat_)</label></td>
		<td><input type="text" name="nombre" id="nombre" value="<?php echo set_value('nombre', '');?>" />
		</td>
	</tr>
	<tr>
<td colspan = 2>
</td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="datoscsv">
				<table id="optcampos">
					<thead><tr><th colspan=2>Campos del catálogo</th></tr></thead>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<input type="hidden" name="llaves" id="llaves" value="1" />
			<input type="submit" name="submit" id="submit" value="Guardar" />
		<td>
	</tr>
</table>
</form>