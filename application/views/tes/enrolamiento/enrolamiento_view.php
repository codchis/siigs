    <link href="/resources/css/grid.css" rel="stylesheet" type="text/css" /> 
    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    
    <link href="/resources/flot/jquery.flot.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/resources/flot/excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="/resources/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="/resources/flot/jquery.flot.axislabels.js"></script>
<?php 
if($enrolado)
{
	$ci = &get_instance();
	$ci->load->model(DIR_TES.'/Reporte_sincronizacion_model');
?>
    <script>
    peso_edad = <?php echo $peso_edad; ?>;
    peso_talla = <?php echo $peso_talla; ?>;
    talla_edad = <?php echo $talla_edad; ?>;
    imc = <?php echo $imc; ?>;
    peri_cefa = <?php echo $peri_cefa; ?>;
    con_hemo = <?php echo $con_hemo; ?>;
    
	$(document).ready(function()
	{   
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php  echo $enrolado->id_asu_localidad_nacimiento;?>] ,
			'desglose':5 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
                if(obj.length == 0) return null;
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("lnacimientoT").innerHTML=des;
			}
		});
		
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php  echo $enrolado->id_asu_um_tratante;?>] ,
			'desglose':5 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
                if(obj.length == 0) return null;
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("umt").innerHTML=des;
			}
		});
		
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php  echo $enrolado->id_localidad_registro_civil;?>] ,
			'desglose':5 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
                if(obj.length == 0) return null;
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("lugarcivilT").innerHTML=des;
			}
		});
		
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php  echo $enrolado->id_asu_localidad_domicilio;?>] ,
			'desglose':5 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
                if(obj.length == 0) return null;
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("localidadT").innerHTML=des;
			}
		});
        
        $('#cambiar_grafica').change(function(event){
            seleccion = $(this).val();
            titulo = $(this).children('option:selected').text();
            
            switch(seleccion){
                case 'peso_edad':
                    dibujaGrafica(peso_edad.series, peso_edad.labels, "flot-graph");
                    break;
                case 'peso_talla':
                    dibujaGrafica(peso_talla.series, peso_talla.labels, "flot-graph");
                    break;
                case 'talla_edad':
                    dibujaGrafica(talla_edad.series, talla_edad.labels, "flot-graph");
                    break;
                case 'imc':
                    dibujaGrafica(imc.series, imc.labels, "flot-graph");
                    break;
                case 'peri_cefa':
                    dibujaGrafica(peri_cefa.series, peri_cefa.labels, "flot-graph");
                    break;
                case 'con_hemo':
                    dibujaGrafica(con_hemo.series, con_hemo.labels, "flot-graph");
                    break;
            }
            
            $("#titulo_grafica").text(titulo);
        });
        
        dibujaGrafica(peso_edad.series, peso_edad.labels, "flot-graph");
	});
    
