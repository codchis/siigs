<script type="text/javascript">

      $(document).ready(function() {

    	  $('#frm_catalogo').submit(function(){

    			if($('#relaciones').val() == 0 && '<?php echo $nivel?>'!='1')
    			{
        			alert('Debe asignar al menos una relación entre los catálogos');
    				return false;
    			}
                        
                        if($('#columnas_descripcion').val() == "")
    			{
        			alert('Debe agregar por lo menos una columna a la descripción');
    				return false;
    			}
                        
    			
        	  });
    	  
	    	$('select[name=tabla_catalogo]').change(function(){

	    		var id = $(this).attr('id');
	    		var valor = $(this).val();

	    		if (valor == 0)
		    		return;

	    		$.ajax({
	                type: "GET",
	                url: '/<?php echo DIR_SIIGS.'/catalogo/view/';?>'+valor,
	                dataType : 'json',
	            })
	              .done(function(dato)
	                {
	 	                if (dato == 'false')
	 	                {
	 	                alert('Ocurrió un error al obtener los datos del catálogo');
	 	                return false;
	 	                }
	 	                else
	 	                {
	 	           	    	$td1 = $('<td></td>');
	 	           	    	$llaveselect = $('<select name="columna_llave"></select>');
	 	           	    	$td1.append($llaveselect);

	 	           	    	$campos = dato.llave.split('||');

	 	                	$.each($campos, function(i, item) {

		 	                	datos = item.split('|');
		 	                	$llaveselect.append('<option value="'+datos[0]+'">'+datos[0]+'</option>');
	 	                	});

	 	           	    	$td2 = $('<td></td>');
	 	           	    	$descselect = $('<select name="columna_descripcion"></select><br/><input type="text" name="columnas_descripcion" id="columnas_descripcion" value=""/>');
	 	           	    	$td2.append($descselect);
                                        
                                        $td3 = $('<td></td>');
                                        $botonagregar = $('<button type="button" class="btn btn-small btn-primary">Agregar<i class="icon-plus"></i></button>');
                                        $br = $('<br/><br/>');
                                        $botonlimpiar = $('<button type="button" class="btn btn-small btn-primary">Limpiar<i class="icon-remove-circle"></i></button>');
                                        $td3.append($botonagregar);
                                        $td3.append($br);
                                        $td3.append($botonlimpiar);
                                        
                                        $botonagregar.click(function(){
                                            $('input[name=columnas_descripcion]').val(
                                                    $('input[name=columnas_descripcion]').val() + 
                                                    (($('input[name=columnas_descripcion]').val().length == 0) ? '' : "+") + 
                                                    $('select[name=columna_descripcion]').val());
                                        });
                                        
                                        $botonlimpiar.click(function(){
                                            $('input[name=columnas_descripcion]').val('');
                                        });
                                        
                                        //Agrega las columnas llaves al combo
                                        $campos = dato.llave.split('||');

	 	                	$.each($campos, function(i, item) {

		 	                	datos = item.split('|');
		 	                	$descselect.append('<option value="'+datos[0]+'">'+datos[0]+'</option>');
	 	                	});

		 	           	//Agrega las columnas que no son llaves al combo
                                        $campos = dato.campos.split('||');

	 	                	$.each($campos, function(i, item) {

		 	                	datos = item.split('|');
		 	                	$descselect.append('<option value="'+datos[0]+'">'+datos[0]+'</option>');
	 	                	});
                                        

	 	                	$('#tdllave').html($td1);
	 	                	$('#tddesc').html($td2);
                                        $('#tddesc_boton').html($td3);

	 	                	//Limpiar las relaciones y cargar de nuevo los datos para relaciones

	 	                	if ('<?php echo $nivel?>'=='1')
		 	                	return;
	 	                	$('#relaciones_tablas').html('<thead><tr><th>Relaciones padre-hijo</th></tr></thead>');
	 	                	$td = $('<td colspan="2"></td>');
	 	                	$boton = $('<button type="button" class="btn btn-small btn-primary">Agregar<i class="icon-plus"></i></button>');
	 	                	$td.append($boton);

	 	                	$boton.click(function(){
	 	                		crearOpcionesRelacion($('#relaciones').val()/1+1,valor);
		 	                	});

	 	                	$('#relaciones_tablas thead tr').append($td);

	 	                	crearOpcionesRelacion(1,valor);

	 	                }

	 	                });

	         	  });

	       	  function crearOpcionesRelacion(nivel,valor)
	       	  {
		       	 $('#relaciones').val(nivel);
               	$selecthijo = $('<select name="relacionhijo'+nivel+'"></select>');
             	$tr = $('<tr id="tr'+nivel+'"></tr>');
             	$td = $('<td></td>');
             	$td.append($selecthijo);
             	$tr.append($td);

               	$selectpadre = $('<select name="relacionpadre'+nivel+'"></select>');
             	$td = $('<td></td>');
             	$td.append($selectpadre);
             	$tr.append($td);
				
				$boton = $('<button type="button" class="btn btn-small btn-primary">Quitar<i class="icon-remove"></i></button>');
             	$td = $('<td></td>');
             	$td.append($boton);
             	$tr.append($td);
				
				$boton.click(function(e){
					//console.log($('#tr'+nivel));
				     $('table#relaciones_tablas tr#tr'+nivel).remove();
				     $('table#relaciones_tablas tr#tr'+nivel).detach();
      		 		$('#relaciones').val($('#relaciones').val()-1);
					});

             	$('#relaciones_tablas').append($tr);

             	$.ajax({
	                type: "GET",
	                url: '/<?php echo DIR_SIIGS.'/catalogo/view/';?>'+valor,
	                dataType : 'json',
	            })
	              .done(function(dato)
	                {
	 	                if (dato == 'false')
	 	                {
	 	                alert('Ocurrió un error al obtener los datos del catálogo');
	 	                return false;
	 	                }
	 	                else
	 	                {
		 	           	    $campos = dato.campos.split('||');
		 	           	    $.each($campos, function(i, item) {
		 	                	datos = item.split('|');
		 	                	$selecthijo.append('<option value="'+datos[0]+'">'+datos[0]+'</option>');
	 	                	});

		 	           	    $campos = dato.llave.split('||');
	 	                	$.each($campos, function(i, item) {

		 	                	datos = item.split('|');
		 	                	$selecthijo.append('<option value="'+datos[0]+'">'+datos[0]+'</option>');
	 	                	});
			         }
                });

               	$.ajax({
	                type: "GET",
	                url: '/<?php echo DIR_SIIGS.'/catalogo/view/'.$catalogo_padre; ?>',
	                dataType : 'json',
	            })
	              .done(function(dato)
	                {
	 	                if (dato == 'false')
	 	                {
	 	                alert('Ocurrió un error al obtener los datos del catálogo');
	 	                return false;
	 	                }
	 	                else
	 	                {

		 	           	    $campos = dato.campos.split('||');
		 	           	    $.each($campos, function(i, item) {
		 	                	datos = item.split('|');
		 	                	$selectpadre.append('<option value="'+datos[0]+'">'+datos[0]+'</option>');
	 	                	});

		 	           	    $campos = dato.llave.split('||');
	 	                	$.each($campos, function(i, item) {

		 	                	datos = item.split('|');
		 	                	$selectpadre.append('<option value="'+datos[0]+'">'+datos[0]+'</option>');
	 	                	});
			         }
                });

	       	  }
      });
