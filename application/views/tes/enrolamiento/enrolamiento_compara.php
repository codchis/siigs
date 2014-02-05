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
		var ancho = $("#tabla").width()+600;
		$("#bodyPagina").width(ancho+"px");
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
			'claves':[<?php  echo $enrolado->id_asu_localidad_domicilio;?>] ,
			'desglose':5 },
		url: '/<?php  echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("localidadT").innerHTML=des;
			}
		});
	});
	</script>
    <style>
	td p
	{
		font-family:Open Sans Condensed ,sans-serif; font-size: 15px; font-weight: bold; text-shadow: 0 0px 0 #FFFFFF;
	}
	</style>
    <h2 style="margin-top:-90px; font-size:28px;">Comparativa</h2>
	<table align="center" width="100%" border="3" cellpadding="0" cellspacing="0" id="tabla"><tr><td>
        	<table width="100%">
            <tr>
                <td>
                  <div id="Accordion1" class="Accordion" tabindex="0" style="">
                  
                  <!-- Datos basicos -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab similar">Datos Basicos </div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="21%"><p align="right">Nombre</p></td>
                            <td width="29%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nombre;?></div></td>
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
                    
                    <!-- Tutor -->
                  
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab similar">Datos de la Madre o Tutor</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="21%"><p align="right">Nombre</p></td>
                            <td width="29%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nombreT;?></div></td>
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
                    
                    <!-- Direccion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab similar">Domicilio</div>
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
                    
                    
                                                          
                    
                </td>
                
                <td>
                
                <div id="Accordion1" class="Accordion" tabindex="0" style="">
                  
                  <!-- Datos basicos -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab compara">Datos Basicos (Capturados)</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="21%"><p align="right">Nombre</p></td>
                            <td width="29%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php echo $prod1[0];?></div></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod1[5];?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod1[1];?></div></td>
                            <td><p align="right">Tipo de Sangre</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod1[6];?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod1[2];?></div></td>
                            <td><p align="right">Fecha de Nacimiento</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo date("Y-m-d",strtotime($prod1[7]));?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><div id="" style="width:100%; margin-left:20px;"><?php  echo $prod1[3];?></div></td>
                            </tr>
                          <tr>
                            <td><p align="right">CURP</p></td>
                            <td ><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod1[4];?></div></td>
                            <td><p align="right">Nacionalidad</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod1[8];?></div></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!-- Tutor -->
                  
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab compara">Datos de la Madre o Tutor (Capturados)</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="21%"><p align="right">Nombre</p></td>
                            <td width="29%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[1];?></div></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[4];?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[2];?></div></td>
                            <td><p align="right">CURP</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[0];?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[3];?></div></td>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[5];?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Celular</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[6];?></div></td>
                            <td><p align="right">Compania Celular</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod2[7];?></div></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!-- Direccion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab compara">Domicilio (Capturados)</div>
                      <div class="AccordionPanelContent">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Calle</p></td>
                            <td width="31%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[0];?></div></td>
                            <td width="25%"><p align="right">Número</p></td>
                            <td width="25%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[1];?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Referencia</p></td>
                            <td colspan="3"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[2];?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Colonia</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[3];?></div></td>
                            <td><p align="right">CP</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[4];?></div></td>
                          </tr>
                          <tr>
                          <td colspan="4" width="100%">
                              <table width="89%" border="0">
                                <tr>
                                  <td width="22%" align="right"><p>Ageb&nbsp;</p></td>
                                  <td ><div style="width:75%; margin-left:15px; margin-top:-3px"><?php  echo $prod3[5];?></div></td>
                                  <td  align="right"><p>Sector</p></td>
                                  <td ><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[6];?></div></td>
                                  <td  align="right"><p>Manzana</p></td>
                                  <td width="16%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[7];?></div></td>
                                </tr>
                              </table>
                          </td>
                          </tr>
                          <tr>
                            <td><p align="right">Localidad</p></td>
                            <td colspan="3"><div id="localidadT" style="width:100%; margin-left:20px;"><?php  echo $prod3[8];?></div></tr>
                          <tr>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[9];?></div></td> 
                            <td><p align="right">Celular</p></td> 
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[10];?></div></td>                          
                          </tr>
                          <tr>
                            <td><p align="right">Compania Celular</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $prod3[11];?></div></td> 
                            <td></td> 
                            <td></td>                          
                          </tr>
                        </table>
                        <br />
                      </div>
                    </div>
                </td>
            </tr>
            
        </table>
	</td></tr></table>
<?php 
}
else
{
 echo "<div class='$infoclass'>".$msgResult."</div><div><br>";
 echo '<a href="" class="btn btn-primary" onclick="window.location.href=\'/'.DIR_TES.'/enrolamiento/\';return false;">Regresar</a></div>';
}
?>