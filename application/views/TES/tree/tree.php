<?php
$datos=json_encode($datos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Dynatree - Example</title>
	<link href="/resources/css/style.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/jquery/jquery.js" type="text/javascript"></script>
	<script src="/resources/jquery/jquery-ui.custom.js" type="text/javascript"></script>
	<script src="/resources/jquery/jquery.cookie.js" type="text/javascript"></script>

	<link href="/resources/src/skin/ui.dynatree.css" rel="stylesheet" type="text/css" id="skinSheet">
	<script src="/resources/src/jquery.dynatree.js" type="text/javascript"></script>

<script type="text/javascript">
	$(function(){
		// --- Initialize sample trees
		var treeData ="";
		var omitidos=[];
		omitidos[0]=null;
		$.ajax({
			type: "POST",
			data: {
				'idarbol':<?php echo $idarbol;?> ,
				'nivel':<?php echo $nivel;?> ,
				'omitidos': <?php echo json_encode($omitidos);?>,
				'seleccionados': <?php if($datos!="[null]")echo $datos; else {?>[parent.document.getElementById("<?php echo $id;?>").value]<?php }?> },
			//(count($omitidos) > 0) ? explode(',',$omitidos) : 'null';
			url: '/<?php echo DIR_SIIGS.'/raiz/getChildrenFromLevel';?>',
			})
			.done(function(dato)
			{
				treeData=jQuery.parseJSON(dato);
				$("#tree").dynatree({
			
				checkbox: true,
				<?php if($tipo=="radio"){?>
				classNames: {checkbox: "dynatree-radio"}, // cambia check por radio
				<?php } ?>
				selectMode: <?php echo $seleccion;?>,// 3 seleccion multiple parcial 2 multiselect 1 select unico
				children: treeData,
				onSelect: function(select, node) 
				{
					// Display list of selected nodes
					var selNodes = node.tree.getSelectedNodes();
					// convert to title/key array
					var selValor = $.map(selNodes, function(node)
					{
						   return "[id=" + node.data.key + ":-Texto=" + node.data.title + "]";
					});
					var selKeys = $.map(selNodes, function(node)
					{
						   return node.data.key;
					});
					var selTitle = $.map(selNodes, function(node)
					{
						   return node.data.title;
					});
					$("#echoSelection").text(selValor.join(", "));
					parent.document.getElementById("<?php echo $id;?>").value=selKeys.join(", ");
					parent.document.getElementById("<?php echo $text;?>").value=selTitle.join(", ");
				},
				onQuerySelect: function(select, node) 
				{
					if( node.data.isFolder )
						return false;
				},
				onClick: function(node, event) 
				{
					// We should not toggle, if target was "checkbox", because this
					// would result in double-toggle (i.e. no toggle)
					if( node.getEventTargetType(event) == "title" )
						node.toggleSelect();
				},
				
				onKeydown: function(node, event) 
				{
					if( event.which == 32 ) {
						node.toggleSelect();
						return false;
					}
				},
				// The following options are only required, if we have more than one tree on one page:
				cookieId: "dynatree-Cb2",
				idPrefix: "dynatree-Cb2-"
			});
		});
		<!-- End_Exclude -->
	});
</script>
</head>
<body >

<br>	
		<?php if($menu){?>
		<a href="#" id="btnSelectAll" class="add">Marcar</a> -
		<a href="#" id="btnDeselectAll" class="cancelar">Desmarcar</a> -
		<a href="#" id="btnToggleSelect" class="agregar">Alternar</a>
		<?php } ?>
        <a href="#" onClick="parent.jQuery.fancybox.close();" class="guardar">Ok</a>
		<h1><?php echo $titulo; ?></h1>
       <div>Seleccionado: <span id="echoSelection">-</span></div>
	<div id="tree"></div>
	
</body>
</html>
