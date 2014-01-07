<div id="loading" style="height:100px;width:100px;position:absolute;top:0px;left:0px;display:none;z-index:1000;background-color:black;">
    
</div>
<script>
$(document).ready(function(){
   
   $(document).bind("ajaxSend", function(){
   $("#loading").show();
 }).bind("ajaxComplete", function(){
   $("#loading").hide();
 }).bind("ajaxError",function(){
   $("#loading").hide();  
 });
 
});

function generarAsu(raiz)
{
	$.ajax({
        type: "GET",
        url: '/<?php echo DIR_SIIGS.'/raiz/createasu/';?>'+raiz,
    })
      .done(function(dato)
        {
          mensaje = (dato.indexOf('false') > -1) ? "Ocurrió un error al crear el Arbol de segmentación": "El arbol se ha construido correctamente";
          $('#resultados_asu').html(mensaje);
    });
}

function modificarAsu(raiz)
{
	$.ajax({
        type: "GET",
        url: '/<?php echo DIR_SIIGS.'/raiz/updateasu/';?>'+raiz,
    })
      .done(function(dato)
        {
          mensaje = (dato.indexOf('false') > -1) ? "Ocurrió un error al crear el Arbol de segmentación": "El arbol se ha construido correctamente";
          $('#resultados_asu').html(mensaje);
    });
}

function revisarCatalogo(ruta)
{
	console.log(ruta);
	$.ajax({
        type: "GET",
        url: ruta,
        dataType: 'json',
    })
    
      .done(function(dato)
        {
    	  	var mostrar = false;

          	$.each($(dato), function(i, item) {

              	if(item.length == undefined)
              	mostrar = true;
              	
          		if (item == 'false')
                {
	   	             alert('Ocurrió un error al obtener los datos del catálogo.');
	   	             return false;
                }
                if (item == 'true')
                {
	   	             alert('Todos los datos de este catálogo son consistentes.');
	   	             return false;
                }
         	});

         	if (mostrar == true)
         	{
         		alert('Se encontraron inconsistencias en el catálogo...');
                mostrarErrores(dato);
            }
        });
}

function mostrarErrores(data)
{
	div = $('<div class="ui-dialog" style="background:#fff;width:600px;"></div>');
	tbl_body = $('<table class="ui-dialog-content" style="width:100%"></table>');
	$(div).append(tbl_body);
	$.each(data, function() {
        var tbl_row = $('<tr></tr>');
        $.each(this, function(k , v) {

            tbl_col = $('<td>'+v+'</td>');
            $(tbl_row).append(tbl_col);
        })
        $(tbl_body).append(tbl_row);
    })
    alert(div);
    $(div).dialog({ width : 500 , closeOnEscape: true });
}
</script>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
 <?php
if (!empty($raiz_item))
{
?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/raiz/update/'.$raiz_item->id) ?>
<div class="table table-striped">
<table>
	<tr>
		<td><label for="descripcion">Descripci&oacute;n</label></td>
		<td><textarea name="descripcion"><?php echo $raiz_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
			<table id="raizcatalogo">
				<thead>
					<tr>
						<th><a href="<?php echo '/'.DIR_SIIGS.'/catalogo_x_raiz/insert/'.$raiz_item->id;?>"><input type="button" id="btnagregar" value="Agregar" class="btn btn-primary"></a></th>
						<th colspan=6><h2>Catálogos de la raiz</h2></th>
					</tr>
					<tr>
						<td></td>
						<td><h2>Nivel</h2></td>
						<td><h2>Catálogo</h2></td>
						<td><h2>Llave</h2></td>
						<td><h2>Descripción</h2></td>
						<td></td>
						<td></td>
					</tr>
					<?php foreach ($catalogos as $item):?>
						<tr>
						<td></td>
							<td><?php echo $item->grado_segmentacion ?></td>
							<td><?php echo $item->tabla_catalogo ?></td>
							<td><?php echo $item->nombre_columna_llave ?></td>
							<td><?php echo $item->nombre_columna_descripcion ?></td>
							<td><a class="btn btn-primary" onclick="revisarCatalogo('<?php echo '/'.DIR_SIIGS.'/catalogo_x_raiz/check/'.$item->id?>'); return false;">Validar</a></td>
							<td><a href="<?php echo '/'.DIR_SIIGS.'/catalogo_x_raiz/delete/'.$item->id;?>" class="btn btn-primary" onclick="if (!confirm('Al eliminar este catálogo de la raiz, podría causar inestabilidad al arbol de segmentación, desea continuar?')) {return false;}">Eliminar</a></td>
						</tr>
					<?php endforeach ?>
				<tr>
					<td>
					<?php if ($existe != true && false) {?>
						<input type="button" value="Crear ASU"  class="btn btn-primary" onclick="generarAsu('<?php echo $raiz_item->id;?>');" />
					<?php } else if (false) {?>
					<input type="button" value="Actualizar ASU"  class="btn btn-primary" onclick="modificarAsu('<?php echo $raiz_item->id;?>');" />
					<?php }?>
					</td>
					<td colspan=6>
					<div id="resultados_asu"></div>
					</td>
				</tr>				
				</thead>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $raiz_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/raiz/'" class="btn btn-primary" />
		<td>
	</tr>
</table>
</div>
</form>
<?php
}
else
{
echo "No se ha encontrado el elemento";
}
?>