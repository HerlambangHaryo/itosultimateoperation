<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LoadingList_" . $id_ves_voyage . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<style>
.basic  {
	font-size: small;
}
</style>
<table>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'>REPORT</td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'><?=$vesvoy->VESSEL?></td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'>OUTBOUND LIST</td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'><?php echo date("Y-m-d H:i:s");?></td></tr>
</table>
<table border="1">
    <tr align="center" class="basic">
    <td width="140">No.</td>
	<td width="140">No. Container</td>
	<td width="80">Stowage Plan</td>
	<td width="80">Stowage Real</td>
	<td width="80">ISO</td>
	<td width="80">Class</td>
	<td width="80">OPR</td>
	<td width="80">F/M</td>
	<td width="80">POL</td>
	<td width="80">POD</td>
	<td width="80">POR</td>
	<td width="100">Yard</td>
	<td width="100">Complete Loading</td>
	<td width="80">WGT(Ton)</td>
	<td width="80">Temp.(C)</td>
	<td width="80">UNNO</td>
	<td width="80">IMDG</td>
	<td width="80">Comm.</td>
	<td width="80">Size</td>
	<td width="80">Type</td>
	<td width="80">Height</td>
	<td width="80">TL</td>
	<td width="80">QC Plan</td>
	<td width="80">QC Real</td>
	<td width="80">YC PLan</td>
	<td width="80">YC Real</td>
	<td width="80">OH</td>
	<td width="80">OW-R</td>
	<td width="80">OW-L</td>
	<td width="80">OL-F</td>
	<td width="80">OL-B</td>
	<td width="80">OW</td>
	<td width="80">Handling</td>
    </tr>
<?php
$no =1;
foreach ($datadetail['data'] as $rowd){
?>
    <tr align="center">
    <td><?=$no++?></td>
	<td class="basic"><?=$rowd['NO_CONTAINER']?></td>
	<td><?=$rowd['STOWAGE_PLAN']?></td>
	<td><?=$rowd['STOWAGE']?></td>
	<td><?=$rowd['ID_ISO_CODE']?></td>
	<td><?=$rowd['ID_CLASS_CODE']?></td>
	<td><?=$rowd['ID_OPERATOR']?></td>
	<td><?=$rowd['CONT_STATUS']?></td>
	<td><?=$rowd['ID_POL']?></td>
	<td><?=$rowd['ID_POD']?></td>
	<td><?=$rowd['ID_POR']?></td>
	<td><?=$rowd['YARD_POS']?></td>
	<td><?=$rowd['CONFIRM_DATE_']?></td>
	<td><?=number_format($rowd['WEIGHT'],1)?></td>
	<td><?=$rowd['TEMP']?></td>
	<td><?=$rowd['UNNO']?></td>
	<td><?=$rowd['IMDG']?></td>
	<td><?=$rowd['ID_COMMODITY']?></td>
	<td><?=$rowd['CONT_SIZE']?></td>
	<td><?=$rowd['CONT_TYPE']?></td>
	<td><?=$rowd['CONT_HEIGHT']?></td>
	<td><?=$rowd['TL_FLAG']?></td>
	<td><?=$rowd['QC_PLAN']?></td>
	<td><?=$rowd['QC_REAL']?></td>
	<td><?=$rowd['YC_PLAN']?></td>
	<td><?=$rowd['YC_REAL']?></td>
	<td><?=$rowd['OVER_HEIGHT']?></td>
	<td><?=$rowd['OVER_RIGHT']?></td>
	<td><?=$rowd['OVER_LEFT']?></td>
	<td><?=$rowd['OVER_FRONT']?></td>
	<td><?=$rowd['OVER_REAR']?></td>
	<td><?=$rowd['OVER_WIDTH']?></td>
	<td><?=$rowd['ID_SPEC_HAND']?></td>
    </tr>
<?php
}
?>
</table>