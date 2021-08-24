<?php
	/*header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=bch_reports.xls");
	header("Pragma: no-cache");
	header("Expires: 0");*/

	//$db = getDB('billing');
	//debux($data_detail);die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<style>
.Middle{
}
.CenterAndMiddle{
	text-align: center;
	vertical-align:middle;
}
</style>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Report BCH</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
<table>
</table>
<table>
	<tr><td colspan='25' style='text-align: center; vertical-align:middle'>REPORT</td></tr>
	<tr><td colspan='25' style='text-align: center; vertical-align:middle'>BOX CRANE HOUR</td></tr>
	<tr><td colspan='25' style='text-align: center; vertical-align:middle'><?php echo date("Y-m-d H:i:s");?></td></tr>
</table>
<table>
</table>
<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			VESSEL/VOY
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			KADE
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			QCC/HMC
		</td>		
		<td style='text-align: center; vertical-align:middle' colspan = '5' rowspan = '2'>
			WORKING HOUR QCC
		</td>		
		<td style='text-align: center; vertical-align:middle' colspan = '6'>
			DISCH
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			TOTAL DISCH
		</td>
		<td style='text-align: center; vertical-align:middle' colspan = '6'>
			LOAD
		</td>	
		<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			TOTAL LOAD
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			TOTAL MOVES
		</td>
		<!--<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			GCR
		</td>-->
		<td style='text-align: center; vertical-align:middle' rowspan = '3'>
			BCH
		</td>
	</tr>	
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle' colspan = '3'>
			FULL
		</td>		
		<td style='text-align: center; vertical-align:middle' colspan = '3'>
			EMPTY
		</td>		
		<td style='text-align: center; vertical-align:middle' colspan = '3'>
			FULL
		</td>		
		<td style='text-align: center; vertical-align:middle' colspan = '3'>
			EMPTY
		</td>		
	</tr>
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle'>
			START
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			FINISH
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			WORK HOURS
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			SUSPEND
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			EFFECTIVE HOURS
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			20
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			40
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			45
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			20
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			40
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			45
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			20
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			40
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			45
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			20
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			40
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			45
		</td>	
	</tr>
	<?
	foreach ($data_detail as $row) {
		$idle = 0;
		$disch_20_full = 0;
		$disch_40_full = 0;
		$disch_45_full = 0;
		$disch_20_mty = 0;
		$disch_40_mty = 0;
		$disch_45_mty = 0;
		$load_20_full = 0;
		$load_40_full = 0;
		$load_45_full = 0;
		$load_20_mty = 0;
		$load_40_mty = 0;
		$load_45_mty = 0;
		$load_total = 0;
		$disch_total = 0;
		
		if ($row["IDLE"] != "") $idle = $row["IDLE"];
		if ($row["DISCH_20_FULL"] != "") $disch_20_full = $row["DISCH_20_FULL"];
		if ($row["DISCH_40_FULL"] != "") $disch_40_full = $row["DISCH_40_FULL"];
		if ($row["DISCH_45_FULL"] != "") $disch_45_full = $row["DISCH_45_FULL"];
		if ($row["DISCH_20_EMPTY"] != "") $disch_20_mty = $row["DISCH_20_EMPTY"];
		if ($row["DISCH_40_EMPTY"] != "") $disch_40_mty = $row["DISCH_40_EMPTY"];
		if ($row["DISCH_45_EMPTY"] != "") $disch_45_mty = $row["DISCH_45_EMPTY"];
		if ($row["LOAD_20_FULL"] != "") $load_20_full = $row["LOAD_20_FULL"];
		if ($row["LOAD_40_FULL"] != "") $load_40_full = $row["LOAD_40_FULL"];
		if ($row["LOAD_45_FULL"] != "") $load_45_full = $row["LOAD_45_FULL"];
		if ($row["LOAD_20_EMPTY"] != "") $load_20_mty = $row["LOAD_20_EMPTY"];
		if ($row["LOAD_40_EMPTY"] != "") $load_40_mty = $row["LOAD_40_EMPTY"];
		if ($row["LOAD_45_EMPTY"] != "") $load_45_mty = $row["LOAD_45_EMPTY"];
		if ($row["LOAD_TOTAL"] != "") $load_total = $row["LOAD_TOTAL"];
		if ($row["DISCH_TOTAL"] != "") $disch_total = $row["DISCH_TOTAL"];
		
		echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["VESSEL_NAME"] . " " . "(" . $row["VOY_IN"] . " - " . $row["VOY_OUT"] . ")" . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["KADE_NAME"] . " " . "(" . $row["START_METER"] . " - " . $row["END_METER"] . ")" . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["MCH_NAME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["START_WORK"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["END_WORK"] . "</td>";
		echo "<td style='mso-number-format:\"[h]:mm:ss\"'>" . $row["WORK_TIME"] . "</td>";
		echo "<td style='mso-number-format:\"[h]:mm:ss\"'>" . $idle . "</td>";
		echo "<td style='mso-number-format:\"[h]:mm:ss\"'>" . $row["EFFECTIVE_TIME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $disch_20_full . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $disch_40_full . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $disch_45_full . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $disch_20_mty . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $disch_40_mty . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $disch_45_mty . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $disch_total . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $load_20_full . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $load_40_full . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $load_45_full . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $load_20_mty . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $load_40_mty . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $load_45_mty . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $load_total . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . ($disch_total+$load_total) . "</td>";
		//echo "<td style='mso-number-format:\"\@\"'>" . "". "</td>";
		echo "<td style='mso-number-format:\"0\.000\"'>" . (($disch_total+$load_total)/($row["EFFECTIVE_TIME"]*24)) . "</td>" . "</tr>";
	}
	?>
</table>
</body>
</html>