function vacunacion(id,tiene,fecha,prioridad)
{
	if(prioridad==1)
		color="#ffcc0a";
	else if(prioridad==2)
		color="#e7232a";
	else if(prioridad==3)
		color="#ff0085";
	else if(prioridad==10)
		color="#7EDA2C";
	else
		color='#61ac1e';
	if(tiene=="X")
	$('#'+id).html("<span style='margin-left:-9px;color:#fff; font-size:20px;'><?php echo VACUNA_APLICADA; ?></span>");
	else
	$('#'+id).html("<span style='margin-left:-8px;'>"+tiene+"</span>");
	$('#'+id).attr("title",fecha);
	$('#'+id).css({'background-color':color,'cursor':'pointer'});
}
	</script>
    <style>
	td p
	{
		font-family:Open Sans Condensed ,sans-serif; font-size: 18px; font-weight: bold; text-shadow: 0 0px 0 #FFFFFF;
	}
	</style>
	<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>
        	<table width="100%">
            <tr>
                <td>
                  <div id="Accordion1" class="Accordion" tabindex="0" style="margin-left:-20px;">
                  
                  <!-- Datos basicos -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/dbasicos.png"/>Datos Basicos</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nombre; $id_x=$enrolado->id;?></div></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->sexo;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->apellido_paterno;?></div></td>
                            <td><p align="right">Tipo de Sangre</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->tsangre;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->apellido_materno;?></div></td>
                            <td><p align="right">Fecha de Nacimiento</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo date("d-m-Y",strtotime($enrolado->fecha_nacimiento));?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><div id="lnacimientoT" style="width:100%; margin-left:20px;"></div></td>
                            </tr>
                          <tr>
                            <td><p align="right">Pre CURP</p></td>
                            <td ><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->curp;?></div></td>
                            <td><p align="right">Nacionalidad</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nacionalidad;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Parto Multiple</p></td>
                            <td ><div style="width:100%; margin-left:20px; margin-top:-5px" id="parto"><?php  echo $enrolado->parto;?></div></td>
                            <td><p align="right">Tamiz neonatal</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php echo (($enrolado->tamiz_neonatal == null)?"Se ignora":(($enrolado->tamiz_neonatal == 1) ? "Si" : "No")); ?></div></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!-- Tutor -->
                  
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/madre.png"/>Datos de la Madre o Tutor</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nombreT;?></div></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->sexoT;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->paternoT;?></div></td>
                            <td><p align="right">Pre CURP</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->curpT;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->maternoT;?></div></td>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->telefonoT;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Celular</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->celularT;?></div></td>
                            <td><p align="right">Compania Celular</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->operadoraT;?></div></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!-- Direccion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/domicilio.png"/>Domicilio</div>
                      <div class="AccordionPanelContent">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Calle</p></td>
                            <td width="31%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->calle_domicilio;?></div></td>
                            <td width="25%"><p align="right">Número</p></td>
                            <td width="25%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->numero_domicilio;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Referencia</p></td>
                            <td colspan="3"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->referencia_domicilio;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Colonia</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->colonia_domicilio;?></div></td>
                            <td><p align="right">CP</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->cp_domicilio;?></div></td>
                          </tr>
                          <tr>
                          <td colspan="4" width="100%">
                              <table width="89%" border="0">
                                <tr>
                                  <td width="22%" align="right"><p>Ageb&nbsp;</p></td>
                                  <td ><div style="width:75%; margin-left:15px; margin-top:-3px"><?php  echo $enrolado->ageb;?></div></td>
                                  <td  align="right"><p>Sector</p></td>
                                  <td ><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->sector;?></div></td>
                                  <td  align="right"><p>Manzana</p></td>
                                  <td width="16%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->manzana;?></div></td>
                                </tr>
                              </table>
                          </td>
                          </tr>
                          <tr>
                            <td><p align="right">Localidad</p></td>
                            <td colspan="3"><div id="localidadT" style="width:100%; margin-left:20px;"></div></tr>
                          <tr>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->telefono_domicilio;?></div></td> 
                            <td><p align="right">Celular</p></td> 
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->celular;?></div></td>                          
                          </tr>
                          <tr>
                            <td><p align="right">Compania Celular</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->operadora;?></div></td> 
                            <td></td> 
                            <td></td>                          
                          </tr>
                        </table>
                        <br />
                      </div>
                    </div>
                    
                    <!-- Tipo de Beneficiario:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/derecho.png"/>Derechohabiencia</div>
                      <div class="AccordionPanelContent"><br />
                      	
                        	<div style="width:100%; margin-left:20px; margin-top:-5px"><?php  
							$i=0;$a=0;
							$opcion='<table width="85%"><tr>';
							foreach ($afiliaciones as $afiliacion):
								$id= $afiliacion->id;
								$descripcion= $afiliacion->descripcion;
								if($a==2){$opcion.="</tr><tr>"; $a=0;}
								$opcion.="<td width='33%' valign='top'><label><input type='checkbox' value='$id' checked disabled> $descripcion</label></td>";
								$i++;$a++;
							endforeach; 
							$opcion.='</tr></table>';
							echo $opcion;
							?></div>
                        
                      </div>
                    </div>
                    
                    
                    <!--  Unidad Medica Tratante -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/unidadm.png"/>Unidad Medica de Responsabilidad</div>
                      <div class="AccordionPanelContent" >
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                      
                          <tr>
                            <td width="19%" valign="middle"><p align="right">Lugar</p></td>
                            <td width="81%" colspan="3">
                            <span style="font-size:12px; margin-left:20px; font-style:italic;">um, localidad ,municipio, estado</span>
                            <div id="umt" style="width:100%; margin-left:20px;"></div>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!--  Registro civil -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/rcivil.png"/>Registro Civil</div>
                      <div class="AccordionPanelContent" >
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Fecha</p></td>
                            <td width="31%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->fecha_registro;?></div></td>
                            <td width="25%"><p align="right">&nbsp;</p></td>
                            <td width="25%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar</p></td>
                            <td colspan="3"><div id="lugarcivilT" style="width:100%; margin-left:20px;"></div></tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    
                    
                    <!-- alergias y reacciones:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/halergias.png"/>Alergias y Antecedentes Heredero Familiar de Riesgo</div>
                      <div class="AccordionPanelContent"><br />
                      	
                        	<div style="width:100%; margin-left:20px; margin-top:-5px"><?php  
							$i=0;$a=0;
							$opcion='<table width="85%"><tr>';
							foreach ($alergias as $alergia):
								$id= $alergia->id;
								$descripcion= $alergia->descripcion;
								if($a==3){$opcion.="</tr><tr>"; $a=0;}
								$opcion.="<td width='33%' valign='top'><label><input type='checkbox' value='$id' checked disabled> $descripcion</label></td>";
								$i++;$a++;
							endforeach; 
							$opcion.='</tr></table>';
							echo $opcion;
							?></div>
                            
                        
                      </div>
                    </div>
                    
                    
                    <!-- vacunacion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/vacunacion.png"/>Control de Vacunación</div>
                      <div class="AccordionPanelContent" >
                      <h2 style="margin-left:10px;">
                      <?php if(isset($vacunacion)){?>
                      <table width="100%"><tr>
                      <th width="20%" align="left">Numero de días: </th><td width="13%" align="left"><?php echo $vacunacion[0]->dias ?></td>
                      <th width="20%" align="left">Numero de Meses: </th><td width="13%" align="left">
					  <?php
					  	$datetime1 = date_create($enrolado->fecha_nacimiento);
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
							$tem=0; $i=0;
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
                           		<td ><div id='var0$i' style='padding-left:14px; background-color:".@$color[$a]."; color:white;font-size:16px;'>".str_replace('Primera','',$vc)."</div></td>
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
					vacunacion("var<?php if($m==7)echo '2'.$i; else if($m==2){$m++;echo '3'.$i;} else echo $m.$i?>",'<?php echo $vat1 ?>','<?php echo $vaf1 ?>','10');
				</script>
                <?php
			}
		}
		if(stripos($vacuna->descripcion,"nfluenza Re"))			  
		{
			$vac1=$vacuna->id_vacuna;
			$vaf1=$vacuna->fecha;
			$vcb1=$vacuna->codigo_barras;
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
					vacunacion("var<?php if($m==7)echo "2".($i-2); else echo $m.($i-2)?>",'<?php echo $vat1 ?>','<?php echo $vaf1 ?>','10');
				</script>
                <?php
			}
		}
						  
