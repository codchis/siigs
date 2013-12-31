	<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
    
    <script type="text/javascript" src="/resources/js/validaciones.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
    $("a#fba1").fancybox({
		'width'             : '90%',
		'height'            : '90%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',								
	}); 
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
	
	
});
</script>
<h2><?php echo $title ?></h2>
<div id='popimpr' class="table table-striped  " >
<table border="0" width="100%" id="tabla">
	<tr>
		<th><h2>Atributo</h2></th>
		<th><h2>Valor</h2></th>
		<th><h2>Listar</h2></th>
		
	</tr>
	<?php if (isset($datos)) foreach ($datos as $dato): ?>
	<tr>
		<td><?php echo $dato["atributo"] ?></td>
		<td><?php echo $dato["valor"] ?></td>
		<td><a href="/<?php echo DIR_TES?>/reporte_sincronizacion/view/<?php echo $dato["lista"] ?>/<?php echo $dato["atributo"] ?>" id="fba1"><img src="/resources/images/listar.png" style="border:none; width:30px; height:30px;" title="ver detalle" /></a></td>
		
	</tr>
	<?php endforeach ?>
</table>
</div>
<div align="right">
<a id="csv" href=""><img src="/resources/images/csv.png" style="border:none; "  title="Exportar a CSV" class="btn btn-primary"/></a>
<a id="pdf" href=""><img src="/resources/images/pdf.png" style="border:none; "  title="Exportar a PDF" class="btn btn-primary"/></a>
<a id="exc" href=""><img src="/resources/images/excel.png" style="border:none;" title="Exportar a EXCEL" class="btn btn-primary"/></a>
</div>