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
});
</script>


<div align="right">
<a id="csv" href=""><img src="/resources/images/csv.png" style="border:none; "  title="Exportar a CSV" class="btn btn-primary"/></a>
<a id="pdf" href=""><img src="/resources/images/pdf.png" style="border:none; "  title="Exportar a PDF" class="btn btn-primary"/></a>
<a id="exc" href=""><img src="/resources/images/excel.png" style="border:none;" title="Exportar a EXCEL" class="btn btn-primary"/></a>
<input type="button" onClick="parent.jQuery.fancybox.close();" value="Cerrar" style="height:58px; width::50px;" class="btn btn-primary">
</div>
<div id='popimpr' class="table table-striped  ">
<h2>Listado - <?php echo urldecode($title) ?></h2>
<table border="0" width="100%" id="tabla">
	<tr>
    <?php
		foreach ($datos[0] as $item => $value)
		{
    		 echo "<th><h5>".$item."</h5></td>";
		}
	?>	
	</tr>
	<?php
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
</div>
<div align="right"><input type="button" onClick="parent.jQuery.fancybox.close();" value="Cerrar" style="height:50px; width::50px;" class="btn btn-primary"></div>