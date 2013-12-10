    <link href="/resources/css/grid.css" rel="stylesheet" type="text/css" /> 
    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    <script>
	$(document).ready(function()
	{
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_asu_localidad_nacimiento;?>] ,
			'desglose':1 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
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
			'claves':[<?php echo $enrolado->id_asu_um_tratante;?>] ,
			'desglose':1 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
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
			'claves':[<?php echo $enrolado->id_asu_localidad_domicilio;?>] ,
			'desglose':1 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
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
	</script>
	<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>
    <h2><?php echo $title ?></h2>
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
                            <td width="31%"><?php echo $enrolado->nombre;?></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%"><?php echo $enrolado->sexo;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><?php echo $enrolado->apellido_paterno;?></td>
                            <td><p align="right">Tipo de Sangre</p></td>
                            <td><?php echo $enrolado->tsangre;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><?php echo $enrolado->apellido_materno;?></td>
                            <td><p align="right">Fecha de Nacimiento</p></td>
                            <td><?php echo $enrolado->fecha_nacimiento;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><div id="lnacimientoT"></div></td>
                            </tr>
                          <tr>
                            <td><p align="right">CURP</p></td>
                            <td ><?php echo $enrolado->curp;?></td>
                            <td><p align="right">Nacionalidad</p></td>
                            <td><?php echo $enrolado->nacionalidad;?></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    
                    <!-- Tipo de Beneficiario:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Tipo de Beneficiario</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        	<?php 
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
							?>
                        </code>
                      </div>
                    </div>
                    <!-- Tutor -->
                  
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos de la Madre o Tutor</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><?php echo $enrolado->nombreT;?></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%"><?php echo $enrolado->sexoT;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><?php echo $enrolado->paternoT;?></td>
                            <td><p align="right">CURP</p></td>
                            <td><?php echo $enrolado->curpT;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><?php echo $enrolado->maternoT;?></td>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><?php echo $enrolado->telefonoT;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Celular</p></td>
                            <td><?php echo $enrolado->celularT;?></td>
                            <td><p align="right">Compania Celular</p></td>
                            <td><?php echo $enrolado->operadoraT;?></td>
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
                            <td width="31%"><?php echo $enrolado->fecha_registro;?></td>
                            <td width="25%"><p align="right">&nbsp;</p></td>
                            <td width="25%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar</p></td>
                            <td colspan="3"><div id="lugarcivilT"></div></tr>
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
                            <td width="31%"><?php echo $enrolado->calle_domicilio;?></td>
                            <td width="25%"><p align="right">Número</p></td>
                            <td width="25%"><?php echo $enrolado->numero_domicilio;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Referencia</p></td>
                            <td colspan="3"><?php echo $enrolado->referencia_domicilio;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Colonia</p></td>
                            <td><?php echo $enrolado->colonia_domicilio;?></td>
                            <td><p align="right">CP</p></td>
                            <td><?php echo $enrolado->cp_domicilio;?></td>
                          </tr>
                          <tr>
                            <td><p align="right">Localidad</p></td>
                            <td colspan="3"><div id="localidadT"></div></tr>
                          <tr>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><?php echo $enrolado->telefono_domicilio;?></td> 
                            <td><p align="right">Celular</p></td> 
                            <td><?php echo $enrolado->celuar;?></td>                          
                          </tr>
                          <tr>
                            <td><p align="right">Compania Celular</p></td>
                            <td><?php echo $enrolado->operadora;?></td> 
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
                      	<code style="margin-left:20px; width:60%">
                        	<?php 
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
							?>
                            
                        </code>
                      </div>
                    </div>
                    
                    
                    <!-- vacunacion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Vacunación</div>
                      <div class="AccordionPanelContent"><br />
                      </div>
                    </div>
                    
                    <!-- ira  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de IRA</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">IRA</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  
                                  	<?php echo getArray($iras);?>
                                       
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    
                    <!-- eda  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de EDA</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">EDA</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($edas);?>                           
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    
                    <!-- consulta  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Consulta</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">Consulta</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($consultas);?>                           
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    
                    <!-- accion nutricional  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Acción Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">A. Nutriconal</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($nutricionales);?>                          
                              </td>
                                 <td valign="top">&nbsp;</td>
                          </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    <!-- nutricion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      <iframe width='100%' style='margin-left:5px;' border=0 height='500' src='/<?php echo DIR_TES?>/Graph/graph/grafica/Nutricion/<?php echo urlencode(($control_nutricional));?>/<?php echo urlencode(($label));?>'></iframe>
                      </div>
                    </div>                                        
                    
                    </td>
            </tr>
            <tr>
                <td>
                <span id="enviandoof" style="margin-left:-20px;">
                <input type="button" value="Cancelar" onclick="window.location.href='/<?php echo DIR_TES?>/enrolamiento/'" />
                </span>
    			
                </td>
            </tr>
        </table>
	</td></tr></table>
	<script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("Accordion1", { useFixedPanelHeights: false, defaultPanel: 0 });
</script>

<?php
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