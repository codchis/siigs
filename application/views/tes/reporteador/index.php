<script type="text/javascript">
$(document).ready(function(){
    
});
</script>
<?php 
$opcion_index = Menubuilder::isGranted(DIR_TES.'::reporteador::index');
$opcion_rpt1 = Menubuilder::isGranted(DIR_TES.'::reporteador::cobXtipo');
$opcion_rpt2 = Menubuilder::isGranted(DIR_TES.'::reporteador::concAct');
$opcion_rpt3 = Menubuilder::isGranted(DIR_TES.'::reporteador::segRv');
$reports = array(
		1  => 'Cobertura por Tipo de Biológico',
		2  => 'Concentrado de Actividades',
		3  => 'Seguimiento RV-1 y RV-5 a menores de 1 año',
);
$jurisdicciones = array();
$municipios = array();
$localidades = array();
$ums = array();
if (!$opcion_rpt1) unset($reports[1]);
if (!$opcion_rpt2) unset($reports[2]);
if (!$opcion_rpt3) unset($reports[3]);
?>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />'; ?>
<fieldset style="width: 50%">
<?php echo form_open(DIR_TES.'/reporteador/index/'); ?>
<table>
<tr>
<td>Reporte a generar:</td>
<td colspan='3'> <?php  if(isset($reports)) echo form_dropdown('reports', $reports); ?></td>
</tr>
<tr>
<td>Jurisdicción:</td>
<td> <?php  echo form_dropdown('jurisdicciones', $jurisdicciones); ?></td>
<td>Municipio:</td>
<td> <?php  echo form_dropdown('municipios', $municipios); ?></td>
</tr>
<tr>
<td>Localidad:</td>
<td> <?php  echo form_dropdown('localidades', $localidades); ?></td>
<td>UM:</td>
<td> <?php  echo form_dropdown('ums', $ums); ?></td>
</tr>
<tr>
<td colspan='4'>
<input type="submit" name="btnProcesar" id="btnProcesar" value="Procesar" /></td>
</tr>
</table>
</form>
</fieldset>