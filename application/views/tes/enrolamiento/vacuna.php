
<script>
function vacunacion(control,id,tiene,fecha,prioridad,ff,latvac,lonvac,tempvac)
{
	if($("#id_x").val()!="")
	{
		if(prioridad==1)
			color="#ffcc0a";
		else if(prioridad==2)
			color="#e7232a";
		else if(prioridad==3)
			color="#ff0085";
		else if(prioridad==10)
			color="#61ac1e";
		else
			color='#61ac1e';
	}
	else
		color='#61ac1e';
	if(tiene=="X")
	{

        console.log("fecha: "+fecha);
		fv=fecha.substr(fecha.search("Fecha Aplicada: ")+16,fecha.length);
        console.log("fv: "+fv);
		$('#'+control).html("<span style='margin-left:-9px;color:#fff; font-size:20px;'>"+tiene+
                "<input type='hidden' name='vacuna[]' value='"+id+"'>\n\
                 <input type='hidden' name='fvacuna[]' id='fv"+control+"' value='"+fv+"'>\n\
                 <input type='hidden' name='ffoliovacuna[]' id='ff"+control+"' value='"+ff+"'>\n\
                 <input type='hidden' name='lat_vacuna[]' id='latvac"+control+"' value='"+latvac+"'>\n\
                 <input type='hidden' name='long_vacuna[]' id='lonvac"+control+"' value='"+lonvac+"'>\n\
                 <input type='hidden' name='temp_vacuna[]' id='tempvac"+control+"' value='"+tempvac+"'>\n\
            </span>");
	}
	else
	$('#'+control).html("<span style='margin-left:-8px;color:#fff'>\n\
                <span id='fecha"+control+"' style='margin-left:-5px;'></span>\n\
                <input type='hidden' name='vacuna[]' value='"+id+"'>\n\
                <input type='hidden' name='fvacuna[]' id='fv"+control+"' value=''>\n\
                <input type='hidden' name='ffoliovacuna[]' id='ff"+control+"' value=''>\n\
                <input type='hidden' name='lat_vacuna[]' id='latvac"+control+"' value=''>\n\
                <input type='hidden' name='long_vacuna[]' id='lonvac"+control+"' value=''>\n\
                <input type='hidden' name='temp_vacuna[]' id='tempvac"+control+"' value=''>\n\
            </span>");
	$('#'+control).attr("title",fecha);
	$('#'+control).css({'background-color':color,'cursor':'pointer'});
	$('#'+control).click(function(e) {
		$("#control").val(control);
		$("#fecha").val($("#fv"+control).val());
		$("#folio").val($("#ff"+control).val());
        $("#temperatura").val($("#tempvac"+control).val());
        $( "#dialog-form" ).dialog( "open" );
    });
	
	$("a#similar").fancybox({
		'width'             : '300px',
		'height'            : '300px',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',
	});
}
$(document).ready(function()
{
	$( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 350,
      width: 300,
      modal: true,
	  resizable: false,
      buttons: {
        "Ok": function() {
			ctr=$("#control").val();
            $("#fv"+ctr).val($("#fecha").val());
			$("#ff"+ctr).val($("#folio").val());
			$("#fecha"+ctr).html($("#fecha").val());
			
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    
                    $("#latvac"+ctr).val(lat);
                    $("#lonvac"+ctr).val(lon);
                    
                }, errorLatLong);
            } else {
                alert('ERROR: Tu navegador no soporta geolocalización');
            }
            
			$("#tempvac"+ctr).val($("#temperatura").val());
			
            $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
	$("#fecha").datepicker(optionsFecha );
});

function errorLatLong(error) {
    msgError = 'Error al obtener la geolocalización del usuario';
    
    switch(error.code) {
        case error.PERMISSION_DENIED:
            msgError = "Error: El usuario denego el acceso a su geolocalización";
            break;
        case error.POSITION_UNAVAILABLE:
            msgError = "Error: La geolocalización no esta disponible";
            break;
        case error.TIMEOUT:
            msgError = "Error: La solicitud para obtener la geolocalización del usuario superó el tiempo de espera";
            break;
        case error.UNKNOWN_ERROR:
            msgError = "Error desconocido al obtener la geolocalización del usuario";
            break;
    }
    
    alert(msgError);
}
</script>
<style>
.ui-dialog .ui-dialog-titlebar-close span
{
display: block;
margin: 1px;
margin-left: -8px;
margin-top: -7px;
}
</style>
<div id="dialog-form" title="Agregar Vacuna">
  <fieldset>
    <label for="fecha">Fecha: </label>
    <input type="text" name="fecha" id="fecha" class="text ui-widget-content ui-corner-all">
    <label for="folio">Folio: </label>
    <input type="text" name="folio" id="folio" value="" class="text ui-widget-content ui-corner-all">
    <label for="temperatura">Temperatura: </label>
    <input type="number" name="temperatura" id="temperatura" value="" class="text ui-widget-content ui-corner-all">
    <input type="hidden" id="control" name="control" value="">
  </fieldset>
