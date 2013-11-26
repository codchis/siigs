    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    
    <script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
    <script>
	$(document).ready(function()
	{
		$("a#fba1").fancybox({
			'width'             : '50%',
			'height'            : '60%',				
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
			'type'			: 'iframe',				
			'onClosed'		: function() {
				getcurp();
			}						
		});   
		
		$("#nombre,#paterno,#materno,#fnacimiento,#lnaciminetoT").blur(function()
		{       
			getcurp();
		});		
	});
	function getcurp()
	{
		var ap=$("#paterno").val();
		var am=$("#materno").val();
		var no=$("#nombre").val();
		var se=$("input[name='sexo']:checked").val();
		var fn=$("#fnacimiento").val();
		var ed=$("#lnaciminetoT").val();
		var a=fn.substr(0,4);
		var m=fn.substr(5,2);
		var d=fn.substr(8,2);
		if(ap!=""&&am!=""&&no!=""&&se!=""&&fn!=""&&ed!="")
		{
			$("#curp").val("");
			$("#curpl").html("");		
			$("#curp2").val("");
			$.ajax({
				url: "obtenercurp/curp/"+ap+"/"+am+"/"+no+"/"+d+"/"+m+"/"+a+"/"+se+"/"+ed+"/2",
				type: "POST",
				data: "json",
				success:function(data){
					var obj = jQuery.parseJSON( data );
					var curp=obj[0]["curp"];
					$("#curp").val(curp.substr(0,curp.length-5));
					$("#curpl").html('<strong>'+curp.substr(0,curp.length-5)+'&nbsp;</strong>');		
					$("#curp2").val(curp.substr(curp.length-5,5));		
				}
			});
		}
	 	return false;
	}
	</script>
	<table align="center" width="94%" border="0" cellpadding="0" cellspacing="0"><tr><td>
    	<div id="alert"></div>
        <form id="frmSolicitud" name="frmSolicitud" method="post" >
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
                            <td width="25%"><p align="right">Nombre</p></td>
                            <td width="25%"><input name="nombre" type="text" id="nombre" style="width:80%; margin-left:10px;" required></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%">
                              <label style=" margin-left:10px;">
                                <input type="radio" name="sexo" value="M" id="sexo_1" onclick="getcurp();">
                                Masculino</label>
                              <label>
                                <input type="radio" name="sexo" value="F" id="sexo_2" onclick="getcurp();">
                                Femenino</label>
                             </td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><input name="paterno" type="text" id="paterno" style="width:80%; margin-left:10px;" ></td>
                            <td><p align="right">Tipo de Sangre</p></td>
                            <td>
                              <select name="sangre" style="width:80%; margin-left:10px;">
                            <option value="">Seleccione</option>
                              <option value="O -">O -</option>
                              <option value="O +">O +</option>
                              <option value="A -">A -</option>
                              <option value="A +">A +</option>
                              <option value="B -">B -</option>
                              <option value="B +">B +</option>
                              <option value="AB -">AB -</option>
                              <option value="AB +">AB +</option>
                            
                            </select></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><input name="materno" type="text" id="materno" style="width:80%; margin-left:10px;" ></td>
                            <td><p align="right">Fecha de Nacimiento</p></td>
                            <td><input name="fnacimiento" type="date" id="fnacimiento" style="width:74%; margin-left:10px;"></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><input name="lnaciminetoT" type="text" id="lnaciminetoT" style="width:78%; margin-left:10px;" >
                            	<input name="lnacimineto" type="hidden" id="lnacimineto" >                              
                              <a href="/TES/Tree/tree/TES/Lugar de Nacimiento/3/check/0/lnacimineto/lnaciminetoT/datos/" id="fba1" class="cat">Seleccionar</a>
                              </td>
                            </tr>
                          <tr>
                            <td><p align="right">CURP</p></td>
                            <td colspan="2"><input name="curp" type="hidden" id="curp" size="5" ><span id="curpl" style="margin-left:10px; font-size:16px; letter-spacing:1px; width:60%">XXXXXXXXXXXXX</span><input name="curp2" type="text" id="curp2" size="8" style="letter-spacing:1px"></td>
                            <td>&nbsp;</td>
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
                            <td width="25%"><p align="right">Fecha</p></td>
                            <td width="25%"><input name="fnacimiento2" type="date" id="fnacimiento2" style="width:75%; margin-left:10px;"></td>
                            <td width="25%"><p align="right">&nbsp;</p></td>
                            <td width="25%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><input name="lnacimineto2" type="text" id="lnacimineto2" style="width:78%; margin-left:10px;" >
                              <a href="" id="fba2" class="cat">Seleccionar</a>
                          </tr>
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
                            <td width="25%"><p align="right">Calle</p></td>
                            <td width="25%"><input name="nombre2" type="text" id="nombre2" style="width:80%; margin-left:10px;" ></td>
                            <td width="25%"><p align="right">Número</p></td>
                            <td width="25%"><input name="nombre3" type="text" id="nombre3" style="width:75%; margin-left:10px;" ></td>
                          </tr>
                          <tr>
                            <td><p align="right">Colonia</p></td>
                            <td><input name="paterno2" type="text" id="paterno2" style="width:80%; margin-left:10px;" ></td>
                            <td><p align="right">CP</p></td>
                            <td><input name="nombre4" type="text" id="nombre4" style="width:75%; margin-left:10px;" ></td>
                          </tr>
                          <tr>
                            <td><p align="right">Localidad</p></td>
                            <td colspan="3"><input name="lnacimineto3" type="text" id="lnacimineto3" style="width:78%; margin-left:10px;" >
                              <a href="" id="fba3" class="cat">Seleccionar</a></td>
                          </tr>
                        </table>
                        <br />
                      </div>
                    </div>
                    
                    <!-- alergias y reacciones:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Historial de Alergias y Reacciones Febriles:</div>
                      <div class="AccordionPanelContent"><br />
                      
                      </div>
                    </div>
                    
                    
                    <!-- vacunacion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Vacunación</div>
                      <div class="AccordionPanelContent"><br />                      
                      
                      </div>
                    </div>
                    
                    <!-- nutricion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      
                      </div>
                    </div>
                    
                    <!-- otros controles  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Otros Controles</div>
                      <div class="AccordionPanelContent"><br />
                      
                      </div>
                    </div>
                    
                    </td>
            </tr>
            <tr>
                <td>
                <span id="enviandoof" style="margin-left:-20px;">
                <input type="submit" name="buscar" id="buscar" value="Guardar" class="guardar"/>
                <input type="button" name="buscar" id="buscar" value="Cancelar" class="cancelar"/>
                </span>
    			
                </td>
            </tr>
        </table>
            
        </form>
	</td></tr></table>

<script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("Accordion1", { useFixedPanelHeights: false, defaultPanel: 0 });
</script>
