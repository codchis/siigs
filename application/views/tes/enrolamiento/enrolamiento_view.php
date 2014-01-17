    <link href="/resources/css/grid.css" rel="stylesheet" type="text/css" /> 
    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<?php 
if($enrolado)
{
?>
    <script>
	$(document).ready(function()
	{
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php  echo $enrolado->id_asu_localidad_nacimiento;?>] ,
			'desglose':1 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				document.getElementById("lnacimientoT").innerHTML=obj[0]["descripcion"];
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
				document.getElementById("umt").innerHTML=obj[0]["descripcion"];
			}
		});
		
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php  echo $enrolado->id_localidad_registro_civil;?>] ,
			'desglose':1 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				document.getElementById("lugarcivilT").innerHTML=obj[0]["descripcion"];
			}
		});
		
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php  echo $enrolado->id_asu_localidad_domicilio;?>] ,
			'desglose':1 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				document.getElementById("localidadT").innerHTML=obj[0]["descripcion"];
			}
		});
	});
function vacunacion(id,tiene,fecha,prioridad)
{
	if(prioridad==1)
		color="#F66";
	else if(prioridad==2)
		color="#B90000";
	else if(prioridad==3)
		color="#FF6FB7";
	else
		color='white';
	$('#'+id).html("<span style='margin-left:-8px;'>"+tiene+"</span>");
	$('#'+id).attr("title",fecha);
	$('#'+id).css({'background-color':color,'cursor':'pointer'});
}
	</script>
    <style>
	td p
	{
		color:#000;
		font-weight:bold;
	}
	</style>
	<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>
        	<table width="100%">
            <tr>
                <td>
                  <div id="Accordion1" class="Accordion" tabindex="0" style="margin-left:-20px;">
                  
                  <!-- Datos basicos -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos Basicos</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nombre;?></div></td>
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
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->fecha_nacimiento;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><div id="lnacimientoT" style="width:100%; margin-left:20px;"></div></td>
                            </tr>
                          <tr>
                            <td><p align="right">CURP</p></td>
                            <td ><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->curp;?></div></td>
                            <td><p align="right">Nacionalidad</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nacionalidad;?></div></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    
                    <!-- Tipo de Beneficiario:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Tipo de Beneficiario</div>
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
                    <!-- Tutor -->
                  
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos de la Madre o Tutor</div>
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
                            <td><p align="right">CURP</p></td>
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
                    
                    <!--  Unidad Medica Tratante -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Unidad Medica Tratante</div>
                      <div class="AccordionPanelContent" >
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                      
                          <tr>
                            <td width="19%"><p align="right">Lugar</p></td>
                            <td width="81%" colspan="3"><div id="umt" style="width:100%; margin-left:20px;"></div>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!--  Registro civil -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Registro Civil</div>
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
                    
                    <!-- Direccion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Dirección</div>
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
                    
                    <!-- alergias y reacciones:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Historial de Alergias y Reacciones Febriles</div>
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
                      <div class="AccordionPanelTab">Control de Vacunación</div>
                      <div class="AccordionPanelContent" ><br />
                      <h2>Numero de días: <?php echo $vacunacion[0]->dias ?></h2>
                       <table width="100%" border="0" class="table vacuna" style="margin-left:10px" cellpadding="0" cellspacing="0">
                           <thead bgcolor="#8ECA35">
                              <tr>
                                <th width="30%" rowspan="2" scope="col">Vacunas</th>
                                <th colspan="6" scope="col">Dosis</th>
                              </tr>
                              <tr>
                                <th width="8%" scope="col">U</th>
                                <th width="8%" scope="col">R</th>
                                <th width="8%" scope="col">1a</th>
                                <th width="8%" scope="col">2a</th>
                                <th width="8%" scope="col">3a</th>
                                <th width="8%" scope="col">4a</th>
                              </tr>
                           </thead>
                           <?php 
							$tem=0; $i=0;
						   foreach($vacunacion as $vacuna)
						   {
							   $vc=$vacuna->descripcion." ";
							   $color=array("","#2828FF","#FF9326","#09F","#FDB802","#009","#26CDFD","#AC72FC","#FD9BF3","#960BA6","#FDAB02");
							   
							if($tem!=stripos($vc," "))
							{
								$a++;
							 echo " 
                           	  <tr>
                           		<td ><div id='var0$i' style='padding-left:14px; background-color:".@$color[$a]."; color:white'>".str_replace('Primera','',$vc)."</div></td>
                                <td ><div id='var1$i' align='center'></div></td>
                                <td ><div id='var2$i' align='center'></div></td>
                                <td ><div id='var3$i' align='center'></div></td>
                                <td ><div id='var4$i' align='center'></div></td>
                                <td ><div id='var5$i' align='center'></div></td>
                                <td ><div id='var6$i' align='center'></div></td>
                           	  </tr>";
							}
							$tem=stripos($vc," ");
							if($vacuna->tiene=='No aplicado')
							{
								
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
	vacunacion("var2<?php if(stripos($vc,"RP"))echo $i-1; else if(stripos($vc,"evacunaci")) echo $i-2; else echo $i?>",'<?php echo $vacuna->tiene ?>','<?php echo $vacuna->fecha ?>','<?php echo $vacuna->prioridad ?>');
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
                    
                    <!-- ira  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de IRA</div>
                      <div class="AccordionPanelContent"><br />
                      	
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%; margin-left:20px; margin-top:-3px">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">IRA</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  
                                  	<div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo getArray($iras);?></div>
                                       
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        
                      </div>
                    </div>
                    
                    <!-- eda  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de EDA</div>
                      <div class="AccordionPanelContent"><br />
                      	
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%; margin-left:20px; margin-top:-3px">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">EDA</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo getArray($edas);?></div>                           
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        
                      </div>
                    </div>
                    
                    <!-- consulta  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Consulta</div>
                      <div class="AccordionPanelContent"><br />
                      	
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%; margin-left:20px; margin-top:-3px">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">Consulta</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo getArray($consultas);?></div>                           
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        
                      </div>
                    </div>
                    
                    <!-- accion nutricional  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Acción Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      	
                        <table>
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
                                  <div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo getArray($nutricionales);?></div>                          
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        
                      </div>
                    </div>
                    <!-- nutricion PESO -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control Nutricional (PESO)</div>
                      <div class="AccordionPanelContent"><br />
                      <iframe width='98.5%' style='margin-left:5px;' border=0 height='700' src='/<?php  echo DIR_TES?>/graph/graph_init/Grafica/Nutrición PESO/<?php  echo urlencode(($control_nutricional));?>/<?php  echo urlencode(($label));?>/time_basic_axis/<?php echo $enrolado->fecha_nacimiento;?>'></iframe>
                      </div>
                    </div>  
                    
                    <!-- nutricion ALTURA -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control Nutricional (ALTURA)</div>
                      <div class="AccordionPanelContent"><br />
                      <iframe width='98.5%' style='margin-left:5px;' border=0 height='700' src='/<?php  echo DIR_TES?>/graph/graph_init/Grafica/Nutrición Altura/<?php  echo urlencode(($control_nutricional_altura));?>/<?php  echo urlencode(($label_altura));?>/time_basic_axis/<?php echo $enrolado->fecha_nacimiento;?>'></iframe>
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

function getArray($array)
{
	$id=0; $grid="";
	foreach($array as $dato)
	{
		$id++;
		$descripcion=$dato->descripcion;
		$fecha=$dato->fecha;
		$clase="row2";
		if($id%2)$clase="row1";
	
		$grid.= '<div class="'.$clase.'" style="height:30px">
				<table width="100%" >
				<tr>
					<th width="10%" >'.$id.'</th>
					<th width="50%" align="left">'.$descripcion.'</th>
					<th width="40%" align="left">'.$fecha.'</th>
				</tr>
				</table> 
			  </div>';
		 
	 }
	if($id==0)
	{
		$grid= '<div class="row1" style="height:30px">
				<table width="100%" >
					<tr>
						<th colspan=3 >No hay Datos</th>
					</tr>
				</table> 
			  </div>';
	}
	return $grid;
}
?>