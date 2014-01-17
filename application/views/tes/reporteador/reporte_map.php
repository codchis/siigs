<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&;amp;language=es"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
    
    <script type="text/javascript" src="/resources/js/validaciones.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$("#pdf").click(function(e) {
        mandarimprimir(document,"popimpr","");
		return false;
    });
	$("#csv").click(function(e) {
		var data=$('#tabla').table2CSV();
       	download(data, "tescsv.csv", "text/csv");
		return false;
    });
	$("#exc").click(function(e) {
        var data=$('#popimpr').html();
       	download(data, "tesexcel.xls", "application/vnd.ms-excel");
		return false;
    });
	$.ajax({
	type: "POST",
	data: {
		'datos'   :<?php echo json_encode($array);?> ,
		'lugar'   :"Chiapas",
		'zoom'    :6,
		'rewrite' :0 },
	url: '/<?php echo DIR_TES."/graph/map/$lugar/$zoom/1";?>',
	})
	.done(function(dato)
	{
		$("#mapa").html(dato);
	});
	var ancho = $("#tabla").width()+600;
	$("#bodyPagina").width(ancho+"px");
});
</script>


<div align="right">
<a id="csv" href=""><img src="/resources/images/csv.png" style="border:none; "  title="Exportar a CSV" class="btn btn-primary"/></a>
<a id="pdf" href=""><img src="/resources/images/pdf.png" style="border:none; "  title="Exportar a PDF" class="btn btn-primary"/></a>
<a id="exc" href=""><img src="/resources/images/excel.png" style="border:none;" title="Exportar a EXCEL" class="btn btn-primary"/></a>
<input type="button" onClick="parent.jQuery.fancybox.close();" value="Cerrar" style="height:58px; " class="btn btn-primary">
</div>
<div id='popimpr'>
<h2>Listado - <?php echo urldecode($title) ?></h2>
<table width="100%" border="0" id="tabla">
  <tr>
    <td align="left" valign="top" width="20%">
        <table border="0" width="100%" id="tabla" class="table table-striped  ">
        <?php
        if(isset($datos))
        {
            if(count($datos)>0)
            {
                if(isset($headTable))
                    echo $headTable;
                else
                {
                    echo "<thead><tr>";
                    foreach ($datos[0] as $item => $value)
                    {
                         echo "<th><h5>".$item."</h5></td>";
                    }
                    echo "<thead></tr>";
                }
            }
            else echo "<tr><th><h4>No se encontro resultados</h4></td></tr>";
        }
        else echo "<tr><th><h4>No se encontro resultados</h4></td></tr>";
        ?>	
        
        <?php
        if(isset($datos))
        for($i=0;$i<count($datos);$i++)
        {
            echo "<tr>";
            foreach ($datos[$i] as $item => $value)
            {
                 echo "<td>".$value."</td>";
            }
            echo  "</tr>";
        }
        ?>
    </table>
    </td>
    <td align="left" valign="top">
    <div id="mapa" style="border: 1px rgb(51, 51, 51); height: 750px; width: 100%; display: block; position: relative; background-color: rgb(229, 227, 223); overflow: hidden; -webkit-transform: translateZ(0); padding-top:-30px"></div>
    </td>
  </tr>
</table>

</div>
<br />
<div align="right"><input type="button" onClick="parent.jQuery.fancybox.close();" value="Cerrar" style="height:58px; " class="btn btn-primary"></div>