</div>
<h2 style="margin-left:10px;">
  <?php if(isset($vacunacion)){
	  $ci = &get_instance();
	  $ci->load->model(DIR_TES.'/Reporte_sincronizacion_model');
	  ?>
  
  <table width="100%"><tr>
  <th width="20%" align="left">Numero de días: </th><td width="13%" align="left"><?php echo $vacunacion[0]->dias ?></td>
  <th width="20%" align="left">Numero de Meses: </th><td width="13%" align="left">
  <?php
    $datetime1 = date_create($fecha);
    $datetime2 = date_create(date("Y-m-d"));
    $interval  = date_diff($datetime1, $datetime2);
    echo round(($vacunacion[0]->dias/365.25)*12);
  ?></td>
  <th width="20%" align="left">Numero de Años: </th><td width="14%"><?php echo $interval->format('%y'); ?></td>
  </tr></table><?php }?></h2>
   <table width="100%" border="0" class="table vacuna" style="margin-left:10px" cellpadding="0" cellspacing="0">
       <thead bgcolor="#e8eced">
          <tr>
            <th width="30%" rowspan="2" scope="col" style="color:#333; font-size:24px;">Vacuna</th>
            <th colspan="6" scope="col" style="color:#333; font-size:18px;">Dosis</th>
          </tr>
          <tr style="color:#4d4d4d; font-size:14px;">
            <th width="8%" scope="col">U</th>                             
            <th width="8%" scope="col">1a</th>
            <th width="8%" scope="col">2a</th>
            <th width="8%" scope="col">3a</th>
            <th width="8%" scope="col">4a</th>
            <th width="8%" scope="col">R</th>
          </tr>
       </thead>
       <?php 
        $tem=0; $i=0; $a=0; 
        if(isset($vacunacion))
       foreach($vacunacion as $vacuna)
       {
           $vc=$vacuna->descripcion." ";
           $color=array("","#005fae","#f78f24","#0094c9","#c9bb00","#006a5a","#00a7e6","#f499c1","#794098","#b73092","#f6945c");
        if($tem!=stripos($vc," ")||count(explode(" ",$vc))==2)
        {
            $a++;
         echo " 
          <tr>
            <td ><div id='var0$i' style='padding-left:14px; background-color:".(array_key_exists($a, $color) ? $color[$a] : '')."; color:white;font-size:16px;'>".str_replace('Primera','',$vc)."</div></td>
            <td ><div id='var1$i' align='center'></div></td>                                
            <td ><div id='var3$i' align='center'></div></td>
            <td ><div id='var4$i' align='center'></div></td>
            <td ><div id='var5$i' align='center'></div></td>
            <td ><div id='var6$i' align='center'></div></td>
            <td ><div id='var2$i' align='center'></div></td>
          </tr>";
        }
        $tem=stripos($vc," ");
        if($vacuna->tiene=='No aplicado')
        {
            
        }
		
		if($vacuna->descripcion=="Sabin"||$vacuna->descripcion=="SR")				  
		{
			$vac1=$vacuna->id_vacuna;
			$vaf1=$vacuna->fecha;
			$vcb1=$vacuna->codigo_barras;
			$latitud=$vacuna->latitud;
			$longitud=$vacuna->longitud;
			$temperatura=$vacuna->temperatura;
			$vat1=$vacuna->tiene;
			$total=$ci->Reporte_sincronizacion_model->getCount("", "select id_vacuna from cns_control_vacuna where id_persona='$id_x' and id_vacuna='$vac1'");
			$y=0;
			$x=0;
			for($m=1;$m<8;$m++)
			{
				$y++;
				if($total<$y)$vat1="";
				$va=$ci->Reporte_sincronizacion_model->getListado("SELECT * FROM cns_control_vacuna WHERE id_persona='$id_x' and id_vacuna='$vac1' limit $x, 1");
				if($va)
				{
					$x++;
					$vaf1="Fecha Aplicada: ".date("d-m-Y",strtotime($va[0]->fecha));
				}
				?>
                
                <script>
					vacunacion("var<?php if($m==7)echo '2'.$i; else if($m==2){$m++;echo '3'.$i;} else echo $m.$i?>",'<?php echo $vac1 ?>','<?php echo $vat1 ?>','<?php echo $vaf1 ?>','10','<?php echo $vcb1 ?>','<?php echo $latitud ?>','<?php echo $longitud ?>','<?php echo $temperatura ?>');
				</script>
                <?php
			}
		}
		if(stripos($vacuna->descripcion,"nfluenza Re"))			  
		{
			$vac1=$vacuna->id_vacuna;
			$vaf1=$vacuna->fecha;
			$vcb1=$vacuna->codigo_barras;
            $latitud=$vacuna->latitud;
			$longitud=$vacuna->longitud;
			$temperatura=$vacuna->temperatura;
			$vat1=$vacuna->tiene;
			$total=$ci->Reporte_sincronizacion_model->getCount("", "select id_vacuna from cns_control_vacuna where id_persona='$id_x' and id_vacuna='$vac1'");
			$y=0; $x=0;
			for($m=5;$m<8;$m++)
			{
				$y++;
				if($total<$y)$vat1="";
				$va=$ci->Reporte_sincronizacion_model->getListado("SELECT * FROM cns_control_vacuna WHERE id_persona='$id_x' and id_vacuna='$vac1' limit $x, 1");
				if($va)
				{
					$x++;
					$vaf1="Fecha Aplicada: ".date("d-m-Y",strtotime($va[0]->fecha));
				}
				?>
                
                <script>
					vacunacion("var<?php if($m==7)echo "2".($i-2); else echo $m.($i-2)?>",'<?php echo $vac1 ?>','<?php echo $vat1 ?>','<?php echo $vaf1 ?>','10','<?php echo $vcb1 ?>','<?php echo $latitud ?>','<?php echo $longitud ?>','<?php echo $temperatura ?>');
				</script>
                <?php
			}
		}
	
		if(stripos($vc,"nica"))   
		{?>
		<script>
			vacunacion("var1<?php echo $i?>",'<?php echo $vacuna->id_vacuna ?>','<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>','<?php echo $vacuna->codigo_barras ?>','<?php echo $vacuna->latitud ?>','<?php echo $vacuna->longitud ?>','<?php echo $vacuna->temperatura ?>');
		</script><?php 
		}
		else if(stripos($vc,"efuerzo"))
		{?>
		<script>
			vacunacion("var2<?php if(stripos($vc,"PT"))echo $i; else if(stripos($vc,"RP"))echo $i-1; else if(stripos($vc,"efuerzo")) echo $i-2; else echo $i?>",'<?php echo $vacuna->id_vacuna ?>','<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>','<?php echo $vacuna->codigo_barras ?>','<?php echo $vacuna->latitud ?>','<?php echo $vacuna->longitud ?>','<?php echo $vacuna->temperatura ?>');
		</script><?php 
		}
		else if(stripos($vc,"rimera")) 
		{?>
		<script>
			vacunacion("var3<?php echo $i?>",'<?php echo $vacuna->id_vacuna ?>','<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>','<?php echo $vacuna->codigo_barras ?>','<?php echo $vacuna->latitud ?>','<?php echo $vacuna->longitud ?>','<?php echo $vacuna->temperatura ?>');
		</script><?php 
		}
		else if(stripos($vc,"egunda")) 
		{?>
		<script>
			vacunacion("var4<?php echo $i-1?>",'<?php echo $vacuna->id_vacuna ?>','<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>','<?php echo $vacuna->codigo_barras ?>','<?php echo $vacuna->latitud ?>','<?php echo $vacuna->longitud ?>','<?php echo $vacuna->temperatura ?>');
		</script><?php 
		}
		else if(stripos($vc,"rcera"))  
		{?>
		<script>
			vacunacion("var5<?php echo $i-2?>",'<?php echo $vacuna->id_vacuna ?>','<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>','<?php echo $vacuna->codigo_barras ?>','<?php echo $vacuna->latitud ?>','<?php echo $vacuna->longitud ?>','<?php echo $vacuna->temperatura ?>');
		</script><?php 
		}
		else if(stripos($vc,"uarta"))  
		{?>
		<script>
			vacunacion("var6<?php echo $i-3?>",'<?php echo $vacuna->id_vacuna ?>','<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>','<?php echo $vacuna->codigo_barras ?>','<?php echo $vacuna->latitud ?>','<?php echo $vacuna->longitud ?>','<?php echo $vacuna->temperatura ?>');
		</script><?php 
		}
		$i++;
   }
	?>
</table>
<input type="hidden" value="<?php echo $id_x;?>" id="id_x" />