if(stripos($vc,"nica"))   
{?>
<script>
	vacunacion("var1<?php echo $i?>",'<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>');
</script><?php 
}
if(stripos($vc,"efuerzo")||stripos($vc,"evacunaci"))
{?>
<script>
	vacunacion("var2<?php if(stripos($vc,"RP"))echo $i-1; else if(stripos($vc,"efuerzo")||stripos($vc,"evacunaci")) echo $i-2; else echo $i?>",'<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>');
</script><?php 
}
if(stripos($vc,"rimera")) 
{?>
<script>
	vacunacion("var3<?php echo $i?>",'<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>');
</script><?php 
}
if(stripos($vc,"egunda")) 
{?>
<script>
	vacunacion("var4<?php echo $i-1?>",'<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>');
</script><?php 
}
if(stripos($vc,"rcera"))  
{?>
<script>
	vacunacion("var5<?php echo $i-2?>",'<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>');
</script><?php 
}
if(stripos($vc,"uarta"))  
{?>
<script>
	vacunacion("var6<?php echo $i-3?>",'<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>');
</script><?php 
}
$i++;
						   }
							?>
                        </table>

                      </div>
                    </div>
                    
                    
                    <!-- consulta  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/consultas.png"/>Control de Consultas</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:96%">
                        
                            <table id="tabla_consultas" style="width: 100%;">
                                <thead style="color: #000; font-size: 14px; font-weight: bold; background-color: #e8eced; border: 0px solid #DF7000;">
                                    <tr style="height:35px;">
                                        <th>No.</th>
                                        <th>Padecimiento</th>
                                        <th>Fecha</th>
                                        <th>Tratamiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?PHP if(!empty($consultas)) {
                                        if(count($consultas) >0 ) {
                                            for($index=0; $index<sizeof($consultas); $index++){
                                                echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                    <td align="center"><label>'.($index+1).'</label></td>
                                                    <td>
                                                        <label class="enfermedad_consulta">'.$consultas[$index]->descripCIE10.'</label>
                                                    </td>
                                                    <td>
                                                        <label class="fecha_consulta">'.date('d-m-Y', strtotime($consultas[$index]->fecha)).'</label>
                                                    </td>
                                                    <td>
                                                        <label class="tratamiento_consulta">'.$consultas[$index]->descripTratamiento.'</label>
                                                    </td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                    <td align="center" colspan="4"><label>No hay datos</label></td>
                                                </tr>';
                                        }
                                    } else {
                                        echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                <td align="center" colspan="4"><label>No hay datos</label></td>
                                            </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        
                        </div>
                      </div>
                    </div>
                    
                    <!-- accion nutricional  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/nutricion.png"/>Control de Acción Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        <table width="100%">
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%; margin-left:20px; margin-top:-3px">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">A. Nutriconal</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo getArrayView($nutricionales);?></div>                          
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                            
                            <h4>Registro de entrega de Sales de Rehidratación Oral</h4>
                            <table id="tabla_sro" style="min-width: 50%;">
                                <thead style="color: #000; font-size: 14px; font-weight: bold; background-color: #e8eced; border: 0px solid #DF7000;">
                                    <tr style="height:35px;">
                                        <th>No.</th>
                                        <th>Fecha</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?PHP if(!empty($sales)) {
                                        if(count($sales) >0 ) {
                                            for($index=0; $index<sizeof($sales); $index++){
                                                echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                    <td align="center"><label>'.($index+1).'</label></td>
                                                    <td align="center"><label>'.date('d-m-Y', strtotime($sales[$index]->fecha)).'</label></td>
                                                    <td align="center"><label>'.$sales[$index]->cantidad.'</label></td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                    <td align="center" colspan="3"><label>No hay datos</label></td>
                                                </tr>';
                                        }
                                    } else {
                                        echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                <td align="center" colspan="3"><label>No hay datos</label></td>
                                            </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                      </div>
                    </div>
                    
                    <!-- estimulación temprana  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/consultas.png"/>Estimulación temprana</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        
                            <table id="tabla_estimulacion" style="width: 80%;" align="center">
                                <thead style="color: #000; font-size: 14px; font-weight: bold; background-color: #e8eced; border: 0px solid #DF7000;">
                                    <tr style="height:35px;">
                                        <th>No.</th>
                                        <th>Fecha</th>
                                        <th>Tutor capacitado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?PHP 
                                    if(!empty($estimulacion_temprana)) {
                                        if(count($estimulacion_temprana) >0 ) {
                                            for($index=0; $index<sizeof($estimulacion_temprana); $index++){
                                                echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                    <td align="center">'.($index+1).'</td>
                                                    <td align="center">
                                                        <label>'.date('d-m-Y', strtotime($estimulacion_temprana[$index]->fecha)).'</label>
                                                    </td>
                                                    <td align="center">
                                                        <label>'.($estimulacion_temprana[$index]->tutor_capacitado == 1 ? 'Si' : 'No').'</label>
                                                    </td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                    <td align="center" colspan="3"><label>No hay datos</label></td>
                                                </tr>';
                                        }
                                    } else {
                                        echo '<tr style="background-color: #f0f0f0; padding-top:4px; padding-bottom:4px; height:50px;">
                                                <td align="center" colspan="3"><label>No hay datos</label></td>
                                            </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        
                        </div>
                      </div>
                    </div>
                    
                    <!-- Gráficas -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab"><img src="/resources/images/altura.png"/>Gráficas Control Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                          &nbsp;  Cambiar Gráfica: 
                          <select id="cambiar_grafica">
                            <option value="peso_edad" selected="">Peso para edad</option>
                            <option value="peso_talla">Peso para talla</option>
                            <option value="talla_edad">Talla para edad</option>
                            <option value="imc">Índice de masa corporal</option>
                            <option value="peri_cefa">Perímetro cefálico</option>
                            <option value="con_hemo">Concentración de Hemoglobina</option>
                          </select>
                          
                          <h3 id="titulo_grafica" align="Center">Peso para edad</h3>
                        <div class="flot-container">
                            <div id="flot-graph" class="flot-placeholder"></div>
                        </div>
                      </div>
                    </div>  
                    
                    
                    </td>
            </tr>
            
        </table>
	</td></tr></table>
	<script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("Accordion1", { useFixedPanelHeights: false, defaultPanel: 0 });
</script>
<?php 
}
else
{
 echo "<div class='$infoclass'>".$msgResult."</div><div><br>";
 echo '<a href="" class="btn btn-primary" onclick="window.location.href=\'/'.DIR_TES.'/enrolamiento/\';return false;">Regresar</a></div>';
}
?>