</script>

<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php echo validation_errors(); ?>
<div class="table table-striped">
<?php 
$atributos = array('id' => 'frm_catalogo');
echo form_open(DIR_SIIGS.'/catalogo_x_raiz/insert/'.$id_raiz , $atributos) 
?>
<table>
				<thead>
					<tr>
						<td>Nivel</td>
						<td>Catálogo</td>
						<td>Llave</td>
						<td>Descripción</td>
					</tr>
				</thead>
	<tr>
		<td>
		<label><?php echo $nivel;?></label>
		<input type="hidden" name="grado" value="<?php echo $nivel;?>"/>
		<input type="hidden" name="id_raiz" value="<?php echo $id_raiz;?>"/>
		<input type="hidden" name="relaciones" id="relaciones" value="0"/>
		</td>
		<td>
			<?php
			if (!empty($catalogos) && count($catalogos)>0)
			echo  form_dropdown('tabla_catalogo', $catalogos);
			else
				echo 'No hay catálogos creados';
			?>
		</td>
		<td id="tdllave"></td>
		<td id="tddesc"></td>
                <td id="tddesc_boton"></td>
	</tr>
	<tr>
		<td colspan=4>
			<table id="relaciones_tablas">
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=2>
                    <button type="submit" name="submit" value="Guardar" class="btn btn-primary">Guardar<i class="icon-hdd"></i></button>
                    <button type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/raiz/update/<?php echo $id_raiz;?>'" class="btn btn-primary">Cancelar<i class="icon-arrow-left"></i></button>
                <td>
	</tr>
</table>
</form>
</div>