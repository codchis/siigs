<script type="text/javascript" src="/resources/js/jquery.form.min.js" /></script>
<script type="text/javascript">
      $(document).ready(function() {
          
          //Script para campos obligatorios
          obligatorios('enviardatos');

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
                                
                                var eserror = false;
    			    	$.each(data, function(i, item) {
							//Agregar el TR
        			    	$tr = $('<tr></tr>');
                                        
                                        if (typeof(item) == 'string')
                                        {
                                        if (!eserror)
                                        eserror = (item == 'Error');
                                
                                        $td = $('<td>'+((item != 'Error' && item != 'Ok') ? '<div class="'+((eserror) ? 'warning' : 'info')+'">' : '<div>') + item +'</div></td>');
			    		$tr.append($td);
			    		
                                        }
                                        else
                                        {
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
                                            $input = $('<input type="checkbox" name="pk'+item.columnName+'" id="pkcampo" '+ ((item.columnName.indexOf('id_')==-1) ? 'disabled' : '') +'>Llave primaria</checkbox>');
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

                                            //seleccionar por default el valor varchar
                                            $input.val('varchar');
                                            //Agregar la revisión de tipos de datos
                                            //por columnas
                                            $input.change(function(){
                                                $.ajax({
                                                context:this,
                                                type: "GET",
                                                url: '/<?php echo DIR_SIIGS.'/catalogo/checkTypeData/';?>'+item.columnName+'/'+$(this).val(),
                                            })
                                            .done(function(dato)
                                            {
                                                    if (dato == 'false')
                                                    {
                                                            alert('El tipo de dato no coincide con los datos del archivo CSV');
                                                            $(this).val('varchar');
                                                    }
                                                    });
                                            });
                                            //Agregar el TD y el checkbox para llaves primarias
                                            $td = $('<td></td>');
                                            $tr.append($td);
                                            $input = $('<input type="textbox" name="len'+item.columnName+'" id="lencampo">');
                                            $td.append($input);
                                        }
        			    	$('#optcampos').append($tr);
    			    	});
    			}
			};
    	    $('#loadcsv').submit(function() {
                
                var filename = $("#btncsv").val();
                var extension = filename.replace(/^.*\./, '');
    	        if (extension == filename || extension.toLowerCase() != 'csv')
                {
                    alert('Solo se aceptan archivos con extensión csv');
                    return false;
                }
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
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php echo validation_errors(); ?>
<div class="table table-striped">
<form method="post" enctype="application/x-www-form-urlencoded" id="loadcsv">
<table>
<tr>
<td>[Archivo csv separado por comas]<input type="file" name="archivocsv" id="btncsv"/></td>
<td><button type="button" name="btnload" id="btnload" onclick="$('#loadcsv').submit();" class="btn btn-primary">Cargar Datos<i class="icon-upload"></i></button></td>
</tr>
</table>
</form>

<?php echo form_open(DIR_SIIGS.'/catalogo/insert' , array('onkeyup' => 'limpiaformulario(this.id)','id' => 'enviardatos')) ?>
    
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div> 
<table>
	<tr>
		<td><label for="nombre">Nombre (cat_)</label></td>
		<td><input type="text" name="nombre" id="nombre" title='requiere' required value="<?php echo set_value('nombre', '');?>" />
		</td>
	</tr>
	<tr>
		<td><label for="nombre">Comentario:</label></td>
                <td><textarea type="text" name="comentario" id="comentario" ></textarea>
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
                        <button type="submit" name="submit" id="submit" class="btn btn-primary" onclick="return validarFormulario('enviardatos')">Guardar<i class="icon-hdd"></i></button>
                        <button type="button" name="cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/catalogo/'" class="btn btn-primary">Cancelar<i class="icon-arrow-left"></i></button>
		<td>
	</tr>
</table>
</form>
</div>