<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=OutboundStackingList_" . $id_ves_voyage . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<style>
.basic {
	font-size: small;
}
</style>

<table border="0">
	<tr><td colspan="4" align="center"><font size="12pt"><b><i>OUTBOUND STACKING LIST <?=$ves_id?> <?=$voyg?></i></b></font></td></tr>
</table>
<br />
<br />
<table border='1'>
	<tr align="center" class="basic" bgcolor="#CCCCCC">
		<td>NO</td>
		<td>No Container</td>
		<td>Size</td>
		<td>ISO</td>
		<td>F/M</td>
		<td>Class</td>
		<td>WGT (Ton)</td>
		<td>Seal No</td>
		<td>Gate In</td>
		<td>Status</td>
		<td>Location</td>
		<td>In Vessel</td>
		<td>In/Out Voyage</td>
		<td>Out Vessel</td>
		<td>In/Out Voyage</td>
		<td>Operator</td>
		<td>POD</td>
		<td>Transhipment</td>
		<td>Hold</td>
		<td>Commodity</td>
		<td>Temperature</td>
		<td>OOG</td>
		<td>IMDG</td>
		<td>UNNO</td>
	</tr>
	<?php $numrow = 1;
		foreach ($datadetail as $rowd){ ?>
			<tr>
				<td><?=$numrow?></td>
				<td><?=$rowd['NO_CONTAINER']?></td>
				<td><?=$rowd['CONT_SIZE']?></td>
				<td><?=$rowd['ID_ISO_CODE']?></td>
				<td><?=$rowd['CONT_STATUS']?></td>
				<td><?=$rowd['ID_CLASS_CODE']?></td>
				<td><?=$rowd['WEIGHT']?></td>
				<td><?=$rowd['SEAL_NUMB']?></td>
				<td><?=$rowd['GT_DATE']?></td>
				<td><?=$rowd['STATUS']?></td>
				<td><?=$rowd['LOCATION']?></td>
				<td><?=$rowd['IN_VESSEL']?></td>
				<td><?=$rowd['IN_VOYAGE']?></td>
				<td><?=$rowd['OUT_VESSEL']?></td>
				<td><?=$rowd['OUT_VOYAGE']?></td>
				<td><?=$rowd['ID_OPERATOR']?></td>
				<td><?=$rowd['ID_POD']?></td>
				<td><?=$rowd['TRANSHIPMENT']?></td>
				<td><?=$rowd['HOLD_CONTAINER']?></td>
				<td><?=$rowd['ID_COMMODITY']?></td>
				<td><?=$rowd['TEMP']?></td>
				<td><?=$rowd['OOG']?></td>
				<td><?=$rowd['IMDG']?></td>
				<td><?=$rowd['UNNO']?></td>
			</tr>
	<?php $numrow++; } ?>
</table>